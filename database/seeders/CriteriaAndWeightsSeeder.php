<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CriteriaAndWeightsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $crit = [
            // USER (C1..C14)
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
            // ROUTE/MOUNTAIN (C15..C21)
            ['C15', 'Ketinggian (mdpl)', 'cost', 'MOUNTAIN', 'numeric'],
            ['C16', 'Elevasi/Gain (m)', 'cost', 'ROUTE', 'numeric'],
            ['C17', 'Tutupan Lahan', 'cost', 'ROUTE', 'categorical'],
            ['C18', 'Panjang Jalur (km)', 'cost', 'ROUTE', 'numeric'],
            ['C19', 'Kecuraman', 'cost', 'ROUTE', 'numeric'],
            ['C20', 'Sumber Air', 'benefit', 'ROUTE', 'ordinal'],
            ['C21', 'Sarana Pendukung', 'benefit', 'ROUTE', 'ordinal'],
        ];
        
        $rows = [];
        foreach ($crit as [$code, $name, $type, $source, $dt]) {
            $rows[] = [
                'code' => $code,
                'name' => $name,
                'type' => $type,
                'source' => $source,
                'data_type' => $dt,
                'unit' => null,
                'active' => 1,
                'version' => 'v1',
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        
        DB::table('criteria')->upsert($rows, ['code'], ['name', 'type', 'source', 'data_type', 'unit', 'active', 'version', 'updated_at']);

        $w = [
            'C1' => 0.03, 'C2' => 0.08, 'C3' => 0.05, 'C4' => 0.06, 'C5' => 0.06, 'C6' => 0.06, 'C7' => 0.05,
            'C8' => 0.07, 'C9' => 0.07, 'C10' => 0.06, 'C11' => 0.06, 'C12' => 0.06, 'C13' => 0.05, 'C14' => 0.05,
            'C15' => 0.04, 'C16' => 0.04, 'C17' => 0.03, 'C18' => 0.03, 'C19' => 0.03, 'C20' => 0.01, 'C21' => 0.01
        ];
        
        $critIds = DB::table('criteria')->pluck('id', 'code');
        DB::table('criterion_weights')->truncate();
        $wr = [];
        foreach ($w as $code => $val) {
            if (isset($critIds[$code])) {
                $wr[] = [
                    'criterion_id' => $critIds[$code],
                    'weight' => $val,
                    'version' => 'v1',
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
        }
        DB::table('criterion_weights')->insert($wr);
    }
}
