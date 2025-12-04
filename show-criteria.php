<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "📋 KRITERIA PENDAKIAN GUNUNG (18 Kriteria)\n";
echo str_repeat('=', 100) . "\n\n";

$criteria = DB::table('criteria')
    ->select('code', 'name', 'type', 'source', 'data_type', 'is_fuzzy')
    ->orderBy('code')
    ->get();

echo "🔮 FUZZY vs CRISP Processing:\n";
echo str_repeat('-', 100) . "\n";
printf("%-5s %-35s %-10s %-10s %-15s %-10s\n",
    "Code", "Nama", "Type", "Source", "Data Type", "Processing");
echo str_repeat('-', 100) . "\n";

$fuzzyCount = 0;
$crispCount = 0;

foreach ($criteria as $c) {
    $processing = $c->is_fuzzy ? '🔮 FUZZY' : '📊 CRISP';
    if ($c->is_fuzzy) $fuzzyCount++;
    else $crispCount++;

    printf("%-5s %-35s %-10s %-10s %-15s %-10s\n",
        $c->code,
        substr($c->name, 0, 35),
        $c->type,
        $c->source,
        $c->data_type,
        $processing
    );
}

echo str_repeat('=', 100) . "\n";
echo "\n📊 RINGKASAN:\n";
echo "Total Kriteria: " . count($criteria) . "\n";
echo "🔮 Fuzzy (Subjektif): {$fuzzyCount} kriteria\n";
echo "📊 Crisp (Objektif): {$crispCount} kriteria\n\n";

echo "🔮 FUZZY digunakan untuk:\n";
echo "   - Data ordinal/categorical dari user (pilihan ganda)\n";
echo "   - Contoh: Kondisi Fisik (Sangat Lemah → Sangat Baik)\n\n";

echo "📊 CRISP digunakan untuk:\n";
echo "   - Data numeric objektif (angka langsung)\n";
echo "   - Data dari database (tinggi gunung, panjang jalur, dll)\n";

echo "\n✨ Done!\n";
