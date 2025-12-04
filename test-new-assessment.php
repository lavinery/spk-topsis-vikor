<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Assessment;
use App\Models\Criterion;
use App\Models\AssessmentAnswer;
use App\Models\AssessmentAlternative;
use App\Services\TopsisService;

echo "🧪 Testing New Assessment with Fixed pure_formula\n";
echo str_repeat('=', 80) . "\n\n";

// 1) Create test user if not exists
$user = DB::table('users')->where('email', 'test@example.com')->first();
if (!$user) {
    DB::table('users')->insert([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        'created_at' => now(),
        'updated_at' => now()
    ]);
    $user = DB::table('users')->where('email', 'test@example.com')->first();
}

echo "✅ Test user: {$user->email} (ID: {$user->id})\n\n";

// 2) Create assessment WITHOUT setting pure_formula (should default to FALSE now)
echo "📝 Creating assessment without pure_formula...\n";
$a = Assessment::create([
    'user_id' => $user->id,
    'title' => 'Test Assessment - All Criteria',
    'status' => 'draft',
    'top_k' => 5,
    // NOT setting pure_formula - should default to FALSE
]);

echo "✅ Assessment created: ID={$a->id}, pure_formula=" . ($a->pure_formula ? 'TRUE' : 'FALSE') . "\n\n";

// 3) Add USER criteria answers
echo "📋 Adding USER criteria answers...\n";
$userCrit = Criterion::where('source', 'USER')->where('active', 1)->get();
foreach ($userCrit as $c) {
    AssessmentAnswer::create([
        'assessment_id' => $a->id,
        'criterion_id' => $c->id,
        'value_raw' => '3', // default medium value
        'value_normalized' => 0.5
    ]);
}
echo "✅ Added " . $userCrit->count() . " USER criteria answers\n\n";

// 4) Add route alternatives
echo "🗺️  Adding route alternatives...\n";
$routes = DB::table('routes')->limit(10)->pluck('id');
foreach ($routes as $rid) {
    AssessmentAlternative::create([
        'assessment_id' => $a->id,
        'route_id' => $rid,
        'is_excluded' => false
    ]);
}
echo "✅ Added " . $routes->count() . " route alternatives\n\n";

// 5) Run TOPSIS
echo "🔮 Running TOPSIS calculation...\n";
$topsis = new TopsisService();
$topsis->run($a);
$a->refresh();

echo "✅ TOPSIS completed!\n";
echo "   Status: {$a->status}\n";
echo "   N_Criteria: {$a->n_criteria}\n";
echo "   N_Alternatives: {$a->n_alternatives}\n\n";

// 6) Check MATRIX_X step to see which criteria were used
$matrixStep = DB::table('assessment_steps')
    ->where('assessment_id', $a->id)
    ->where('step', 'MATRIX_X')
    ->first();

if ($matrixStep) {
    $data = json_decode($matrixStep->payload, true);
    $cols = $data['cols'] ?? [];

    echo "📊 Criteria used in TOPSIS:\n";
    echo "   Total: " . count($cols) . " criteria\n";
    echo "   Codes: " . implode(', ', $cols) . "\n\n";

    // Check if USER criteria are included
    $userCodes = ['C1', 'C2', 'C3', 'C5', 'C7', 'C8', 'C9', 'C10', 'C11', 'C12', 'C13'];
    $routeCodes = ['C15', 'C16', 'C17', 'C18', 'C19', 'C20', 'C21'];

    $userIncluded = array_filter($userCodes, fn($c) => in_array($c, $cols));
    $routeIncluded = array_filter($routeCodes, fn($c) => in_array($c, $cols));

    echo "   🔮 USER criteria (C1-C13):   " . count($userIncluded) . "/11 included\n";
    echo "      " . implode(', ', $userIncluded) . "\n\n";

    echo "   🗻 ROUTE criteria (C15-C21): " . count($routeIncluded) . "/7 included\n";
    echo "      " . implode(', ', $routeIncluded) . "\n\n";

    if (count($userIncluded) === 11 && count($routeIncluded) === 7) {
        echo "✅ SUCCESS! All 18 criteria are included!\n";
    } else {
        echo "❌ FAIL! Some criteria are missing.\n";
    }
} else {
    echo "❌ MATRIX_X step not found!\n";
}

echo "\n" . str_repeat('=', 80) . "\n";
echo "✨ Test completed! Assessment ID: {$a->id}\n";
