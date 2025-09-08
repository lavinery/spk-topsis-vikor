<?php

namespace App\Imports;

use App\Models\Mountain;
use App\Models\Route as RouteModel;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RoutesImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();
        
        // Create or find mountain
        $m = Mountain::firstOrCreate(
            ['name' => $r['mountain_name']],
            [
                'elevation_m' => $r['elevation_m'] ?? null,
                'province' => $r['province'] ?? null,
                'status' => 'open'
            ]
        );
        
        // Create or update route
        RouteModel::updateOrCreate(
            ['mountain_id' => $m->id, 'name' => $r['route_name']],
            [
                'distance_km' => $r['distance_km'] ?? null,
                'elevation_gain_m' => $r['elevation_gain_m'] ?? null,
                'slope_class' => $r['slope_class'] ?? null,
                'land_cover_key' => $r['land_cover_key'] ?? null,
                'water_sources_score' => $r['water_sources_score'] ?? null,
                'support_facility_score' => $r['support_facility_score'] ?? null,
                'permit_required' => (bool)($r['permit_required'] ?? 0),
            ]
        );
    }
}