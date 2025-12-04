<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 Checking assessments table pure_formula column:\n\n";

$result = DB::select("SHOW COLUMNS FROM assessments WHERE Field = 'pure_formula'");

if (empty($result)) {
    echo "❌ Column pure_formula does not exist!\n";
} else {
    $col = $result[0];
    echo "Column: {$col->Field}\n";
    echo "Type: {$col->Type}\n";
    echo "Null: {$col->Null}\n";
    echo "Default: " . ($col->Default ?? 'NULL') . "\n";

    echo "\n📌 INTERPRETATION:\n";
    if ($col->Default === '1') {
        echo "   ⚠️  DEFAULT is TRUE (1) - This is WRONG!\n";
        echo "   This means new assessments exclude USER criteria by default.\n";
    } elseif ($col->Default === '0') {
        echo "   ✅ DEFAULT is FALSE (0) - This is CORRECT!\n";
        echo "   New assessments will include all criteria.\n";
    } else {
        echo "   ⚠️  DEFAULT is NULL or not set\n";
        echo "   This may cause unexpected behavior.\n";
    }
}

echo "\n✨ Done!\n";
