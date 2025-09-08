<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentStep;
use App\Models\Criterion;
use Illuminate\Support\Facades\DB;

final class VikorService
{
    /** Jalankan VIKOR full pipeline dan simpan setiap tahap */
    public function run(Assessment $a, float $v = null): void
    {
        if ($v === null) {
            $v = config('spk.vikor.default_v', 0.5);
        }
        
        DB::transaction(function () use ($a, $v) {
            // status
            $a->update(['status' => 'running']);

            // 0) Ambil daftar alternatif (exclude yang di-flag)
            $alts = $a->alternatives()
                ->with(['route.mountain'])
                ->where('is_excluded', false)
                ->get();

            if ($alts->isEmpty()) {
                $this->storeStep($a, 'VIKOR_RANKING', ['ranking' => [], 'Q' => []]);
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
            $types = $criteria->pluck('type','code')->toArray();             // ["C15"=>"cost", ...]

            // 2) Build X murni tanpa imputasi
            [$rows, $X, $missing] = $this->buildXPure($alts, $criteria);
            
            // 3) Keluarkan alternatif yang missing (strict)
            if (config('spk.strict_missing') && !empty($missing)) {
                [$rows, $X] = $this->dropRowsWithMissing($rows, $X, $missing);
                $this->storeStep($a, 'VIKOR_VALIDATION_MISSING', [
                    'algorithm' => 'VIKOR',
                    'dropped' => $missing,
                    'dropped_count' => count($missing)
                ]);
            }

            $this->storeStep($a, 'VIKOR_MATRIX_X', [
                'rows' => $rows, 'cols' => $cols, 'X' => $X, 'algorithm' => 'VIKOR',
            ]);

            // 4) BEST/WORST murni
            [$Fstar, $Fminus] = $this->bestWorst($X, $cols, $types);
            $this->storeStep($a, 'VIKOR_BEST_WORST', [
                'cols' => $cols, 'F_star' => $Fstar, 'F_minus' => $Fminus, 'types' => $types,
            ]);

            // 5) Bobot murni (sum=1)
            $W = $this->resolveWeightsPure($cols, $a);
            $this->storeStep($a, 'VIKOR_WEIGHTS', [
                'cols' => $cols, 'weights' => $W,
            ]);

            // 6) S & R (regret rata-rata & maksimum) — rumus baku
            [$S, $R, $G, $denoms] = $this->calcSR($X, $Fstar, $Fminus, $W);
            $this->storeStep($a, 'VIKOR_S_R', [
                'rows' => $rows, 'S' => $S, 'R' => $R, 'G' => $G, 'denoms' => $denoms, 'weights' => $W,
            ]);

            // 7) Q (v 0..1)
            if (empty($S) || empty($R)) {
                $this->storeStep($a, 'VIKOR_RANKING', ['ranking' => [], 'Q' => []]);
                $a->update(['status' => 'done', 'n_alternatives' => 0, 'n_criteria' => count($cols)]);
                return;
            }
            
            $Smin = min($S); $Smax = max($S); $Rmin = min($R); $Rmax = max($R);
            $Q = [];
            foreach ($S as $i => $Si) {
                $termS = ($Smax > $Smin) ? ($Si - $Smin) / ($Smax - $Smin) : 0;
                $termR = ($Rmax > $Rmin) ? ($R[$i] - $Rmin) / ($Rmax - $Rmin) : 0;
                $Q[$i] = $v * $termS + (1 - $v) * $termR;
            }
            asort($Q); $ranking = array_keys($Q);

            $this->storeStep($a, 'VIKOR_Q', [
                'rows' => $rows, 'Q' => $Q, 'v' => $v, 'Smin' => $Smin, 'Smax' => $Smax, 'Rmin' => $Rmin, 'Rmax' => $Rmax,
            ]);
            $this->storeStep($a, 'VIKOR_RANKING', [
                'ranking' => $ranking, 'Q' => $Q, 'v' => $v,
            ]);

            $a->update([
                'status' => 'done',
                'n_alternatives' => count($rows),
                'n_criteria' => count($cols),
            ]);
        });
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
            $userAnswers = \App\Models\AssessmentAnswer::where('assessment_id', $assessment->id)
                ->get()
                ->keyBy('criterion_id');
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
                    $val = $answer?->value_numeric;
                } elseif ($c->source === 'MOUNTAIN') {
                    $val = is_numeric($m?->elevation_m) ? (float)$m->elevation_m : null; // C15
                } else { // ROUTE
                    $val = match($c->code) {
                        'C16' => is_numeric($r->elevation_gain_m) ? (float)$r->elevation_gain_m : null,
                        'C17' => $this->mapLandCoverKey($c->id, $r->land_cover_key),
                        'C18' => is_numeric($r->distance_km) ? (float)$r->distance_km : null,
                        'C19' => is_numeric($r->slope_class) ? (float)$r->slope_class
                                : (is_numeric($r->slope_deg) ? (float)$r->slope_deg : null),
                        'C20' => is_numeric($r->water_sources_score) ? (float)$r->water_sources_score : null,
                        'C21' => is_numeric($r->support_facility_score) ? (float)$r->support_facility_score : null,
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

    /** Find best and worst values per criterion */
    private function bestWorst(array $X, array $cols, array $types): array
    {
        $m = count($X);
        $n = $m ? count($X[0]) : 0;
        $Fstar = array_fill(0, $n, 0.0);
        $Fminus = array_fill(0, $n, 0.0);

        for ($j = 0; $j < $n; $j++) {
            $col = array_column($X, $j);
            $col = array_filter($col, 'is_numeric'); // Remove null values
            
            if (empty($col)) {
                $Fstar[$j] = 0.0;
                $Fminus[$j] = 0.0;
                continue;
            }
            
            if (($types[$cols[$j]] ?? 'benefit') === 'benefit') {
                $Fstar[$j] = max($col);
                $Fminus[$j] = min($col);
            } else { // cost
                $Fstar[$j] = min($col);
                $Fminus[$j] = max($col);
            }
        }
        
        return [$Fstar, $Fminus];
    }

    /** Resolve weights - using global criterion weights only (traditional approach) */
    private function resolveWeightsPure(array $cols, Assessment $a): array
    {
        $W = [];
        
        // Use global criterion weights (traditional approach)
        $rows = DB::table('criteria')
            ->leftJoin('criterion_weights', 'criteria.id', '=', 'criterion_weights.criterion_id')
            ->whereIn('criteria.code', $cols)
            ->select('criteria.code', 'criterion_weights.weight')
            ->orderByRaw("CAST(SUBSTRING(code, 2) AS UNSIGNED)")
            ->get();
            
        foreach ($rows as $r) {
            $W[] = (float)($r->weight ?? 0);
        }
        
        // Normalize to sum=1
        $sum = array_sum($W);
        if ($sum > 0) {
            foreach ($W as &$w) {
                $w /= $sum;
            }
        }
        
        return $W; // index by j (numerical index)
    }

    /** Calculate S (average regret) and R (maximum regret) */
    private function calcSR(array $X, array $Fstar, array $Fminus, array $W): array
    {
        $m = count($X);
        $n = $m ? count($X[0]) : 0;
        $S = [];
        $R = [];
        $G = [];
        $denoms = [];

        for ($i = 0; $i < $m; $i++) {
            $Si = 0.0;
            $Ri = 0.0;
            $Gi = [];

            for ($j = 0; $j < $n; $j++) {
                if (!is_numeric($X[$i][$j])) continue;
                
                $denom = $Fstar[$j] - $Fminus[$j];
                if (abs($denom) < 1e-9) {
                    // For criteria where all alternatives have the same value (like USER criteria)
                    // Skip this criterion in VIKOR calculation
                    continue;
                }
                $denoms[$j] = $denom;
                
                $gij = ($Fstar[$j] - $X[$i][$j]) / $denom;
                $Gi[$j] = $gij;
                
                $Si += ($W[$j] ?? 0) * $gij;
                $Ri = max($Ri, $gij);
            }
            
            $S[$i] = $Si;
            $R[$i] = $Ri;
            $G[$i] = $Gi;
        }

        return [$S, $R, $G, $denoms];
    }

    /** Simpan step ke DB */
    private function storeStep(Assessment $a, string $step, array $payload): void
    {
        AssessmentStep::create([
            'assessment_id' => $a->id,
            'step' => $step,
            'payload' => json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_IGNORE),
        ]);
    }

    /**
     * Dry run VIKOR without saving to database
     */
    public function dryRun(Assessment $a, array $overrideWeights = [], float $v = 0.5): array
    {
        // Get alternatives
        $alts = $a->alternatives()
            ->with(['route.mountain'])
            ->where('is_excluded', false)
            ->get();

        if ($alts->isEmpty()) {
            return ['ranking' => [], 'Q' => [], 'rows' => []];
        }

        // Get criteria
        $criteria = Criterion::where('active', 1)
            ->when($a->pure_formula, fn($q) => $q->whereIn('source', ['MOUNTAIN', 'ROUTE']))
            ->orderBy('sort_order')
            ->orderByRaw("CAST(SUBSTRING(code, 2) AS UNSIGNED)")
            ->get();

        $cols = $criteria->pluck('code')->values()->all();
        $types = $criteria->pluck('type','code')->toArray();

        // Build X matrix
        [$rows, $X, $missing] = $this->buildXPure($alts, $criteria);
        
        // Drop rows with missing data if strict mode
        if (config('spk.strict_missing') && !empty($missing)) {
            [$rows, $X] = $this->dropRowsWithMissing($rows, $X, $missing);
        }

        if (empty($X)) {
            return ['ranking' => [], 'Q' => [], 'rows' => []];
        }

        // Use override weights if provided, otherwise use normal weights
        if (!empty($overrideWeights)) {
            $W = [];
            foreach ($cols as $code) {
                $W[] = (float)($overrideWeights[$code] ?? 0);
            }
            // Normalize weights
            $sum = array_sum($W);
            if ($sum > 0) {
                foreach ($W as &$w) {
                    $w /= $sum;
                }
            }
        } else {
            $W = $this->resolveWeightsPure($cols, $a);
        }

        // Best and worst values
        [$Fstar, $Fminus] = $this->bestWorst($X, $cols, $types);

        // Calculate S and R
        [$S, $R, $G, $denoms] = $this->calcSR($X, $Fstar, $Fminus, $W);

        if (empty($S) || empty($R)) {
            return ['ranking' => [], 'Q' => [], 'rows' => []];
        }

        // Calculate Q
        $Smin = min($S); $Smax = max($S); $Rmin = min($R); $Rmax = max($R);
        $Q = [];
        foreach ($S as $i => $Si) {
            $termS = ($Smax > $Smin) ? ($Si - $Smin) / ($Smax - $Smin) : 0;
            $termR = ($Rmax > $Rmin) ? ($R[$i] - $Rmin) / ($Rmax - $Rmin) : 0;
            $Q[$i] = $v * $termS + (1 - $v) * $termR;
        }

        // Ranking
        asort($Q);
        $ranking = array_keys($Q);

        return [
            'ranking' => $ranking,
            'Q' => $Q,
            'rows' => $rows,
            'cols' => $cols,
            'weights' => count($cols) === count($W) ? array_combine($cols, $W) : [],
            'S' => $S,
            'R' => $R
        ];
    }
}
