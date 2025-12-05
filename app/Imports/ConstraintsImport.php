<?php

namespace App\Imports;

use App\Models\Constraint;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ConstraintsImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();

        Constraint::updateOrCreate(
            ['name' => $r['name']],
            [
                'action' => $r['action'] ?? 'exclude_alternative',
                'expr_json' => is_array($r['expr_json']) ? $r['expr_json'] : json_decode($r['expr_json'], true),
                'active' => filter_var($r['active'] ?? true, FILTER_VALIDATE_BOOLEAN),
            ]
        );
    }
}
