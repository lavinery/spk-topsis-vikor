<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryMap;
use App\Models\Criterion;
use Illuminate\Support\Facades\DB;

class CategoryMapsSampleSeeder extends Seeder
{
    public function run(): void
    {
        // Get C17 (Tutupan Lahan) criterion
        $c17 = Criterion::where('code', 'C17')->first();
        
        if (!$c17) {
            echo "C17 criterion not found. Please run CriteriaAndWeightsSeeder first.\n";
            return;
        }

        // Sample data for C17 (Tutupan Lahan)
        $landCoverMaps = [
            ['key' => 'hutan', 'score' => 0.2, 'label' => 'Hutan lebat'],
            ['key' => 'savana', 'score' => 0.6, 'label' => 'Savana'],
            ['key' => 'campuran', 'score' => 0.4, 'label' => 'Hutan campuran'],
            ['key' => 'semak', 'score' => 0.8, 'label' => 'Semak belukar'],
            ['key' => 'padang-rumput', 'score' => 0.9, 'label' => 'Padang rumput'],
            ['key' => 'lahan-terbuka', 'score' => 1.0, 'label' => 'Lahan terbuka'],
        ];

        foreach ($landCoverMaps as $map) {
            CategoryMap::updateOrCreate(
                [
                    'criterion_id' => $c17->id,
                    'key' => $map['key']
                ],
                [
                    'score' => $map['score'],
                    'label' => $map['label'],
                ]
            );
        }

        echo "Category maps sample data created for C17 (Tutupan Lahan)\n";
        echo "Created " . count($landCoverMaps) . " mappings\n";
    }
}
