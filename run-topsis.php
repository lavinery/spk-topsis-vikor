<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Assessment;
use App\Services\TopsisService;

// Get assessment ID from command line or use default
$assessmentId = $argv[1] ?? 92;

echo "🚀 Running TOPSIS calculation for assessment ID: {$assessmentId}\n";
echo str_repeat('=', 60) . "\n";

$assessment = Assessment::find($assessmentId);

if (!$assessment) {
    echo "❌ Assessment not found!\n";
    exit(1);
}

echo "📝 Assessment: {$assessment->title}\n";
echo "📊 Status: {$assessment->status}\n";

// Count alternatives
$alternativesCount = $assessment->alternatives()->count();
echo "🏔️  Alternatives: {$alternativesCount}\n";
echo str_repeat('=', 60) . "\n";

try {
    $service = new TopsisService();
    $startTime = microtime(true);

    $service->run($assessment);

    $endTime = microtime(true);
    $executionTime = round(($endTime - $startTime) * 1000, 2);

    // Refresh assessment
    $assessment = $assessment->fresh();

    echo "\n✅ TOPSIS calculation completed successfully!\n";
    echo "⏱️  Execution time: {$executionTime}ms\n";
    echo "📊 Final status: {$assessment->status}\n";
    echo "🎯 Alternatives processed: {$assessment->n_alternatives}\n";
    echo "📋 Criteria used: {$assessment->n_criteria}\n";
    echo str_repeat('=', 60) . "\n";

    // Get ranking results
    $rankingStep = $assessment->steps()->where('step', 'RANKING')->first();

    if ($rankingStep) {
        $payload = json_decode($rankingStep->payload, true);
        $ranking = $payload['ranking'] ?? [];
        $CC = $payload['CC'] ?? [];

        echo "\n🏆 TOP 10 RANKING RESULTS:\n";
        echo str_repeat('=', 60) . "\n";

        $counter = 1;
        foreach (array_slice($ranking, 0, 10) as $idx) {
            $score = isset($CC[$idx]) ? number_format($CC[$idx], 4) : 'N/A';
            echo str_pad($counter, 3, ' ', STR_PAD_LEFT) . ". {$idx} (CC: {$score})\n";
            $counter++;
        }
    }

} catch (\Exception $e) {
    echo "\n❌ Error during TOPSIS calculation:\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}

echo "\n✨ Done!\n";
