<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FullDemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('routes')->delete();
        DB::table('mountains')->delete();
        
        // Insert mountains with full data
        $mountains = [
            ['name' => 'Gn. Contoh', 'elevation_m' => 3200, 'province' => 'Jawa Tengah', 'status' => 'open'],
            ['name' => 'Gunung Semeru', 'elevation_m' => 3676, 'province' => 'Jawa Timur', 'status' => 'open'],
            ['name' => 'Gunung Rinjani', 'elevation_m' => 3726, 'province' => 'Nusa Tenggara Barat', 'status' => 'open'],
            ['name' => 'Gunung Kerinci', 'elevation_m' => 3805, 'province' => 'Jambi', 'status' => 'open'],
            ['name' => 'Gunung Merapi', 'elevation_m' => 2930, 'province' => 'Jawa Tengah', 'status' => 'open'],
            ['name' => 'Gunung Bromo', 'elevation_m' => 2329, 'province' => 'Jawa Timur', 'status' => 'open'],
            ['name' => 'Semeru', 'elevation_m' => 3676, 'province' => 'Jawa Timur', 'status' => 'open'],
            ['name' => 'Rinjani', 'elevation_m' => 3726, 'province' => 'Nusa Tenggara Barat', 'status' => 'open'],
            ['name' => 'Gede', 'elevation_m' => 2958, 'province' => 'Jawa Barat', 'status' => 'open'],
            ['name' => 'Pangrango', 'elevation_m' => 3019, 'province' => 'Jawa Barat', 'status' => 'open'],
            ['name' => 'Slamet', 'elevation_m' => 3432, 'province' => 'Jawa Tengah', 'status' => 'open'],
            ['name' => 'Merbabu', 'elevation_m' => 3145, 'province' => 'Jawa Tengah', 'status' => 'open'],
            ['name' => 'Sindoro', 'elevation_m' => 3150, 'province' => 'Jawa Tengah', 'status' => 'open'],
            ['name' => 'Sumbing', 'elevation_m' => 3371, 'province' => 'Jawa Tengah', 'status' => 'open'],
            ['name' => 'Lawu', 'elevation_m' => 3265, 'province' => 'Jawa Tengah', 'status' => 'open'],
            ['name' => 'Kerinci', 'elevation_m' => 3805, 'province' => 'Jambi', 'status' => 'open'],
        ];
        
        $mountainIds = [];
        foreach ($mountains as $mountain) {
            $id = DB::table('mountains')->insertGetId($mountain);
            $mountainIds[$mountain['name']] = $id;
        }
        
        // Insert routes with full data
        $routes = [
            // Gn. Contoh
            ['mountain' => 'Gn. Contoh', 'name' => 'Jalur Utara', 'distance_km' => 11.5, 'elevation_gain_m' => 1200, 'slope_deg' => 25.5, 'slope_class' => 3, 'land_cover_key' => 'campuran', 'water_sources_score' => 6, 'support_facility_score' => 7, 'permit_required' => true],
            ['mountain' => 'Gn. Contoh', 'name' => 'Jalur Timur', 'distance_km' => 9.8, 'elevation_gain_m' => 900, 'slope_deg' => 18.2, 'slope_class' => 2, 'land_cover_key' => 'savana', 'water_sources_score' => 7, 'support_facility_score' => 6, 'permit_required' => true],
            
            // Gunung Semeru
            ['mountain' => 'Gunung Semeru', 'name' => 'Jalur Ranu Kumbolo', 'distance_km' => 15.2, 'elevation_gain_m' => 1500, 'slope_deg' => 28.5, 'slope_class' => 4, 'land_cover_key' => 'hutan', 'water_sources_score' => 8, 'support_facility_score' => 7, 'permit_required' => true],
            ['mountain' => 'Gunung Semeru', 'name' => 'Jalur Ranu Pane', 'distance_km' => 12.8, 'elevation_gain_m' => 1200, 'slope_deg' => 22.1, 'slope_class' => 3, 'land_cover_key' => 'campuran', 'water_sources_score' => 7, 'support_facility_score' => 8, 'permit_required' => true],
            
            // Gunung Rinjani
            ['mountain' => 'Gunung Rinjani', 'name' => 'Jalur Sembalun', 'distance_km' => 18.5, 'elevation_gain_m' => 1800, 'slope_deg' => 32.1, 'slope_class' => 5, 'land_cover_key' => 'savana', 'water_sources_score' => 6, 'support_facility_score' => 5, 'permit_required' => true],
            ['mountain' => 'Gunung Rinjani', 'name' => 'Jalur Senaru', 'distance_km' => 16.2, 'elevation_gain_m' => 1600, 'slope_deg' => 29.8, 'slope_class' => 4, 'land_cover_key' => 'hutan', 'water_sources_score' => 8, 'support_facility_score' => 6, 'permit_required' => true],
            
            // Gunung Kerinci
            ['mountain' => 'Gunung Kerinci', 'name' => 'Jalur Kersik Tuo', 'distance_km' => 14.5, 'elevation_gain_m' => 1400, 'slope_deg' => 26.3, 'slope_class' => 4, 'land_cover_key' => 'hutan', 'water_sources_score' => 7, 'support_facility_score' => 6, 'permit_required' => true],
            ['mountain' => 'Gunung Kerinci', 'name' => 'Jalur Sungai Penuh', 'distance_km' => 13.2, 'elevation_gain_m' => 1300, 'slope_deg' => 24.7, 'slope_class' => 3, 'land_cover_key' => 'campuran', 'water_sources_score' => 8, 'support_facility_score' => 7, 'permit_required' => true],
            
            // Gunung Merapi
            ['mountain' => 'Gunung Merapi', 'name' => 'Jalur Selo', 'distance_km' => 8.5, 'elevation_gain_m' => 800, 'slope_deg' => 20.1, 'slope_class' => 2, 'land_cover_key' => 'savana', 'water_sources_score' => 5, 'support_facility_score' => 8, 'permit_required' => false],
            ['mountain' => 'Gunung Merapi', 'name' => 'Jalur Kinahrejo', 'distance_km' => 7.2, 'elevation_gain_m' => 700, 'slope_deg' => 18.5, 'slope_class' => 2, 'land_cover_key' => 'campuran', 'water_sources_score' => 6, 'support_facility_score' => 7, 'permit_required' => false],
            
            // Gunung Bromo
            ['mountain' => 'Gunung Bromo', 'name' => 'Jalur Cemoro Lawang', 'distance_km' => 5.8, 'elevation_gain_m' => 500, 'slope_deg' => 15.2, 'slope_class' => 1, 'land_cover_key' => 'savana', 'water_sources_score' => 4, 'support_facility_score' => 9, 'permit_required' => true],
            ['mountain' => 'Gunung Bromo', 'name' => 'Jalur Ngadisari', 'distance_km' => 6.5, 'elevation_gain_m' => 550, 'slope_deg' => 16.8, 'slope_class' => 1, 'land_cover_key' => 'savana', 'water_sources_score' => 5, 'support_facility_score' => 8, 'permit_required' => true],
            
            // Semeru
            ['mountain' => 'Semeru', 'name' => 'Jalur Ranu Pane', 'distance_km' => 12.8, 'elevation_gain_m' => 1200, 'slope_deg' => 22.1, 'slope_class' => 3, 'land_cover_key' => 'campuran', 'water_sources_score' => 7, 'support_facility_score' => 8, 'permit_required' => true],
            ['mountain' => 'Semeru', 'name' => 'Jalur Senduro', 'distance_km' => 11.2, 'elevation_gain_m' => 1100, 'slope_deg' => 20.5, 'slope_class' => 3, 'land_cover_key' => 'hutan', 'water_sources_score' => 8, 'support_facility_score' => 7, 'permit_required' => true],
            
            // Rinjani
            ['mountain' => 'Rinjani', 'name' => 'Jalur Sembalun', 'distance_km' => 18.5, 'elevation_gain_m' => 1800, 'slope_deg' => 32.1, 'slope_class' => 5, 'land_cover_key' => 'savana', 'water_sources_score' => 6, 'support_facility_score' => 5, 'permit_required' => true],
            ['mountain' => 'Rinjani', 'name' => 'Jalur Senaru', 'distance_km' => 16.2, 'elevation_gain_m' => 1600, 'slope_deg' => 29.8, 'slope_class' => 4, 'land_cover_key' => 'hutan', 'water_sources_score' => 8, 'support_facility_score' => 6, 'permit_required' => true],
            
            // Gede
            ['mountain' => 'Gede', 'name' => 'Jalur Cibodas', 'distance_km' => 9.5, 'elevation_gain_m' => 900, 'slope_deg' => 19.8, 'slope_class' => 2, 'land_cover_key' => 'hutan', 'water_sources_score' => 8, 'support_facility_score' => 8, 'permit_required' => true],
            ['mountain' => 'Gede', 'name' => 'Jalur Gunung Putri', 'distance_km' => 8.2, 'elevation_gain_m' => 800, 'slope_deg' => 17.5, 'slope_class' => 2, 'land_cover_key' => 'campuran', 'water_sources_score' => 7, 'support_facility_score' => 7, 'permit_required' => true],
            
            // Pangrango
            ['mountain' => 'Pangrango', 'name' => 'Jalur Cibodas', 'distance_km' => 10.2, 'elevation_gain_m' => 950, 'slope_deg' => 21.2, 'slope_class' => 3, 'land_cover_key' => 'hutan', 'water_sources_score' => 8, 'support_facility_score' => 7, 'permit_required' => true],
            ['mountain' => 'Pangrango', 'name' => 'Jalur Selabintana', 'distance_km' => 9.8, 'elevation_gain_m' => 900, 'slope_deg' => 20.1, 'slope_class' => 2, 'land_cover_key' => 'campuran', 'water_sources_score' => 7, 'support_facility_score' => 8, 'permit_required' => true],
            
            // Slamet
            ['mountain' => 'Slamet', 'name' => 'Jalur Bambangan', 'distance_km' => 12.5, 'elevation_gain_m' => 1200, 'slope_deg' => 24.5, 'slope_class' => 3, 'land_cover_key' => 'hutan', 'water_sources_score' => 7, 'support_facility_score' => 6, 'permit_required' => true],
            ['mountain' => 'Slamet', 'name' => 'Jalur Guci', 'distance_km' => 11.8, 'elevation_gain_m' => 1150, 'slope_deg' => 23.2, 'slope_class' => 3, 'land_cover_key' => 'campuran', 'water_sources_score' => 8, 'support_facility_score' => 7, 'permit_required' => true],
            
            // Merbabu
            ['mountain' => 'Merbabu', 'name' => 'Jalur Selo', 'distance_km' => 8.8, 'elevation_gain_m' => 850, 'slope_deg' => 19.5, 'slope_class' => 2, 'land_cover_key' => 'savana', 'water_sources_score' => 6, 'support_facility_score' => 7, 'permit_required' => false],
            ['mountain' => 'Merbabu', 'name' => 'Jalur Wekas', 'distance_km' => 7.5, 'elevation_gain_m' => 750, 'slope_deg' => 17.8, 'slope_class' => 2, 'land_cover_key' => 'campuran', 'water_sources_score' => 7, 'support_facility_score' => 8, 'permit_required' => false],
            
            // Sindoro
            ['mountain' => 'Sindoro', 'name' => 'Jalur Kledung', 'distance_km' => 9.2, 'elevation_gain_m' => 900, 'slope_deg' => 20.5, 'slope_class' => 2, 'land_cover_key' => 'hutan', 'water_sources_score' => 7, 'support_facility_score' => 7, 'permit_required' => true],
            ['mountain' => 'Sindoro', 'name' => 'Jalur Tambi', 'distance_km' => 8.8, 'elevation_gain_m' => 850, 'slope_deg' => 19.2, 'slope_class' => 2, 'land_cover_key' => 'campuran', 'water_sources_score' => 8, 'support_facility_score' => 8, 'permit_required' => true],
            
            // Sumbing
            ['mountain' => 'Sumbing', 'name' => 'Jalur Garung', 'distance_km' => 10.5, 'elevation_gain_m' => 1000, 'slope_deg' => 22.1, 'slope_class' => 3, 'land_cover_key' => 'hutan', 'water_sources_score' => 7, 'support_facility_score' => 6, 'permit_required' => true],
            ['mountain' => 'Sumbing', 'name' => 'Jalur Butuh', 'distance_km' => 9.8, 'elevation_gain_m' => 950, 'slope_deg' => 21.5, 'slope_class' => 3, 'land_cover_key' => 'campuran', 'water_sources_score' => 8, 'support_facility_score' => 7, 'permit_required' => true],
            
            // Lawu
            ['mountain' => 'Lawu', 'name' => 'Jalur Cemoro Sewu', 'distance_km' => 7.5, 'elevation_gain_m' => 750, 'slope_deg' => 18.2, 'slope_class' => 2, 'land_cover_key' => 'savana', 'water_sources_score' => 6, 'support_facility_score' => 8, 'permit_required' => true],
            ['mountain' => 'Lawu', 'name' => 'Jalur Cemoro Kandang', 'distance_km' => 8.2, 'elevation_gain_m' => 800, 'slope_deg' => 19.5, 'slope_class' => 2, 'land_cover_key' => 'campuran', 'water_sources_score' => 7, 'support_facility_score' => 7, 'permit_required' => true],
            
            // Kerinci
            ['mountain' => 'Kerinci', 'name' => 'Jalur Kersik Tuo', 'distance_km' => 14.5, 'elevation_gain_m' => 1400, 'slope_deg' => 26.3, 'slope_class' => 4, 'land_cover_key' => 'hutan', 'water_sources_score' => 7, 'support_facility_score' => 6, 'permit_required' => true],
            ['mountain' => 'Kerinci', 'name' => 'Jalur Sungai Penuh', 'distance_km' => 13.2, 'elevation_gain_m' => 1300, 'slope_deg' => 24.7, 'slope_class' => 3, 'land_cover_key' => 'campuran', 'water_sources_score' => 8, 'support_facility_score' => 7, 'permit_required' => true],
        ];
        
        foreach ($routes as $route) {
            DB::table('routes')->insert([
                'mountain_id' => $mountainIds[$route['mountain']],
                'name' => $route['name'],
                'distance_km' => $route['distance_km'],
                'elevation_gain_m' => $route['elevation_gain_m'],
                'slope_deg' => $route['slope_deg'],
                'slope_class' => $route['slope_class'],
                'land_cover_key' => $route['land_cover_key'],
                'water_sources_score' => $route['water_sources_score'],
                'support_facility_score' => $route['support_facility_score'],
                'permit_required' => $route['permit_required'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        echo "✅ Full demo data seeded successfully!\n";
        echo "   - Mountains: " . count($mountains) . "\n";
        echo "   - Routes: " . count($routes) . "\n";
    }
}
