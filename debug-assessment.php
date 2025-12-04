<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// Get latest assessment
$a = DB::table('assessments')->orderBy('id', 'desc')->first();

echo "🔍 Debugging Assessment ID: {$a->id}\n";
echo str_repeat('=', 80) . "\n\n";

echo "Assessment details:\n";
echo "  pure_formula: " . ($a->pure_formula ? 'TRUE' : 'FALSE') . "\n";
echo "  status: {$a->status}\n";
echo "  n_criteria: {$a->n_criteria}\n";
echo "  n_alternatives: {$a->n_alternatives}\n\n";

// Check answers
$answers = DB::table('assessment_answers')
    ->where('assessment_id', $a->id)
    ->count();
echo "Answers count: {$answers}\n\n";

// Check alternatives
$alts = DB::table('assessment_alternatives')
    ->where('assessment_id', $a->id)
    ->count();
echo "Alternatives count: {$alts}\n\n";

// Check steps
echo "Steps:\n";
$steps = DB::table('assessment_steps')
    ->where('assessment_id', $a->id)
    ->orderBy('id')
    ->get();

foreach ($steps as $step) {
    echo "  - {$step->step}\n";

    $data = json_decode($step->payload, true);

    if ($step->step === 'VALIDATION_MISSING') {
        echo "    dropped: " . json_encode($data['dropped'] ?? []) . "\n";
        echo "    dropped_count: " . ($data['dropped_count'] ?? 0) . "\n";
    }

    if ($step->step === 'MATRIX_X') {
        echo "    cols: " . json_encode($data['cols'] ?? []) . "\n";
        echo "    rows count: " . count($data['rows'] ?? []) . "\n";
        echo "    X count: " . count($data['X'] ?? []) . "\n";
    }
}

echo "\n✨ Done!\n";
