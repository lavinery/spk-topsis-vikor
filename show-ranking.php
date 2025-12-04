<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Assessment;
use Illuminate\Support\Facades\DB;

$assessmentId = $argv[1] ?? 92;

$assessment = Assessment::find($assessmentId);

if (!$assessment) {
    echo "❌ Assessment not found!\n";
    exit(1);
}

echo "🏆 TOPSIS RANKING RESULTS\n";
echo str_repeat('=', 100) . "\n";
echo "Assessment: {$assessment->title}\n";
echo "Status: {$assessment->status}\n";
echo str_repeat('=', 100) . "\n\n";

// Get ranking
$rankingStep = $assessment->steps()->where('step', 'RANKING')->first();

if (!$rankingStep) {
    echo "❌ No ranking data found!\n";
    exit(1);
}

$payload = json_decode($rankingStep->payload, true);
$ranking = $payload['ranking'] ?? [];
$CC = $payload['CC'] ?? [];
$rows = $payload['rows'] ?? [];

// Get alternatives data
$alternatives = DB::table('assessment_alternatives as aa')
    ->join('routes as r', 'aa.route_id', '=', 'r.id')
    ->join('mountains as m', 'r.mountain_id', '=', 'm.id')
    ->where('aa.assessment_id', $assessmentId)
    ->where('aa.is_excluded', false)
    ->select('aa.id', 'm.name as mountain_name', 'r.name as route_name',
             'r.distance_km', 'r.elevation_gain_m', 'r.slope_class',
             'r.land_cover_key', 'r.water_sources_score', 'r.support_facility_score')
    ->orderBy('aa.id')
    ->get()
    ->keyBy(function($item, $key) {
        return $key; // Index by array position (0-based)
    })
    ->toArray();

echo "📊 RANKING RESULTS (Top 15):\n";
echo str_repeat('-', 100) . "\n";
printf("%-4s %-35s %-10s %-12s %-10s %-12s %-10s\n",
    "Rank", "Gunung - Jalur", "CC Score", "Distance", "Elev.Gain", "Slope", "Land Cover");
echo str_repeat('-', 100) . "\n";

$rank = 1;
foreach (array_slice($ranking, 0, 15) as $idx) {
    $score = isset($CC[$idx]) ? number_format($CC[$idx], 4) : 'N/A';
    $alt = $alternatives[$idx] ?? null;

    if ($alt) {
        $mountainRoute = "{$alt->mountain_name} - {$alt->route_name}";
        $distance = $alt->distance_km . " km";
        $elevGain = $alt->elevation_gain_m . " m";
        $slope = "Class " . $alt->slope_class;
        $landCover = $alt->land_cover_key;

        printf("%-4d %-35s %-10s %-12s %-12s %-10s %-12s\n",
            $rank,
            substr($mountainRoute, 0, 35),
            $score,
            $distance,
            $elevGain,
            $slope,
            $landCover
        );
    } else {
        echo "$rank. Index $idx - Data not found\n";
    }

    $rank++;
}

echo str_repeat('=', 100) . "\n";

// Show statistics
echo "\n📈 STATISTICS:\n";
echo "Total Alternatives: " . count($alternatives) . "\n";
echo "Criteria Used: {$assessment->n_criteria}\n";
echo "Min CC Score: " . number_format(min($CC), 4) . "\n";
echo "Max CC Score: " . number_format(max($CC), 4) . "\n";
echo "Avg CC Score: " . number_format(array_sum($CC) / count($CC), 4) . "\n";

echo "\n✨ Done!\n";
