<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "📋 ALL CRITERIA IN DATABASE (Active + Inactive):\n";
echo str_repeat('=', 80) . "\n\n";

$all = DB::table('criteria')
    ->orderBy('code')
    ->get(['code', 'name', 'active', 'source']);

printf("%-5s %-40s %-10s %-10s\n", "Code", "Name", "Source", "Status");
echo str_repeat('-', 80) . "\n";

$activeCount = 0;
$inactiveCount = 0;

foreach ($all as $c) {
    $status = $c->active ? '✅ ACTIVE' : '❌ INACTIVE';
    if ($c->active) $activeCount++;
    else $inactiveCount++;

    printf("%-5s %-40s %-10s %-10s\n",
        $c->code,
        substr($c->name, 0, 40),
        $c->source,
        $status
    );
}

echo str_repeat('=', 80) . "\n";
echo "\n📊 SUMMARY:\n";
echo "Total Criteria: " . count($all) . "\n";
echo "✅ Active: {$activeCount}\n";
echo "❌ Inactive: {$inactiveCount}\n";

if ($inactiveCount > 0) {
    echo "\n⚠️  WARNING: Ada kriteria yang INACTIVE!\n";
    echo "Harusnya hanya ada 18 kriteria ACTIVE (C1, C2, C3, C5, C7, C8, C9, C10, C11, C12, C13, C15-C21)\n";
    echo "C4, C6, C14 harus INACTIVE atau dihapus.\n";
}

echo "\n✨ Done!\n";
