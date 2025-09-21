<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FuzzyTermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get USER criteria (C1-C14)
        $userCriteria = DB::table('criteria')
            ->where('source', 'USER')
            ->where('active', 1)
            ->orderByRaw("CAST(SUBSTRING(code, 2) AS UNSIGNED)")
            ->get();

        foreach ($userCriteria as $criterion) {
            $this->createFuzzyTermsForCriterion($criterion);
            $this->createFuzzyMappingForCriterion($criterion);
        }

        // Ensure all C1-C14 criteria have is_fuzzy=true
        DB::table('criteria')
            ->where('source', 'USER')
            ->where('active', 1)
            ->whereRaw("code REGEXP '^C([1-9]|1[0-4])$'")
            ->update(['is_fuzzy' => true]);
    }
    
    private function createFuzzyTermsForCriterion($criterion): void
    {
        $criterionId = $criterion->id;
        $code = $criterion->code;
        
        // Define fuzzy terms based on criterion type
        if (in_array($code, ['C1', 'C2', 'C3', 'C4'])) {
            // For C1-C4: C1(Credential: Pengalaman), C2(Kondisi fisik), C3(Tingkat kesulitan diinginkan), C4(Preferensi cuaca)
            // Scale 1..5 with exact triangular parameters
            $terms = [
                [
                    'code' => 'RENDAH',
                    'label' => 'Rendah',
                    'shape' => 'triangular',
                    'params_json' => json_encode([1, 1, 2.5])
                ],
                [
                    'code' => 'SEDANG',
                    'label' => 'Sedang',
                    'shape' => 'triangular',
                    'params_json' => json_encode([2, 3, 4])
                ],
                [
                    'code' => 'TINGGI',
                    'label' => 'Tinggi',
                    'shape' => 'triangular',
                    'params_json' => json_encode([3.5, 5, 5])
                ]
            ];
        } else {
            // For other criteria, use similar pattern but adjust based on type
            $terms = [
                [
                    'code' => 'RENDAH',
                    'label' => 'Rendah',
                    'shape' => 'triangular',
                    'params_json' => json_encode([1.0, 1.5, 2.5])
                ],
                [
                    'code' => 'SEDANG',
                    'label' => 'Sedang',
                    'shape' => 'triangular',
                    'params_json' => json_encode([2.0, 3.0, 4.0])
                ],
                [
                    'code' => 'TINGGI',
                    'label' => 'Tinggi',
                    'shape' => 'triangular',
                    'params_json' => json_encode([3.5, 4.5, 5.0])
                ]
            ];
        }
        
        foreach ($terms as $term) {
            DB::table('fuzzy_terms')->updateOrInsert(
                [
                    'criterion_id' => $criterionId,
                    'code' => $term['code']
                ],
                array_merge($term, [
                    'criterion_id' => $criterionId,
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }
    }
    
    private function createFuzzyMappingForCriterion($criterion): void
    {
        DB::table('fuzzy_mappings')->updateOrInsert(
            ['criterion_id' => $criterion->id],
            [
                'criterion_id' => $criterion->id,
                'input_min' => 1.0,
                'input_max' => 5.0,
                'default_term_code' => 'SEDANG', // default to middle term
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}