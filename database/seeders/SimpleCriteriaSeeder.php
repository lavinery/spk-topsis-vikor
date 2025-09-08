<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SimpleCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Clear existing data (respect foreign key constraints)
        DB::table('criterion_weights')->delete();
        DB::table('criteria')->delete();
        
        // Simple criteria with equal weights
        $criteria = [
            // USER criteria (C1-C14) - 14 criteria, each gets 1/21 weight
            ['C1', 'Usia', 'cost', 'USER', 'numeric'],
            ['C2', 'Physical Fitness', 'benefit', 'USER', 'ordinal'],
            ['C3', 'Riwayat Kardiovaskular', 'cost', 'USER', 'ordinal'],
            ['C4', 'Kepercayaan Diri', 'benefit', 'USER', 'ordinal'],
            ['C5', 'Kepemilikan Peralatan', 'benefit', 'USER', 'ordinal'],
            ['C6', 'Pengetahuan P3K', 'benefit', 'USER', 'ordinal'],
            ['C7', 'Motivasi Pendakian', 'benefit', 'USER', 'categorical'],
            ['C8', 'Pengalaman Pendakian', 'benefit', 'USER', 'numeric'],
            ['C9', 'Perencanaan Logistik', 'benefit', 'USER', 'ordinal'],
            ['C10', 'Skill Penggunaan Alat', 'benefit', 'USER', 'ordinal'],
            ['C11', 'Kemampuan Survival', 'benefit', 'USER', 'ordinal'],
            ['C12', 'Kesiapan Tim', 'benefit', 'USER', 'ordinal'],
            ['C13', 'Kehadiran Pemandu', 'benefit', 'USER', 'ordinal'],
            ['C14', 'Pembagian Tugas Tim', 'benefit', 'USER', 'ordinal'],
            
            // MOUNTAIN/ROUTE criteria (C15-C21) - 7 criteria, each gets 1/21 weight
            ['C15', 'Ketinggian (mdpl)', 'cost', 'MOUNTAIN', 'numeric'],
            ['C16', 'Elevasi/Gain (m)', 'cost', 'ROUTE', 'numeric'],
            ['C17', 'Tutupan Lahan', 'cost', 'ROUTE', 'categorical'],
            ['C18', 'Panjang Jalur (km)', 'cost', 'ROUTE', 'numeric'],
            ['C19', 'Kecuraman', 'cost', 'ROUTE', 'numeric'],
            ['C20', 'Sumber Air', 'benefit', 'ROUTE', 'ordinal'],
            ['C21', 'Sarana Pendukung', 'benefit', 'ROUTE', 'ordinal'],
        ];
        
        // Insert criteria
        $criteriaRows = [];
        foreach ($criteria as $index => [$code, $name, $type, $source, $dataType]) {
            $criteriaRows[] = [
                'code' => $code,
                'name' => $name,
                'type' => $type,
                'source' => $source,
                'data_type' => $dataType,
                'unit' => null,
                'active' => 1,
                'sort_order' => $index + 1,
                'version' => 'v1',
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        
        DB::table('criteria')->insert($criteriaRows);
        
        // Get criteria IDs
        $criteriaIds = DB::table('criteria')->pluck('id', 'code');
        
        // Set equal weights for all criteria (total = 1)
        $totalCriteria = count($criteria);
        $equalWeight = 1.0 / $totalCriteria; // Each criterion gets 1/21 weight
        
        $weightRows = [];
        foreach ($criteriaIds as $code => $id) {
            $weightRows[] = [
                'criterion_id' => $id,
                'weight' => $equalWeight,
                'version' => 'v1',
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        
        DB::table('criterion_weights')->insert($weightRows);
        
        echo "✅ Simple criteria seeder completed!\n";
        echo "📊 Total criteria: " . $totalCriteria . "\n";
        echo "⚖️ Equal weight per criterion: " . number_format($equalWeight, 4) . "\n";
        echo "🎯 Total weight: " . number_format($totalCriteria * $equalWeight, 4) . "\n";
    }
}
