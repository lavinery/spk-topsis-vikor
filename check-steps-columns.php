<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 Checking assessment_steps table columns:\n\n";

$result = DB::select("SHOW COLUMNS FROM assessment_steps");

foreach ($result as $col) {
    echo "  - {$col->Field} ({$col->Type})\n";
}

echo "\n✨ Done!\n";
