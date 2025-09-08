<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryMapsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $crit = DB::table('criteria')->pluck('id', 'code');
        
        // C7 Motivasi
        $c7 = $crit['C7'] ?? null;
        if ($c7) {
            DB::table('category_maps')->upsert([
                ['criterion_id' => $c7, 'key' => 'rekreasi', 'score' => 0.8],
                ['criterion_id' => $c7, 'key' => 'latihan', 'score' => 0.9],
                ['criterion_id' => $c7, 'key' => 'ekspedisi', 'score' => 1.0],
            ], ['criterion_id', 'key'], ['score']);
        }
        
        // C17 Tutupan Lahan
        $c17 = $crit['C17'] ?? null;
        if ($c17) {
            DB::table('category_maps')->upsert([
                ['criterion_id' => $c17, 'key' => 'hutan-lebat', 'score' => 1.0],   // cost tinggi
                ['criterion_id' => $c17, 'key' => 'campuran', 'score' => 0.6],
                ['criterion_id' => $c17, 'key' => 'savana', 'score' => 0.3],
                ['criterion_id' => $c17, 'key' => 'terbuka', 'score' => 0.2],
            ], ['criterion_id', 'key'], ['score']);
        }
    }
}
