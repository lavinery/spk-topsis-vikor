<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConstraintsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('constraints')->insert([
            [
                'name' => 'CardioHigh_vs_Slope',
                'expr_json' => json_encode([
                    'all' => [
                        ['criterion' => 'C3', 'op' => 'eq', 'value' => 'high'],     // raw level
                        ['criterion' => 'C19', 'op' => 'gt', 'value' => 3]          // slope class > 3
                    ]
                ]),
                'action' => 'exclude_alternative',
                'active' => true
            ],
            [
                'name' => 'Closed_Mountain',
                'expr_json' => json_encode([
                    'any' => [
                        ['route_attr' => 'mountain.status', 'op' => 'eq', 'value' => 'closed']
                    ]
                ]),
                'action' => 'exclude_alternative',
                'active' => true
            ],
        ]);
    }
}
