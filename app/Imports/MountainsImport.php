<?php

namespace App\Imports;

use App\Models\Mountain;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MountainsImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();

        Mountain::updateOrCreate(
            ['name' => $r['name']],
            [
                'elevation_m' => $r['elevation_m'] ?? null,
                'province' => $r['province'] ?? null,
                'lat' => $r['lat'] ?? null,
                'lng' => $r['lng'] ?? null,
                'status' => $r['status'] ?? 'active',
            ]
        );
    }
}
