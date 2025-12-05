<?php

namespace App\Imports;

use App\Models\Criterion;
use App\Models\FuzzyTerm;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FuzzyTermsImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();

        $criterion = Criterion::where('code', $r['criterion_code'])->first();

        if ($criterion) {
            FuzzyTerm::updateOrCreate(
                [
                    'criterion_id' => $criterion->id,
                    'code' => $r['code']
                ],
                [
                    'label' => $r['label'] ?? null,
                    'shape' => $r['shape'] ?? 'triangular',
                    'params_json' => is_array($r['params_json']) ? $r['params_json'] : json_decode($r['params_json'], true),
                ]
            );
        }
    }
}
