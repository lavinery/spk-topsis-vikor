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
            'title' => 'Test Assessment - TOPSIS Traditional',
            'status' => 'draft',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        // Test user profile (moderate level hiker)
        $userAnswers = [
            'C1' => 25,        // Usia: 25 tahun
            'C2' => 4,         // Physical Fitness: 4/5 (good)
            'C3' => 1,         // Riwayat Kardiovaskular: 1/5 (no issues)
            'C4' => 3,         // Kepercayaan Diri: 3/5 (moderate)
            'C5' => 4,         // Kepemilikan Peralatan: 4/5 (well equipped)
            'C6' => 3,         // Pengetahuan P3K: 3/5 (basic)
            'C7' => 'high',    // Motivasi Pendakian: high
            'C8' => 3,         // Pengalaman Pendakian: 3 times
            'C9' => 3,         // Perencanaan Logistik: 3/5 (moderate)
            'C10' => 3,        // Skill Penggunaan Alat: 3/5 (moderate)
            'C11' => 3,        // Kemampuan Survival: 3/5 (moderate)
            'C12' => 4,        // Kesiapan Tim: 4/5 (good)
            'C13' => 2,        // Kehadiran Pemandu: 2/5 (prefer with guide)
            'C14' => 3,        // Pembagian Tugas Tim: 3/5 (moderate)
        ];

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

        // Create assessment alternatives (all routes from test mountains)
        $routeIds = DB::table('routes')
            ->join('mountains', 'routes.mountain_id', '=', 'mountains.id')
            ->whereIn('mountains.name', ['Semeru', 'Rinjani', 'Gede', 'Pangrango', 'Slamet', 'Merbabu', 'Sindoro', 'Sumbing', 'Lawu', 'Kerinci'])
            ->pluck('routes.id')
            ->toArray();

        foreach ($routeIds as $routeId) {
            DB::table('assessment_alternatives')->insert([
                'assessment_id' => $assessmentId,
                'route_id' => $routeId,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        echo "Test assessment created with ID: {$assessmentId}\n";
        echo "User answers: " . json_encode($userAnswers, JSON_PRETTY_PRINT) . "\n";
        echo "Number of alternatives: " . count($routeIds) . "\n";
    }
}
