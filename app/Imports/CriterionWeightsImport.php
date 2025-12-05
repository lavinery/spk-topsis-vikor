<?php

namespace App\Imports;

use App\Models\Criterion;
use App\Models\CriterionWeight;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CriterionWeightsImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();

        $criterion = Criterion::where('code', $r['criterion_code'])->first();

        if ($criterion) {
            CriterionWeight::updateOrCreate(
                [
                    'criterion_id' => $criterion->id,
                    'version' => $r['version'] ?? 'v1'
                ],
                [
                    'weight' => $r['weight'],
                ]
            );
        }
    }
}
