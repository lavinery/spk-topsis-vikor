<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 Checking Latest Assessments - Criteria Count\n";
echo str_repeat('=', 100) . "\n\n";

// Get latest 10 assessments with steps
$assessments = DB::table('assessments')
    ->where('status', 'done')
    ->orderBy('id', 'desc')
    ->limit(10)
    ->get();

printf("%-5s %-12s %-15s %-15s %-25s\n",
    "ID", "pure_formula", "n_criteria", "Actual (MATRIX_X)", "Created");
echo str_repeat('-', 100) . "\n";

foreach ($assessments as $a) {
    $pureFormula = $a->pure_formula ? 'TRUE' : 'FALSE';

    // Check MATRIX_X step to see actual criteria count
    $matrixStep = DB::table('assessment_steps')
        ->where('assessment_id', $a->id)
        ->where('step', 'MATRIX_X')
        ->first();

    $actualCount = 0;
    $cols = [];
    if ($matrixStep) {
        $data = json_decode($matrixStep->payload, true);
        $cols = $data['cols'] ?? [];
        $actualCount = count($cols);
    }

    printf("%-5s %-12s %-15s %-15s %-25s\n",
        $a->id,
        $pureFormula,
        $a->n_criteria,
        $actualCount . ' cols',
        substr($a->created_at, 0, 19)
    );

    // Show criteria codes if > 18
    if ($actualCount > 18) {
        echo "   ⚠️  EXTRA CRITERIA: " . implode(', ', $cols) . "\n";
    } elseif ($actualCount === 21) {
        echo "   ⚠️  21 CRITERIA DETECTED! Codes: " . implode(', ', $cols) . "\n";
    }
}

echo "\n" . str_repeat('=', 100) . "\n";
echo "\n📊 LEGEND:\n";
echo "   n_criteria    = Stored count in assessment record\n";
echo "   Actual        = Actual criteria count from MATRIX_X step\n";
echo "   pure_formula  = TRUE means only ROUTE/MOUNTAIN criteria (should be 7)\n";
echo "   pure_formula  = FALSE means ALL criteria (should be 18)\n";

echo "\n✨ Done!\n";
