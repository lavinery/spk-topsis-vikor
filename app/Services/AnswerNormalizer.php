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
                // Use legacy transforms for non-fuzzy criteria
                $num = match($c->code) {
                    'C2','C4','C5','C6','C9','C10','C11','C12','C14'
                         => UserTransforms::ord1_5((int)$val),                    // benefit 0..1
                    'C7' => UserTransforms::motivation($this->maps->getScore($c->id, (string)$val)), // benefit
                    'C8' => UserTransforms::experience((int)$val),                // benefit 0..1
                    'C13'=> UserTransforms::guide((bool)$val),                    // benefit 0..1
                    default => is_numeric($val) ? (float)$val : null
                };

                // Generic boolean support
                if ($num === null && ($c->data_type ?? '') === 'boolean') {
                    $num = filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    $num = is_null($num) ? null : ($num ? 1.0 : 0.0);
                }
            }

            $ans->value_numeric = $num;
            $ans->save();
        }
    }
}
