<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestAssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Get user ID
        $userId = DB::table('users')->where('email', 'test@example.com')->value('id');
        if (!$userId) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        // Get criteria IDs
        $criteriaIds = DB::table('criteria')->pluck('id', 'code')->toArray();

        // Create assessment
        $assessmentId = DB::table('assessments')->insertGetId([
            'user_id' => $userId,
            'title' => 'Test Assessment - TOPSIS Realistis',
            'status' => 'draft',
            'pure_formula' => false,  // Use all criteria (USER + MOUNTAIN + ROUTE)
            'created_at' => $now,
            'updated_at' => $now
        ]);

        /**
         * Test user profile: Intermediate level hiker
         * Values normalized to 0-1 range
         *
         * Profile: 25 years old, good physical condition, minimal health issues,
         * well equipped, highly motivated, moderate experience
         */
        $userAnswers = [
            // === PROFIL PENDAKI (11 kriteria) ===
            'C1' => 0.60,      // Usia: 0.60 (25 tahun, moderately young)
            'C2' => 0.75,      // Kondisi Fisik: 0.75 (good fitness)
            'C3' => 0.20,      // Riwayat Penyakit: 0.20 (minimal issues, sehat)
            'C5' => 0.80,      // Kepemilikan Peralatan: 0.80 (well equipped)
            'C7' => 0.90,      // Motivasi Pendakian: 0.90 (highly motivated)
            'C8' => 0.60,      // Pengalaman Pendakian: 0.60 (moderate, ~5-6 kali)
            'C9' => 0.70,      // Perencanaan Logistik: 0.70 (good planning)
            'C10' => 0.65,     // Keterampilan Alat: 0.65 (competent)
            'C11' => 0.70,     // Kemampuan Survival: 0.70 (decent survival skills)
            'C12' => 0.75,     // Kesiapan Tim: 0.75 (solid team ready)
            'C13' => 0.40,     // Kehadiran Pemandu: 0.40 (prefer with guide for safety)
        ];
        // Total: 11 kriteria profil pendaki (C4, C6, C14 dihapus)

        // Insert assessment answers
        foreach ($userAnswers as $code => $value) {
            if (isset($criteriaIds[$code])) {
                DB::table('assessment_answers')->insert([
                    'assessment_id' => $assessmentId,
                    'criterion_id' => $criteriaIds[$code],
                    'value_raw' => (string)$value,
                    'value_numeric' => is_numeric($value) ? $value : null,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }
        }

        // Create assessment alternatives (select diverse routes for testing)
        $routeIds = DB::table('routes')
            ->join('mountains', 'routes.mountain_id', '=', 'mountains.id')
            ->whereIn('mountains.name', ['Prau', 'Papandayan', 'Bromo', 'Gede', 'Merbabu', 'Lawu', 'Semeru', 'Rinjani'])
            ->pluck('routes.id')
            ->toArray();

        foreach ($routeIds as $routeId) {
            DB::table('assessment_alternatives')->insert([
                'assessment_id' => $assessmentId,
                'route_id' => $routeId,
                'is_excluded' => false,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        $this->command->info("✅ Test assessment created with ID: {$assessmentId}");
        $this->command->info("📊 Number of alternatives: " . count($routeIds));
        $this->command->table(
            ['Criteria', 'Value'],
            collect($userAnswers)->map(fn($v, $k) => [$k, $v])->toArray()
        );
    }
}
