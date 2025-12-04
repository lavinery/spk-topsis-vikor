<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestMountainsSeeder extends Seeder
{
    /**
     * Run the database seeds - Data realistis gunung-gunung populer di Indonesia
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Data gunung populer di Indonesia dengan elevasi yang akurat
        $mountains = [
            // Nama, Elevasi(mdpl), Provinsi, Lat, Lng, Status
            ['Semeru', 3676, 'Jawa Timur', -8.108, 112.922, 'open'],
            ['Rinjani', 3726, 'Nusa Tenggara Barat', -8.411, 116.457, 'open'],
            ['Gede', 2958, 'Jawa Barat', -6.782, 106.983, 'open'],
            ['Merbabu', 3145, 'Jawa Tengah', -7.454, 110.440, 'open'],
            ['Slamet', 3432, 'Jawa Tengah', -7.242, 109.208, 'open'],
            ['Lawu', 3265, 'Jawa Tengah/Jawa Timur', -7.625, 111.192, 'open'],
            ['Kerinci', 3805, 'Jambi', -1.696, 101.264, 'open'],
            ['Prau', 2565, 'Jawa Tengah', -7.186, 109.924, 'open'],
            ['Papandayan', 2665, 'Jawa Barat', -7.319, 107.730, 'open'],
            ['Bromo', 2329, 'Jawa Timur', -7.942, 112.953, 'open'],
        ];

        /**
         * Data jalur pendakian yang realistis
         * Format: [Gunung, Nama Jalur, Jarak(km), Elevation Gain(m), Slope Class(1-5), Land Cover, Water Score(0-10), Support Score(0-10), Permit]
         *
         * Slope Class: 1=Landai, 2=Sedang, 3=Curam, 4=Sangat Curam, 5=Ekstrim
         * Land Cover: 'terbuka', 'savana', 'campuran', 'hutan-lebat'
         * Water Score: 0=Tidak ada, 10=Sangat banyak
         * Support Score: 0=Tidak ada, 10=Lengkap
         */
        $routes = [
            // SEMERU - Gunung tertinggi Jawa
            ['Semeru', 'Jalur Ranu Pane', 12.5, 1800, 4, 'campuran', 6, 7, true],
            ['Semeru', 'Jalur Burno', 15.0, 2000, 5, 'hutan-lebat', 5, 4, true],

            // RINJANI - Gunung ikonik NTB
            ['Rinjani', 'Jalur Sembalun', 16.2, 2200, 4, 'savana', 7, 8, true],
            ['Rinjani', 'Jalur Senaru', 14.8, 2100, 4, 'hutan-lebat', 8, 7, true],
            ['Rinjani', 'Jalur Timbanuh', 18.5, 2300, 5, 'campuran', 6, 5, true],

            // GEDE - Gunung klasik Jawa Barat
            ['Gede', 'Jalur Cibodas', 8.2, 1200, 3, 'hutan-lebat', 8, 9, true],
            ['Gede', 'Jalur Gunung Putri', 6.8, 1000, 2, 'campuran', 7, 8, true],

            // MERBABU - Gunung savana Jawa Tengah
            ['Merbabu', 'Jalur Selo', 9.5, 1400, 3, 'savana', 7, 8, true],
            ['Merbabu', 'Jalur Wekas', 10.2, 1500, 3, 'savana', 6, 7, true],
            ['Merbabu', 'Jalur Thekelan', 11.5, 1600, 4, 'campuran', 5, 6, true],

            // SLAMET - Gunung tertinggi Jawa Tengah
            ['Slamet', 'Jalur Bambangan', 11.8, 1700, 4, 'hutan-lebat', 7, 7, true],
            ['Slamet', 'Jalur Guci', 13.5, 1900, 4, 'campuran', 6, 6, true],

            // LAWU - Gunung mistis Jawa
            ['Lawu', 'Jalur Cemoro Sewu', 8.5, 1400, 3, 'hutan-lebat', 8, 8, true],
            ['Lawu', 'Jalur Cemoro Kandang', 9.8, 1500, 3, 'campuran', 7, 7, true],
            ['Lawu', 'Jalur Candi Cetho', 7.2, 1200, 2, 'campuran', 7, 9, true],

            // KERINCI - Gunung tertinggi Sumatera
            ['Kerinci', 'Jalur Kersik Tuo', 16.0, 2000, 4, 'hutan-lebat', 6, 5, true],
            ['Kerinci', 'Jalur Sungai Penuh', 18.2, 2200, 5, 'hutan-lebat', 5, 4, true],

            // PRAU - Gunung sunrise populer
            ['Prau', 'Jalur Igirmranak', 4.5, 600, 2, 'savana', 6, 8, false],
            ['Prau', 'Jalur Patak Banteng', 5.2, 700, 2, 'savana', 5, 7, false],
            ['Prau', 'Jalur Dieng', 6.0, 800, 3, 'campuran', 6, 8, false],

            // PAPANDAYAN - Gunung aktif dengan kawah
            ['Papandayan', 'Jalur Cisurupan', 4.8, 500, 2, 'savana', 7, 9, false],
            ['Papandayan', 'Jalur Pengalengan', 5.5, 600, 2, 'campuran', 6, 8, false],

            // BROMO - Gunung ikonik sunrise
            ['Bromo', 'Jalur Cemoro Lawang', 4.2, 300, 1, 'terbuka', 4, 9, false],
            ['Bromo', 'Jalur Jemplang', 5.0, 400, 2, 'savana', 5, 7, false],
        ];

        // Delete existing data with FK handling
        $this->command->warn('⚠️  Deleting existing mountains and routes data...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('routes')->truncate();
        DB::table('mountains')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert mountains
        $mountainIds = [];
        foreach ($mountains as [$name, $elevation, $province, $lat, $lng, $status]) {
            $id = DB::table('mountains')->insertGetId([
                'name' => $name,
                'elevation_m' => $elevation,
                'province' => $province,
                'lat' => $lat,
                'lng' => $lng,
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

        $this->command->info('✅ Berhasil menambahkan ' . count($mountains) . ' gunung dan ' . count($routes) . ' jalur pendakian');
    }
}
