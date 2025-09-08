<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestMountainsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Data gunung dan jalur untuk testing TOPSIS
        $mountains = [
            ['Semeru', 3676, 'Jawa Timur', 'open'],
            ['Rinjani', 3726, 'Nusa Tenggara Barat', 'open'],
            ['Gede', 2958, 'Jawa Barat', 'open'],
            ['Pangrango', 3019, 'Jawa Barat', 'open'],
            ['Slamet', 3432, 'Jawa Tengah', 'open'],
            ['Merbabu', 3145, 'Jawa Tengah', 'open'],
            ['Sindoro', 3151, 'Jawa Tengah', 'open'],
            ['Sumbing', 3371, 'Jawa Tengah', 'open'],
            ['Lawu', 3265, 'Jawa Tengah', 'open'],
            ['Kerinci', 3805, 'Jambi', 'open'],
        ];

        $routes = [
            // Semeru
            ['Semeru', 'Jalur Ranu Pane', 12.5, 1800, 4, 'hutan', 5, 6, true],
            ['Semeru', 'Jalur Senduro', 14.2, 2000, 4, 'hutan', 4, 5, true],
            
            // Rinjani
            ['Rinjani', 'Jalur Sembalun', 15.8, 2200, 5, 'savana', 6, 7, true],
            ['Rinjani', 'Jalur Senaru', 18.5, 2500, 5, 'hutan', 5, 6, true],
            
            // Gede
            ['Gede', 'Jalur Cibodas', 8.5, 1200, 3, 'hutan', 7, 8, true],
            ['Gede', 'Jalur Gunung Putri', 7.2, 1000, 2, 'hutan', 8, 9, true],
            
            // Pangrango
            ['Pangrango', 'Jalur Cibodas', 6.8, 800, 2, 'hutan', 8, 9, true],
            ['Pangrango', 'Jalur Selabintana', 8.2, 950, 3, 'hutan', 7, 8, true],
            
            // Slamet
            ['Slamet', 'Jalur Bambangan', 11.5, 1500, 4, 'hutan', 6, 7, true],
            ['Slamet', 'Jalur Guci', 13.2, 1700, 4, 'hutan', 5, 6, true],
            
            // Merbabu
            ['Merbabu', 'Jalur Selo', 9.8, 1300, 3, 'savana', 7, 8, true],
            ['Merbabu', 'Jalur Wekas', 11.2, 1400, 3, 'savana', 6, 7, true],
            
            // Sindoro
            ['Sindoro', 'Jalur Kledung', 10.5, 1400, 3, 'hutan', 6, 7, true],
            ['Sindoro', 'Jalur Tambi', 12.8, 1600, 4, 'hutan', 5, 6, true],
            
            // Sumbing
            ['Sumbing', 'Jalur Garung', 11.2, 1500, 3, 'hutan', 6, 7, true],
            ['Sumbing', 'Jalur Butuh', 13.5, 1700, 4, 'hutan', 5, 6, true],
            
            // Lawu
            ['Lawu', 'Jalur Cemoro Sewu', 8.8, 1200, 3, 'hutan', 7, 8, true],
            ['Lawu', 'Jalur Cemoro Kandang', 10.2, 1300, 3, 'hutan', 6, 7, true],
            
            // Kerinci
            ['Kerinci', 'Jalur Kersik Tuo', 16.5, 2000, 4, 'hutan', 4, 5, true],
            ['Kerinci', 'Jalur Sungai Penuh', 18.2, 2200, 5, 'hutan', 3, 4, true],
        ];

        // Insert mountains
        $mountainIds = [];
        foreach ($mountains as [$name, $elevation, $province, $status]) {
            $id = DB::table('mountains')->insertGetId([
                'name' => $name,
                'elevation_m' => $elevation,
                'province' => $province,
                'status' => $status,
                'created_at' => $now,
                'updated_at' => $now
            ]);
            $mountainIds[$name] = $id;
        }

        // Insert routes
        foreach ($routes as [$mountainName, $routeName, $distance, $elevationGain, $slopeClass, $landCover, $waterSources, $supportFacility, $permitRequired]) {
            DB::table('routes')->insert([
                'mountain_id' => $mountainIds[$mountainName],
                'name' => $routeName,
                'distance_km' => $distance,
                'elevation_gain_m' => $elevationGain,
                'slope_class' => $slopeClass,
                'land_cover_key' => $landCover,
                'water_sources_score' => $waterSources,
                'support_facility_score' => $supportFacility,
                'permit_required' => $permitRequired,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }
    }
}
