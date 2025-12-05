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

        /**
         * 18 Kriteria Pendakian Gunung (C1-C18)
         *
         * PROFIL PENDAKI (11 kriteria: C1-C11)
         * KARAKTERISTIK JALUR (7 kriteria: C12-C18)
         *
         * Format: [code, name, type, source, data_type, is_fuzzy]
         * is_fuzzy = true  → Fuzzy logic untuk data subjektif/ordinal
         * is_fuzzy = false → Crisp value untuk data objektif/numeric
         */
        $crit = [
            // === PROFIL PENDAKI (C1-C11) ===
            ['C1', 'Usia', 'cost', 'USER', 'numeric', false],        // Numeric langsung (tahun)
            ['C2', 'Kondisi Fisik', 'benefit', 'USER', 'ordinal', true],  // Ordinal → FUZZY
            ['C3', 'Riwayat Penyakit', 'cost', 'USER', 'ordinal', true],  // Ordinal → FUZZY
            ['C4', 'Kepemilikan Peralatan', 'benefit', 'USER', 'ordinal', true],  // Ordinal → FUZZY
            ['C5', 'Motivasi Pendakian', 'benefit', 'USER', 'categorical', true], // Categorical → FUZZY
            ['C6', 'Pengalaman Pendakian', 'benefit', 'USER', 'numeric', false],  // Numeric langsung (jumlah)
            ['C7', 'Perencanaan Logistik', 'benefit', 'USER', 'ordinal', true],   // Ordinal → FUZZY
            ['C8', 'Keterampilan Alat Pendakian', 'benefit', 'USER', 'ordinal', true], // Ordinal → FUZZY
            ['C9', 'Kemampuan Bertahan Hidup', 'benefit', 'USER', 'ordinal', true],    // Ordinal → FUZZY
            ['C10', 'Kesiapan Anggota Pendakian', 'benefit', 'USER', 'ordinal', true],  // Ordinal → FUZZY
            ['C11', 'Kehadiran Pemandu', 'benefit', 'USER', 'ordinal', true],           // Ordinal → FUZZY

            // === KARAKTERISTIK JALUR (C12-C18) - Semua dari database, tidak perlu fuzzy ===
            ['C12', 'Ketinggian Gunung (mdpl)', 'cost', 'MOUNTAIN', 'numeric', false],
            ['C13', 'Elevasi Jalur (m)', 'cost', 'ROUTE', 'numeric', false],
            ['C14', 'Tutupan Lahan', 'cost', 'ROUTE', 'categorical', false],  // Category mapping
            ['C15', 'Panjang Jalur (km)', 'cost', 'ROUTE', 'numeric', false],
            ['C16', 'Kecuraman Jalur', 'cost', 'ROUTE', 'numeric', false],
            ['C17', 'Ketersediaan Sumber Air', 'benefit', 'ROUTE', 'ordinal', false],
            ['C18', 'Ketersediaan Sarana Pendukung', 'benefit', 'ROUTE', 'ordinal', false],
        ];

        // Delete old criteria codes that are no longer used (old numbering)
        DB::table('criteria')->whereIn('code', ['C5', 'C7', 'C8', 'C9', 'C10', 'C12', 'C13', 'C15', 'C16', 'C17', 'C18', 'C19', 'C20', 'C21'])->delete();

        $rows = [];
        foreach ($crit as [$code, $name, $type, $source, $dt, $isFuzzy]) {
            $rows[] = [
                'code' => $code,
                'name' => $name,
                'type' => $type,
                'source' => $source,
                'data_type' => $dt,
                'is_fuzzy' => $isFuzzy,
                'unit' => null,
                'active' => 1,
                'version' => 'v1',
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        DB::table('criteria')->upsert($rows, ['code'], ['name', 'type', 'source', 'data_type', 'is_fuzzy', 'unit', 'active', 'version', 'updated_at']);

        /**
         * Bobot Kriteria yang Realistis (Total = 1.0)
         *
         * Prioritas Tinggi (8-10%):
         * - C2 (Kondisi Fisik): 0.10 - Sangat penting untuk keselamatan
         * - C6 (Pengalaman): 0.09 - Krusial untuk kemampuan pendakian
         * - C16 (Kecuraman): 0.08 - Faktor difficulty utama
         *
         * Prioritas Sedang (5-7%):
         * - C1 (Usia): 0.07 - Mempengaruhi stamina
         * - C3 (Riwayat Penyakit): 0.07 - Keselamatan
         * - C9 (Survival): 0.06 - Penting untuk emergency
         * - C13 (Elevasi): 0.06 - Menentukan difficulty
         * - C15 (Panjang Jalur): 0.06 - Menentukan durasi
         *
         * Prioritas Normal (3-5%):
         * - C4 (Peralatan): 0.05
         * - C7 (Logistik): 0.05
         * - C8 (Keterampilan): 0.05
         * - C10 (Tim): 0.04
         * - C12 (Ketinggian): 0.04
         * - C14 (Tutupan Lahan): 0.04
         *
         * Prioritas Rendah (2-3%):
         * - C5 (Motivasi): 0.03
         * - C11 (Pemandu): 0.03
         * - C17 (Air): 0.04
         * - C18 (Sarana): 0.04
         */
        $w = [
            // Profil Pendaki (Total: 0.61)
            'C1'  => 0.07,   // Usia
            'C2'  => 0.10,   // Kondisi Fisik (HIGHEST)
            'C3'  => 0.07,   // Riwayat Penyakit
            'C4'  => 0.05,   // Kepemilikan Peralatan
            'C5'  => 0.03,   // Motivasi Pendakian
            'C6'  => 0.09,   // Pengalaman Pendakian (HIGH)
            'C7'  => 0.05,   // Perencanaan Logistik
            'C8'  => 0.05,   // Keterampilan Alat
            'C9'  => 0.06,   // Kemampuan Survival
            'C10' => 0.04,   // Kesiapan Tim
            'C11' => 0.03,   // Kehadiran Pemandu

            // Karakteristik Jalur (Total: 0.39)
            'C12' => 0.04,   // Ketinggian Gunung
            'C13' => 0.06,   // Elevasi Jalur
            'C14' => 0.04,   // Tutupan Lahan
            'C15' => 0.06,   // Panjang Jalur
            'C16' => 0.08,   // Kecuraman (HIGH - faktor difficulty utama)
            'C17' => 0.04,   // Sumber Air
            'C18' => 0.04,   // Sarana Pendukung
        ];
        // Total: 1.00 (verified)

        $critIds = DB::table('criteria')->pluck('id', 'code');
        DB::table('criterion_weights')->truncate();
        $wr = [];
        $totalWeight = 0;

        foreach ($w as $code => $val) {
            if (isset($critIds[$code])) {
                $wr[] = [
                    'criterion_id' => $critIds[$code],
                    'weight' => $val,
                    'version' => 'v1',
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                $totalWeight += $val;
            }
        }

        DB::table('criterion_weights')->insert($wr);

        // Count fuzzy vs crisp
        $fuzzyCount = collect($crit)->filter(fn($c) => $c[5] === true)->count();
        $crispCount = count($crit) - $fuzzyCount;

        $this->command->info("✅ Successfully seeded " . count($crit) . " criteria");
        $this->command->info("📊 Total weight: " . number_format($totalWeight, 2));
        $this->command->info("⚖️  Weight distribution:");
        $this->command->info("   - Profil Pendaki: 61%");
        $this->command->info("   - Karakteristik Jalur: 39%");
        $this->command->info("🔮 Fuzzy processing:");
        $this->command->info("   - Fuzzy (subjektif/ordinal): {$fuzzyCount} criteria");
        $this->command->info("   - Crisp (objektif/numeric): {$crispCount} criteria");
    }
}
