<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DATABASE CHECK ===\n";
echo "Total Assessments: " . \App\Models\Assessment::count() . "\n";
echo "Total Users: " . \App\Models\User::count() . "\n\n";

echo "=== USERS ===" . "\n";
$users = \App\Models\User::all();
foreach ($users as $user) {
    $roles = \DB::table('model_has_roles')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('model_has_roles.model_id', $user->id)
        ->where('model_has_roles.model_type', 'App\Models\User')
        ->pluck('roles.name')
        ->toArray();

    $roleStr = empty($roles) ? 'no role' : implode(', ', $roles);
    $count = \App\Models\Assessment::where('user_id', $user->id)->count();
    echo sprintf("User #%d | %s | Role: %s | Assessments: %d\n",
        $user->id, $user->email, $roleStr, $count);
}

echo "\n=== RECENT ASSESSMENTS ===\n";
$assessments = \App\Models\Assessment::latest()->take(10)->get();
if ($assessments->isEmpty()) {
    echo "NO ASSESSMENTS FOUND!\n";
} else {
    foreach ($assessments as $a) {
        echo sprintf("ID: %d | User: %d | Status: %s | Title: %s | Created: %s\n",
            $a->id, $a->user_id, $a->status, $a->title, $a->created_at);
    }
}

echo "\n=== SUGGESTIONS ===\n";

// Check if there's a regular user (not admin)
$regularUser = null;
foreach ($users as $user) {
    $isAdmin = \DB::table('model_has_roles')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('model_has_roles.model_id', $user->id)
        ->where('model_has_roles.model_type', 'App\Models\User')
        ->whereIn('roles.name', ['admin', 'editor'])
        ->exists();

    if (!$isAdmin) {
        $regularUser = $user;
        break;
    }
}

if (!$regularUser) {
    echo "❌ No regular user found. All users are admin/editor.\n";
    echo "   Admins cannot create assessments.\n";
    echo "   Please create a regular user account first.\n";
} else {
    $hasAssessments = \App\Models\Assessment::where('user_id', $regularUser->id)->exists();

    if (!$hasAssessments) {
        echo "✓ Regular user found: {$regularUser->email} (ID: {$regularUser->id})\n";
        echo "⚠ But this user has NO assessments yet.\n";
        echo "   Please submit an assessment as this user.\n";
    } else {
        echo "✓ Regular user {$regularUser->email} has assessments.\n";
        echo "   Login as this user to see the history page.\n";
    }
}

echo "\nDone.\n";
