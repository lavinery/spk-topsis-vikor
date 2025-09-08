<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Criterion;
use App\Repositories\CategoryMapRepository;
use App\Support\UserTransforms;

class AnswerNormalizer
{
    public function __construct(protected CategoryMapRepository $maps)
    {
    }

    public function normalize(Assessment $a): void
    {
        // Ambil semua jawaban raw + definisi kriteria
        $answers = $a->answers()->get()->keyBy('criterion_id');
        $criteria = Criterion::whereIn('id', $answers->keys())->get()->keyBy('id');

        foreach ($answers as $ans) {
            $c = $criteria[$ans->criterion_id];
            $val = $ans->value_raw;

            $num = match($c->code) {
                'C1' => UserTransforms::age((int)$val),                       // cost 0..1
                'C2','C4','C5','C6','C9','C10','C11','C12','C14'
                     => UserTransforms::ord1_5((int)$val),                    // benefit 0..1
                'C3' => UserTransforms::cardio((string)$val),                 // cost 0..1
                'C7' => UserTransforms::motivation($this->maps->getScore($c->id, (string)$val)), // benefit
                'C8' => UserTransforms::experience((int)$val),                // benefit 0..1
                'C13'=> UserTransforms::guide((bool)$val),                    // benefit 0..1
                default => is_numeric($val) ? (float)$val : null
            };

            $ans->value_numeric = $num;
            $ans->save();
        }
    }
}
