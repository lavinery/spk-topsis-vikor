<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 Checking Assessment pure_formula values:\n";
echo str_repeat('=', 80) . "\n\n";

$assessments = DB::table('assessments')
    ->select('id', 'pure_formula', 'status', 'n_criteria', 'created_at')
    ->orderBy('id', 'desc')
    ->limit(10)
    ->get();

if ($assessments->isEmpty()) {
    echo "❌ No assessments found.\n";
    exit;
}

printf("%-5s %-15s %-10s %-12s %-20s\n",
    "ID", "pure_formula", "Status", "N_Criteria", "Created");
echo str_repeat('-', 80) . "\n";

foreach ($assessments as $a) {
    $pureFormula = $a->pure_formula ? '✅ TRUE' : '❌ FALSE';

    printf("%-5s %-15s %-10s %-12s %-20s\n",
        $a->id,
        $pureFormula,
        $a->status,
        $a->n_criteria,
        $a->created_at
    );
}

echo "\n" . str_repeat('=', 80) . "\n";
echo "\n📌 EXPLANATION:\n";
echo "   ✅ TRUE  = Only MOUNTAIN/ROUTE criteria (C15-C21) - EXCLUDES USER criteria\n";
echo "   ❌ FALSE = All criteria including USER (C1-C14)\n\n";

echo "🔍 Now checking USER criteria in criteria table:\n";
$userCriteria = DB::table('criteria')
    ->where('source', 'USER')
    ->where('active', 1)
    ->select('code', 'name', 'source')
    ->orderBy('code')
    ->get();

echo "Found " . $userCriteria->count() . " active USER criteria:\n";
foreach ($userCriteria as $c) {
    echo "  {$c->code} - {$c->name}\n";
}

echo "\n✨ Done!\n";
