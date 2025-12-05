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

        // C5 Motivasi
        $c5 = $crit['C5'] ?? null;
        if ($c5) {
            DB::table('category_maps')->upsert([
                ['criterion_id' => $c5, 'key' => 'rekreasi', 'score' => 0.8],
                ['criterion_id' => $c5, 'key' => 'latihan', 'score' => 0.9],
                ['criterion_id' => $c5, 'key' => 'ekspedisi', 'score' => 1.0],
            ], ['criterion_id', 'key'], ['score']);
        }

        // C14 Tutupan Lahan
        $c14 = $crit['C14'] ?? null;
        if ($c14) {
            DB::table('category_maps')->upsert([
                ['criterion_id' => $c14, 'key' => 'hutan-lebat', 'score' => 1.0],   // cost tinggi
                ['criterion_id' => $c14, 'key' => 'campuran', 'score' => 0.6],
                ['criterion_id' => $c14, 'key' => 'savana', 'score' => 0.3],
                ['criterion_id' => $c14, 'key' => 'terbuka', 'score' => 0.2],
            ], ['criterion_id', 'key'], ['score']);
        }
    }
}
