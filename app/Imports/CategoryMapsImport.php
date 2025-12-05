<?php

namespace App\Imports;

use App\Models\Criterion;
use App\Models\CategoryMap;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CategoryMapsImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();

        $criterion = Criterion::where('code', $r['criterion_code'])->first();

        if ($criterion) {
            CategoryMap::updateOrCreate(
                [
                    'criterion_id' => $criterion->id,
                    'key' => $r['key']
                ],
                [
                    'score' => $r['score'],
                    'label' => $r['label'] ?? null,
                ]
            );
        }
    }
}
