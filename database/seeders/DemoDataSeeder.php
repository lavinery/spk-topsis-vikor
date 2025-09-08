<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gid = DB::table('mountains')->insertGetId([
            'name' => 'Gn. Contoh',
            'elevation_m' => 3200,
            'province' => 'Jawa Tengah',
            'status' => 'open'
        ]);
        
        $rid1 = DB::table('routes')->insertGetId([
            'mountain_id' => $gid,
            'name' => 'Jalur Utara',
            'distance_km' => 11.5,
            'elevation_gain_m' => 1200,
            'slope_class' => 3,
            'land_cover_key' => 'campuran',
            'water_sources_score' => 6,
            'support_facility_score' => 7,
            'permit_required' => true
        ]);
        
        $rid2 = DB::table('routes')->insertGetId([
            'mountain_id' => $gid,
            'name' => 'Jalur Timur',
            'distance_km' => 9.8,
            'elevation_gain_m' => 900,
            'slope_class' => 2,
            'land_cover_key' => 'savana',
            'water_sources_score' => 7,
            'support_facility_score' => 6,
            'permit_required' => true
        ]);
    }
}
