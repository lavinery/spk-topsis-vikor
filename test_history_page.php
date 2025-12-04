<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST HISTORY PAGE ===\n\n";

// Simulate different users
$users = \App\Models\User::all();

foreach ($users as $user) {
    echo "--- Testing for User: {$user->email} (ID: {$user->id}) ---\n";

    // Get roles
    $roles = \DB::table('model_has_roles')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('model_has_roles.model_id', $user->id)
        ->where('model_has_roles.model_type', 'App\Models\User')
        ->pluck('roles.name')
        ->toArray();

    $roleStr = empty($roles) ? 'no role' : implode(', ', $roles);
    echo "Role: {$roleStr}\n";

    // Query assessments like the Livewire component does
    $assessments = \App\Models\Assessment::where('user_id', $user->id)
        ->latest()
        ->get();

    echo "Total assessments: " . $assessments->count() . "\n";

    if ($assessments->isNotEmpty()) {
        echo "Assessments:\n";
        foreach ($assessments->take(5) as $a) {
            echo sprintf("  - ID: %d | Status: %s | Title: %s | Created: %s\n",
                $a->id, $a->status, $a->title, $a->created_at->format('Y-m-d H:i'));
        }
    } else {
        echo "  ❌ NO ASSESSMENTS FOUND\n";
    }

    echo "\n";
}

echo "=== IMPORTANT ===\n";
echo "If you're logged in as ADMIN, you should:\n";
echo "1. Logout from admin account\n";
echo "2. Login as: user@example.com OR test@example.com\n";
echo "3. Then check the History page (/user/history)\n";
echo "\nDone.\n";
