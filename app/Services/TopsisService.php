<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\AssessmentAlternative;
use App\Models\AssessmentStep;
use App\Models\Criterion;
use App\Services\FuzzyService;
use Illuminate\Support\Facades\DB;

final class TopsisService
{
    /** Jalankan TOPSIS full pipeline dan simpan setiap tahap */
    public function run(Assessment $a): void
    {
        try {
            DB::transaction(function () use ($a) {
                // status
                $a->update(['status' => 'running']);

                // 0) Ambil daftar alternatif (exclude yang di-flag)
                $alts = $a->alternatives()
                    ->with(['route.mountain'])
                    ->where('is_excluded', false)
                    ->get();

                if ($alts->isEmpty()) {
                    $this->storeStep($a, 'RANKING', ['ranking' => [], 'CC' => []]);
                    $a->update(['status' => 'done', 'n_alternatives' => 0, 'n_criteria' => 0]);
                    return;
                }

                // 1) Filter kriteria berdasarkan assessment.pure_formula
                $criteria = Criterion::where('active', 1)
                    ->when($a->pure_formula, fn($q) => $q->whereIn('source', ['MOUNTAIN', 'ROUTE']))
                    ->orderBy('sort_order')
                    ->orderByRaw("CAST(SUBSTRING(code, 2) AS UNSIGNED)")
                    ->get();

                $cols = $criteria->pluck('code')->values()->all();               // ["C15","C16",...]
                $types = $criteria->pluck('type', 'code')->toArray();             // ["C15"=>"cost", ...]
                $colIndex = array_flip($cols);

                // 2) Build X murni tanpa imputasi
                [$rows, $X, $missing] = $this->buildXPure($alts, $criteria);

                // 3) Keluarkan alternatif yang missing (strict)
                if (config('spk.strict_missing') && !empty($missing)) {
                    [$rows, $X] = $this->dropRowsWithMissing($rows, $X, $missing);
                    $this->storeStep($a, 'VALIDATION_MISSING', [
                        'algorithm' => 'TOPSIS',
                        'dropped' => $missing,
                        'dropped_count' => count($missing)
                    ]);
                }

                $this->storeStep($a, 'MATRIX_X', [
                    'rows' => $rows,
                    'cols' => $cols,
                    'X' => $X,
                    'algorithm' => 'TOPSIS',
                ]);

                // 2.1) Snapshot dataset (reproducible)
                DB::table('assessment_dataset_snapshots')->updateOrInsert(
                    ['assessment_id' => $a->id],
                    [
                        'X_json' => json_encode($X),
                        'rows_json' => json_encode($rows),
                        'cols_json' => json_encode($cols),
                        'criteria_version' => $criteria->max('version') ?? 'v1',
                        'weights_version' => 'v1',
                        'notes' => 'auto-snapshot',
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );

                // 3) Normalisasi R
                [$R, $denoms, $skipCols] = $this->normalizeR($X);
                $this->storeStep($a, 'NORMALIZED_R', [
                    'rows' => $rows,
                    'cols' => $cols,
                    'R' => $R,
                    'denoms' => $denoms,
                    'skip_cols' => $skipCols,
                ]);

                // 4) Weights → Y
                $W = $this->resolveWeights($a, $cols);           // map code → w
                $Y = $this->applyWeights($R, $cols, $W, $skipCols);
                $this->storeStep($a, 'WEIGHTED_Y', [
                    'rows' => $rows,
                    'cols' => $cols,
                    'Y' => $Y,
                    'weights' => $W,
                    'skip_cols' => $skipCols,
                ]);

                // 5) A+ / A-
                [$Aplus, $Aminus] = $this->idealSolutions($Y, $cols, $types, $skipCols);
                $this->storeStep($a, 'IDEAL_SOLUTION', [
                    'cols' => $cols,
                    'A_plus' => $Aplus,
                    'A_minus' => $Aminus,
                    'types' => $types,
                    'skip_cols' => $skipCols,
                ]);

                // 6) Distances S+ / S- (+ kontribusi per kriteria untuk explainability)
                [$Splus, $Sminus, $dPlus2, $dMinus2] = $this->distances($Y, $Aplus, $Aminus, $cols, $skipCols);
                $this->storeStep($a, 'DISTANCES', [
                    'rows' => $rows,
                    'S_plus' => $Splus,
                    'S_minus' => $Sminus,
                    // simpan ringkasan kontribusi top-3 per alternatif (hemat payload)
                    'top_contrib' => $this->topContrib($cols, $dPlus2, $dMinus2, 3),
                ]);

                // 7) CC & Ranking
                $CC = $this->closeness($Splus, $Sminus); // assoc rowName => cc
                arsort($CC); // desc
                $ranking = array_keys($CC);

                $this->storeStep($a, 'CLOSENESS_COEFF', ['rows' => $rows, 'CC' => $CC]);
                $this->storeStep($a, 'RANKING', ['ranking' => $ranking, 'CC' => $CC]);

                $a->update([
                    'status' => 'done',
                    'n_alternatives' => count($rows),
                    'n_criteria' => count($cols),
                ]);
            });
        } catch (\Exception $e) {
            // Log error and mark assessment as failed
            \Log::error('TOPSIS calculation failed', [
                'assessment_id' => $a->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Update assessment status to failed
            $a->update(['status' => 'failed']);

            // Re-throw exception for controller to handle
            throw $e;
        }
    }

    /** Bangun matriks keputusan X (m×n) */
    private function buildDecisionMatrix(Assessment $a, $alts, $criteria): array
    {
        // Cache jawaban user (USER-source) → value_numeric per criterion_id
        $answers = AssessmentAnswer::where('assessment_id', $a->id)->get()->keyBy('criterion_id');

        $rows = [];   // nama alternatif (mis: "GnX - Jalur Y")
        $X = [];      // m×n
        $meta = ['impute' => []];

        foreach ($alts as $alt) {
            $route = $alt->route;
            $mount = $route->mountain;
            $rowName = ($mount?->name ?? 'Gunung ?') . ' — ' . $route->name;
            $rows[] = $rowName;

            $rowVals = [];
            foreach ($criteria as $c) {
                if ($c->source === 'USER') {
                    // Ambil value_numeric user untuk C1..C14
                    $ans = $answers->get($c->id);
                    $val = $ans?->value_numeric;
                    $rowVals[] = is_numeric($val) ? (float)$val : null;
                } elseif ($c->source === 'MOUNTAIN') {
                    $rowVals[] = $this->valueFromMountain($c->code, $mount);
                } else { // ROUTE
                    $rowVals[] = $this->valueFromRoute($c->code, $route);
                }
            }
            $X[] = $rowVals;
        }

        // Imputasi nilai hilang per kolom (median); catat meta
        $X = $this->imputeMedianPerColumn($X, $criteria->pluck('code')->all(), $meta);

        return [$rows, $X, $meta];
    }

    /** Ambil nilai dari Mountain utk C15 (elevation mdpl) dst */
    private function valueFromMountain(string $code, $mountain): ?float
    {
        if (!$mountain) return null;
        return match ($code) {
            'C15' => is_numeric($mountain->elevation_m) ? (float)$mountain->elevation_m : null,
            default => null,
        };
    }

    /** Ambil nilai dari Route utk C16..C21 (termasuk kategori C17) */
    private function valueFromRoute(string $code, $route): ?float
    {
        if (!$route) return null;
        return match ($code) {
            'C16' => $this->normalizeRouteValue($route->elevation_gain_m, 'C16'),
            'C17' => $this->mapLandCoverKey(17, $route->land_cover_key), // 0..1 cost-score dari category_maps
            'C18' => $this->normalizeRouteValue($route->distance_km, 'C18'),
            'C19' => $this->normalizeRouteValue($route->slope_class ?? $route->slope_deg, 'C19'),
            'C20' => $this->normalizeRouteValue($route->water_sources_score, 'C20'),
            'C21' => $this->normalizeRouteValue($route->support_facility_score, 'C21'),
            default => null,
        };
    }


    /** Normalisasi nilai route berdasarkan range global */
    private function normalizeRouteValue($value, string $code): ?float
    {
        if (!is_numeric($value)) return null;

        // Range global untuk normalisasi min-max
        $ranges = [
            'C16' => [200, 2200],    // elevation_gain_m
            'C18' => [4.2, 16.2],    // distance_km
            'C19' => [1, 5],         // slope_class
            'C20' => [4, 8],         // water_sources_score
            'C21' => [3, 9],         // support_facility_score
        ];

        if (!isset($ranges[$code])) return (float)$value;

        [$min, $max] = $ranges[$code];
        if ($max <= $min) return 0.0;

        // Normalisasi ke 0..1
        return max(0, min(1, ($value - $min) / ($max - $min)));
    }

    /** Imputasi median kolom untuk null; jika semua null → kolom ditandai skip */
    private function imputeMedianPerColumn(array $X, array $cols, array &$meta): array
    {
        $m = count($X);
        if ($m === 0) return $X;
        $n = count($cols);
        $skip = [];
        for ($j = 0; $j < $n; $j++) {
            $col = [];
            for ($i = 0; $i < $m; $i++) {
                $col[] = $X[$i][$j];
            }
            $nonNull = array_values(array_filter($col, fn($v) => is_numeric($v)));
            if (count($nonNull) === 0) {
                $skip[$cols[$j]] = true;
                continue;
            }
            sort($nonNull);
            $mid = intdiv(count($nonNull), 2);
            $median = (count($nonNull) % 2 === 1) ? $nonNull[$mid] : (($nonNull[$mid - 1] + $nonNull[$mid]) / 2);
            for ($i = 0; $i < $m; $i++) {
                if (!is_numeric($X[$i][$j])) $X[$i][$j] = $median;
            }
            $meta['impute'][$cols[$j]] = ['median' => $median];
        }
        if (!empty($skip)) $meta['skip_all_zero_cols'] = array_keys($skip);
        return $X;
    }

    /** Normalisasi vektor per kolom; kolom yang semua 0 → ditandai skip */
    private function normalizeR(array $X): array
    {
        $m = count($X);
        $n = $m ? count($X[0]) : 0;
        $denoms = array_fill(0, $n, 0.0);
        for ($j = 0; $j < $n; $j++) {
            $sum2 = 0.0;
            for ($i = 0; $i < $m; $i++) $sum2 += ($X[$i][$j] ?? 0.0) ** 2;
            $denoms[$j] = sqrt($sum2);
        }
        $R = [];
        $skipCols = [];
        for ($i = 0; $i < $m; $i++) {
            $row = [];
            for ($j = 0; $j < $n; $j++) {
                if ($denoms[$j] == 0.0) {
                    $row[] = 0.0;
                    $skipCols[$j] = true;
                } else {
                    $row[] = ($X[$i][$j] ?? 0.0) / $denoms[$j];
                }
            }
            $R[] = $row;
        }
        return [$R, $denoms, array_keys($skipCols)];
    }

    /** Resolve weights: Use global criterion weights only (traditional approach) */
    public function resolveWeights(Assessment $a, array $cols): array
    {
        $W = [];

        // Use global criterion weights (traditional approach)
        $rows = DB::table('criteria')
            ->leftJoin('criterion_weights', 'criteria.id', '=', 'criterion_weights.criterion_id')
            ->whereIn('criteria.code', $cols)
            ->select('criteria.code', 'criterion_weights.weight')
            ->get();

        foreach ($rows as $r) {
            $W[$r->code] = (float)($r->weight ?? 0.0);
        }

        // normalize to sum=1
        $sum = array_sum($W);
        if ($sum > 0) {
            foreach ($W as $k => $v) $W[$k] = $v / $sum;
        }

        return $W;
    }

    /** Terapkan bobot: Y = W * R; kolom yang skip → tetap 0 */
    private function applyWeights(array $R, array $cols, array $W, array $skipCols): array
    {
        $Y = [];
        $m = count($R);
        $n = $m ? count($R[0]) : 0;
        for ($i = 0; $i < $m; $i++) {
            $row = [];
            for ($j = 0; $j < $n; $j++) {
                $w = $W[$cols[$j]] ?? 0.0;
                $row[] = in_array($j, $skipCols, true) ? 0.0 : ($R[$i][$j] * $w);
            }
            $Y[] = $row;
        }
        return $Y;
    }

    /** A+ / A- sesuai benefit/cost; kolom skip di-set 0 */
    private function idealSolutions(array $Y, array $cols, array $types, array $skipCols): array
    {
        $m = count($Y);
        $n = $m ? count($Y[0]) : 0;
        $Aplus = array_fill(0, $n, 0.0);
        $Aminus = array_fill(0, $n, 0.0);

        for ($j = 0; $j < $n; $j++) {
            if (in_array($j, $skipCols, true)) {
                $Aplus[$j] = 0.0;
                $Aminus[$j] = 0.0;
                continue;
            }
            $col = array_column($Y, $j);
            if (($types[$cols[$j]] ?? 'benefit') === 'benefit') {
                $Aplus[$j] = max($col);
                $Aminus[$j] = min($col);
            } else { // cost
                $Aplus[$j] = min($col);
                $Aminus[$j] = max($col);
            }
        }
        return [$Aplus, $Aminus];
    }

    /** Hitung jarak S+ dan S-; kembalikan juga kontribusi per kriteria (kuadrat selisih) */
    private function distances(array $Y, array $Aplus, array $Aminus, array $cols, array $skipCols): array
    {
        $m = count($Y);
        $n = $m ? count($Y[0]) : 0;
        $Splus = array_fill(0, $m, 0.0);
        $Sminus = array_fill(0, $m, 0.0);
        $dPlus2 = [];
        $dMinus2 = []; // per i: [j => (Δ)^2]

        for ($i = 0; $i < $m; $i++) {
            $sumP = 0.0;
            $sumM = 0.0;
            $dP = [];
            $dM = [];
            for ($j = 0; $j < $n; $j++) {
                if (in_array($j, $skipCols, true)) {
                    $dP[$j] = 0.0;
                    $dM[$j] = 0.0;
                    continue;
                }
                $dp = ($Y[$i][$j] - $Aplus[$j]);
                $dm = ($Y[$i][$j] - $Aminus[$j]);
                $dp2 = $dp * $dp;
                $dm2 = $dm * $dm;
                $sumP += $dp2;
                $sumM += $dm2;
                $dP[$j] = $dp2;
                $dM[$j] = $dm2;
            }
            $Splus[$i] = sqrt($sumP);
            $Sminus[$i] = sqrt($sumM);
            $dPlus2[$i] = $dP;
            $dMinus2[$i] = $dM;
        }
        return [$Splus, $Sminus, $dPlus2, $dMinus2];
    }

    /** Ambil top-k kontributor (besar) untuk pro/kontra per alternatif */
    private function topContrib(array $cols, array $dPlus2, array $dMinus2, int $k = 3): array
    {
        $res = [];
        foreach ($dPlus2 as $i => $rowDp2) {
            // pro = kriteria dengan dMinus2 besar (mendekat ke A- berarti menjauh dari buruk → kontribusi positif relatif)
            // kontra = kriteria dengan dPlus2 besar (menjauh dari A+)
            $pro = $this->topKAssoc($row = $dMinus2[$i], $cols, $k);
            $con = $this->topKAssoc($rowDp2, $cols, $k);
            $res[$i] = ['pro' => $pro, 'con' => $con];
        }
        return $res;
    }
    private function topKAssoc(array $arr, array $cols, int $k): array
    {
        $tmp = [];
        foreach ($arr as $j => $v) $tmp[$cols[$j]] = $v;
        arsort($tmp);
        return array_slice($tmp, 0, $k, true);
    }

    /** CC = S- / (S+ + S-) */
    private function closeness(array $Splus, array $Sminus): array
    {
        $CC = [];
        for ($i = 0; $i < count($Splus); $i++) {
            $den = $Splus[$i] + $Sminus[$i];
            $CC[$i] = $den > 0 ? ($Sminus[$i] / $den) : 0.0;
        }
        // rebinding index ke nama baris dilakukan di UI; di sini tetap numeric index
        return $CC;
    }

    private function buildCompatibilityColumns(array $rows, array $X, array $cols, Assessment $a): array
    {
        // ambil indeks kolom rute yang dibutuhkan
        $idx = array_flip($cols);
        $jGain   = $idx['C16'] ?? null;
        $jDist   = $idx['C18'] ?? null;
        $jLand   = $idx['C17'] ?? null; // sudah 0..1 cost score
        $jSlope  = $idx['C19'] ?? null;
        $jSup    = $idx['C21'] ?? null;

        // nilai user (0..1)
        $ans = $a->answers()->with('criterion')->get()->keyBy(fn($x) => $x->criterion->code);
        $C2 = (float)($ans['C2']->value_numeric  ?? 0);   // fitness
        $C3 = (float)($ans['C3']->value_numeric  ?? 0);   // cardio risk (cost)
        $C8 = (float)($ans['C8']->value_numeric  ?? 0);   // experience
        $C11 = (float)($ans['C11']->value_numeric ?? 0);   // survival
        $C12 = (float)($ans['C12']->value_numeric ?? 0);
        $C13 = (float)($ans['C13']->value_numeric ?? 0);
        $C14 = (float)($ans['C14']->value_numeric ?? 0);
        $team = ($C12 + $C14) / 2;

        // helper minmax
        $minmax = function (?int $j) use ($X) {
            if ($j === null) return [0, 1];
            $vals = array_column($X, $j);
            $min = min($vals);
            $max = max($vals);
            if ($max <= $min) $max = $min + 1e-9;
            return [$min, $max];
        };
        [$gMin, $gMax] = $minmax($jGain);
        [$dMin, $dMax] = $minmax($jDist);
        [$sMin, $sMax] = $minmax($jSlope);
        [$uMin, $uMax] = $minmax($jSup);

        // build columns
        $COMP = [
            'COMP_Fitness_vs_Gain'     => [],
            'COMP_Exp_vs_Distance'     => [],
            'COMP_Survival_vs_LandCover' => [],
            'COMP_Team_vs_Support'     => [],
            'COMP_Cardio_vs_Gain'      => [],
            'COMP_Guide_vs_Slope'      => [],
        ];

        foreach ($X as $i => $row) {
            $gain = $jGain !== null ? ($row[$jGain] - $gMin) / ($gMax - $gMin) : 0;
            $dist = $jDist !== null ? ($row[$jDist] - $dMin) / ($dMax - $dMin) : 0;
            $land = $jLand !== null ? max(0, min(1, $row[$jLand])) : 0; // sudah 0..1 cost
            $slope = $jSlope !== null ? ($row[$jSlope] - $sMin) / ($sMax - $sMin) : 0;
            $supp = $jSup !== null ? ($row[$jSup] - $uMin) / ($uMax - $uMin) : 0;

            $COMP['COMP_Fitness_vs_Gain'][]      = 1 - abs($gain - $C2);
            $COMP['COMP_Exp_vs_Distance'][]      = 1 - abs($dist - $C8);
            $COMP['COMP_Survival_vs_LandCover'][] = 1 - abs($land - $C11);
            $COMP['COMP_Team_vs_Support'][]      = 1 - abs($supp - $team);
            $COMP['COMP_Cardio_vs_Gain'][]       = 1 - min(1.0, $C3 * $gain);
            $COMP['COMP_Guide_vs_Slope'][]       = 1 - max(0.0, $slope - 0.3 * $C13);
        }

        // append ke X dan cols (semua benefit)
        foreach ($COMP as $code => $colVals) {
            $cols[] = $code;
            foreach ($X as $i => $_) $X[$i][] = max(0, min(1, $colVals[$i]));
        }

        return [$cols, $X];
    }

    /** Build decision matrix X murni tanpa imputasi */
    private function buildXPure($alts, $criteria): array
    {
        $rows = [];
        $X = [];
        $missing = [];

        // Get user answers for USER criteria
        $assessment = $alts->first()?->assessment;
        $userAnswers = [];
        if ($assessment) {
            $userAnswers = AssessmentAnswer::where('assessment_id', $assessment->id)
                ->get()
                ->keyBy('criterion_id');
        }

        // Initialize FuzzyService if fuzzy processing is enabled
        $fuzzyService = null;
        if (config('spk.fuzzy.enabled', false)) {
            $fuzzyService = new FuzzyService();
        }

        foreach ($alts as $idx => $alt) {
            $r = $alt->route;
            $m = $r->mountain;
            $rows[] = ($m?->name ?? 'Gunung ?') . ' — ' . $r->name;

            $row = [];
            $hasNull = false;

            foreach ($criteria as $c) {
                $val = null;

                if ($c->source === 'USER') {
                    // Get user answer for USER criteria (C1-C14)
                    $answer = $userAnswers->get($c->id);
                    $rawValue = $answer?->value_numeric;

                    if (is_numeric($rawValue)) {
                        // Check if fuzzy processing is enabled for this criterion
                        if ($fuzzyService && $c->is_fuzzy) {
                            // Apply fuzzy processing
                            $fuzzyResult = $fuzzyService->fuzzifyAndDefuzzify((float)$rawValue, $c->id);
                            $val = $fuzzyResult['defuzzified_value'];

                            // Store fuzzy processing metadata in assessment step for later viewing
                            $this->storeFuzzyStep($assessment, $c, $rawValue, $fuzzyResult);
                        } else {
                            // Use crisp value directly
                            $val = (float)$rawValue;
                        }
                    }
                } elseif ($c->source === 'MOUNTAIN') {
                    $val = is_numeric($m?->elevation_m) ? (float)$m->elevation_m : null; // C15
                } else { // ROUTE - Apply normalization to ensure consistent scale
                    $val = match ($c->code) {
                        'C16' => $this->normalizeRouteValue($r->elevation_gain_m, 'C16'),
                        'C17' => $this->mapLandCoverKey($c->id, $r->land_cover_key),
                        'C18' => $this->normalizeRouteValue($r->distance_km, 'C18'),
                        'C19' => $this->normalizeRouteValue($r->slope_class ?? $r->slope_deg, 'C19'),
                        'C20' => $this->normalizeRouteValue($r->water_sources_score, 'C20'),
                        'C21' => $this->normalizeRouteValue($r->support_facility_score, 'C21'),
                        default => null
                    };
                }

                if (!is_numeric($val)) {
                    $hasNull = true;
                }
                $row[] = $val;
            }

            if ($hasNull) {
                $missing[] = $idx; // tandai alternatif ini
            }
            $X[] = $row;
        }

        return [$rows, $X, $missing];
    }

    /** Drop rows with missing data */
    private function dropRowsWithMissing(array $rows, array $X, array $missing): array
    {
        $keep = array_diff(array_keys($rows), $missing);
        $rows2 = [];
        $X2 = [];

        foreach ($keep as $i) {
            $rows2[] = $rows[$i];
            $X2[] = $X[$i];
        }

        return [$rows2, $X2];
    }

    /** Map land cover key to numeric score */
    private function mapLandCoverKey(int $criterionId, ?string $key): ?float
    {
        if (!$key) return null;

        $score = DB::table('category_maps')
            ->where('criterion_id', $criterionId)
            ->where('key', $key)
            ->value('score');

        return is_numeric($score) ? (float)$score : null;
    }

    /** Check if compatibility columns should be used */
    private function shouldUseCompatibilityColumns(Assessment $a): bool
    {
        // In pure mode, never use compatibility columns
        if (config('spk.pure_formula')) {
            return false;
        }

        // Check if assessment has weights_json that includes COMP columns
        $weights = $a->weights_json ?? [];

        // If no weights specified, use default behavior (no COMP columns for pure TOPSIS)
        if (empty($weights)) {
            return false;
        }

        // Check if any COMP columns are present in weights
        $compColumns = [
            'COMP_Fitness_vs_Gain',
            'COMP_Exp_vs_Distance',
            'COMP_Survival_vs_LandCover',
            'COMP_Team_vs_Support',
            'COMP_Cardio_vs_Gain',
            'COMP_Guide_vs_Slope'
        ];

        foreach ($compColumns as $compCol) {
            if (isset($weights[$compCol]) && $weights[$compCol] > 0) {
                return true;
            }
        }

        return false;
    }

    /** Simpan step ke DB */
    private function storeStep(Assessment $a, string $step, array $payload): void
    {
        AssessmentStep::create([
            'assessment_id' => $a->id,
            'step' => $step,
            'payload' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE),
        ]);
    }

    /** Store fuzzy processing step for later viewing */
    private function storeFuzzyStep(Assessment $assessment, Criterion $criterion, $rawValue, array $fuzzyResult): void
    {
        if (!$assessment) return;

        $payload = [
            'criterion_code' => $criterion->code,
            'criterion_name' => $criterion->name,
            'raw_input' => $rawValue,
            'defuzzified_value' => $fuzzyResult['defuzzified_value'],
            'memberships' => $fuzzyResult['memberships'],
            'terms' => $fuzzyResult['terms']
        ];

        AssessmentStep::create([
            'assessment_id' => $assessment->id,
            'step' => 'FUZZY_PROCESSING',
            'payload' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE),
        ]);
    }

    /**
     * Dry run TOPSIS without saving to database
     */
    public function dryRun(Assessment $a, array $overrideWeights = []): array
    {
        try {
            // Get alternatives
            $alts = $a->alternatives()
                ->with(['route.mountain'])
                ->where('is_excluded', false)
                ->get();

            if ($alts->isEmpty()) {
                return ['ranking' => [], 'CC' => [], 'rows' => []];
            }

            // Get criteria
            $criteria = Criterion::where('active', 1)
                ->when($a->pure_formula, fn($q) => $q->whereIn('source', ['MOUNTAIN', 'ROUTE']))
                ->orderBy('sort_order')
                ->orderByRaw("CAST(SUBSTRING(code, 2) AS UNSIGNED)")
                ->get();

            $cols = $criteria->pluck('code')->values()->all();
            $types = $criteria->pluck('type', 'code')->toArray();

            // Build X matrix
            [$rows, $X, $missing] = $this->buildXPure($alts, $criteria);

            // Drop rows with missing data if strict mode
            if (config('spk.strict_missing') && !empty($missing)) {
                [$rows, $X] = $this->dropRowsWithMissing($rows, $X, $missing);
            }

            if (empty($X) || empty($X[0])) {
                return ['ranking' => [], 'CC' => [], 'rows' => []];
            }

            // Get weights (use associative array format for consistency)
            $W = [];
            if (!empty($overrideWeights)) {
                foreach ($cols as $code) {
                    $W[$code] = (float)($overrideWeights[$code] ?? 0);
                }
                // Normalize weights
                $sum = array_sum($W);
                if ($sum > 0) {
                    foreach ($W as $k => $v) {
                        $W[$k] = $v / $sum;
                    }
                }
            } else {
                $W = $this->resolveWeights($a, $cols);
            }

            $m = count($X);
            $n = count($X[0]);

            if ($m === 0 || $n === 0 || count($W) !== $n) {
                return ['ranking' => [], 'CC' => [], 'rows' => [], 'cols' => $cols, 'weights' => $W];
            }

            // Simple TOPSIS calculation
            // Normalize matrix (vector normalization)
            $R = [];
            for ($i = 0; $i < $m; $i++) {
                $R[$i] = [];
                for ($j = 0; $j < $n; $j++) {
                    $sum = 0;
                    for ($k = 0; $k < $m; $k++) {
                        $sum += $X[$k][$j] * $X[$k][$j];
                    }
                    $R[$i][$j] = ($sum > 0) ? ($X[$i][$j] / sqrt($sum)) : 0;
                }
            }

            // Weighted matrix (use associative array indexing)
            $Y = [];
            for ($i = 0; $i < $m; $i++) {
                $Y[$i] = [];
                for ($j = 0; $j < $n; $j++) {
                    $w = $W[$cols[$j]] ?? 0.0;
                    $Y[$i][$j] = $R[$i][$j] * $w;
                }
            }

            // Ideal solutions
            $Aplus = [];
            $Aminus = [];
            for ($j = 0; $j < $n; $j++) {
                $colValues = array_column($Y, $j);
                if ($types[$cols[$j]] === 'benefit') {
                    $Aplus[$j] = max($colValues);
                    $Aminus[$j] = min($colValues);
                } else {
                    $Aplus[$j] = min($colValues);
                    $Aminus[$j] = max($colValues);
                }
            }

            // Distances
            $Splus = [];
            $Sminus = [];
            for ($i = 0; $i < $m; $i++) {
                $sumPlus = 0;
                $sumMinus = 0;
                for ($j = 0; $j < $n; $j++) {
                    $sumPlus += pow($Y[$i][$j] - $Aplus[$j], 2);
                    $sumMinus += pow($Y[$i][$j] - $Aminus[$j], 2);
                }
                $Splus[$i] = sqrt($sumPlus);
                $Sminus[$i] = sqrt($sumMinus);
            }

            // Closeness coefficient
            $CC = [];
            for ($i = 0; $i < $m; $i++) {
                $denominator = $Splus[$i] + $Sminus[$i];
                $CC[$i] = $denominator > 0 ? $Sminus[$i] / $denominator : 0;
            }

            // Ranking
            arsort($CC);
            $ranking = array_keys($CC);

            return [
                'ranking' => $ranking,
                'CC' => $CC,
                'rows' => $rows,
                'cols' => $cols,
                'weights' => $W  // Already in associative array format
            ];
        } catch (\Exception $e) {
            return [
                'ranking' => [],
                'CC' => [],
                'rows' => [],
                'cols' => [],
                'weights' => [],
                'error' => $e->getMessage()
            ];
        }
    }
}
