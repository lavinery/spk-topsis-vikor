<?php
// Check assessment data
$assessment = \App\Models\Assessment::latest()->first();

if ($assessment) {
    echo "Latest Assessment ID: " . $assessment->id . "\n";
    echo "Status: " . $assessment->status . "\n";
    echo "Title: " . $assessment->title . "\n";
    echo "Alternatives count: " . $assessment->alternatives()->count() . "\n";
    echo "Answers count: " . $assessment->answers()->count() . "\n";
    echo "Steps count: " . $assessment->steps()->count() . "\n";
    
    // Check alternatives
    $alternatives = $assessment->alternatives()->with('route.mountain')->get();
    echo "\nAlternatives:\n";
    foreach ($alternatives as $alt) {
        echo "- Route: " . $alt->route->name . " (Mountain: " . $alt->route->mountain->name . ")\n";
    }
    
    // Check if TOPSIS has been run
    $topsisStep = $assessment->steps()->where('step_name', 'TOPSIS')->first();
    if ($topsisStep) {
        echo "\nTOPSIS Step found:\n";
        echo "- Status: " . $topsisStep->status . "\n";
        echo "- Data: " . substr($topsisStep->data_json, 0, 100) . "...\n";
    } else {
        echo "\nNo TOPSIS step found\n";
    }
} else {
    echo "No assessments found\n";
}
?>
