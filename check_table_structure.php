<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ASSESSMENT_ANSWERS TABLE STRUCTURE ===\n\n";

try {
    $columns = DB::select('DESCRIBE assessment_answers');
    echo "Columns in assessment_answers table:\n";
    foreach ($columns as $col) {
        echo "- {$col->Field}: {$col->Type}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== ASSESSMENT_ALTERNATIVES TABLE STRUCTURE ===\n\n";

try {
    $columns = DB::select('DESCRIBE assessment_alternatives');
    echo "Columns in assessment_alternatives table:\n";
    foreach ($columns as $col) {
        echo "- {$col->Field}: {$col->Type}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== ASSESSMENT_STEPS TABLE STRUCTURE ===\n\n";

try {
    $columns = DB::select('DESCRIBE assessment_steps');
    echo "Columns in assessment_steps table:\n";
    foreach ($columns as $col) {
        echo "- {$col->Field}: {$col->Type}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
