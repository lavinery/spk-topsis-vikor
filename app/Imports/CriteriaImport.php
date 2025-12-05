<?php

namespace App\Imports;

use App\Models\Criterion;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CriteriaImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();

        Criterion::updateOrCreate(
            ['code' => $r['code']],
            [
                'name' => $r['name'],
                'source' => $r['source'] ?? 'user',
                'type' => $r['type'] ?? 'benefit',
                'scale' => $r['scale'] ?? 'numeric',
                'data_type' => $r['data_type'] ?? 'number',
                'unit' => $r['unit'] ?? null,
                'active' => filter_var($r['active'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'is_fuzzy' => filter_var($r['is_fuzzy'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ]
        );
    }
}
