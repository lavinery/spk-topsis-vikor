<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentStep;
use App\Models\Criterion;
use App\Repositories\CategoryMapRepository;
use App\Support\UserTransforms;
use Illuminate\Support\Facades\DB;

class AnswerNormalizer
{
    public function __construct(
        protected CategoryMapRepository $maps,
        protected FuzzyService $fuzzy
    ) {
    }

    public function normalize(Assessment $a): void
    {
        // Ambil semua jawaban raw + definisi kriteria
        $answers = $a->answers()->get()->keyBy('criterion_id');
        $criteria = Criterion::whereIn('id', $answers->keys())->get()->keyBy('id');

        // Check which criteria have fuzzy terms
        $fuzzyEnabledCriteria = DB::table('fuzzy_terms')
            ->select('criterion_id')
            ->whereIn('criterion_id', $answers->keys())
            ->groupBy('criterion_id')
            ->having(DB::raw('COUNT(*)'), '>', 0)
            ->pluck('criterion_id')
            ->toArray();

        foreach ($answers as $ans) {
            $c = $criteria[$ans->criterion_id];
            $val = $ans->value_raw;

            // Use fuzzy processing if criterion marked is_fuzzy OR has fuzzy terms defined
            if ((($c->is_fuzzy ?? false) || in_array($c->id, $fuzzyEnabledCriteria)) && is_numeric($val)) {
                $fuzzyResult = $this->fuzzy->fuzzifyAndDefuzzify((float)$val, $c->id);

                // Store fuzzy processing details
                AssessmentStep::create([
                    'assessment_id' => $a->id,
                    'step' => 'FUZZY_PROCESSING',
                    'payload' => [
                        'criterion_id' => $c->id,
                        'criterion_code' => $c->code,
                        'criterion_name' => $c->name,
                        'raw_input' => (float)$val,
                        'defuzzified_value' => $fuzzyResult['defuzzified_value'],
                        'memberships' => $fuzzyResult['memberships'],
                        'terms' => $fuzzyResult['terms']
                    ]
                ]);

                $num = $fuzzyResult['defuzzified_value'];
            } else {
                // Dynamic transformation based on criterion metadata (no hardcoded codes!)
                $num = $this->transformValue($c, $val);
            }

            $ans->value_numeric = $num;
            $ans->save();
        }
    }

    /**
     * Transform value dynamically based on criterion metadata
     * NO HARDCODED CRITERION CODES - fully flexible for CRUD operations
     */
    private function transformValue(Criterion $criterion, $value): ?float
    {
        // Handle null/empty values
        if ($value === null || $value === '') {
            return null;
        }

        // 1. BOOLEAN type - convert to 0/1
        if ($criterion->data_type === 'boolean') {
            $bool = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            return is_null($bool) ? null : ($bool ? 1.0 : 0.0);
        }

        // 2. CATEGORICAL type - use category_maps
        if ($criterion->data_type === 'categorical' || $criterion->scale === 'categorical') {
            $score = $this->maps->getScore($criterion->id, (string)$value);
            return is_numeric($score) ? (float)$score : null;
        }

        // 3. ORDINAL type - normalize to 0..1 scale
        if ($criterion->data_type === 'ordinal') {
            if (!is_numeric($value)) {
                return null;
            }
            return UserTransforms::ord1_5((int)$value);
        }

        // 4. NUMERIC type - use as-is or normalize based on hints
        if ($criterion->data_type === 'numeric' && is_numeric($value)) {
            $numValue = (float)$value;

            // If min_hint and max_hint are defined, normalize to 0..1
            if ($criterion->min_hint !== null && $criterion->max_hint !== null) {
                $min = (float)$criterion->min_hint;
                $max = (float)$criterion->max_hint;
                if ($max > $min) {
                    return max(0, min(1, ($numValue - $min) / ($max - $min)));
                }
            }

            // Otherwise use as-is
            return $numValue;
        }

        // Fallback: try to parse as number
        return is_numeric($value) ? (float)$value : null;
    }
}
