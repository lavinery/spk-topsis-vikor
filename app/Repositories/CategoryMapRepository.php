<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CategoryMapRepository
{
    protected array $cache = []; // [criterion_id][key] => score

    public function getScore(int $criterionId, string $key): ?float
    {
        if (isset($this->cache[$criterionId][$key])) {
            return $this->cache[$criterionId][$key];
        }

        $score = DB::table('category_maps')
            ->where('criterion_id', $criterionId)
            ->where('key', $key)
            ->value('score');

        if ($score !== null) {
            $this->cache[$criterionId][$key] = (float)$score;
        }

        return $score ? (float)$score : null;
    }
}
