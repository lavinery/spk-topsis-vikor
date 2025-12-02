<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssessmentRunController;
use App\Http\Controllers\AssessmentsExportController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\WhatIfController;
use App\Livewire\Assessment\ResultTop;
use App\Livewire\Assessment\StepsViewer;
use App\Livewire\Assessment\UserWizard;

// Landing page - hanya untuk user biasa yang sudah login (bukan admin)
Route::middleware(['auth'])->group(function() {
    Route::get('/', [LandingController::class,'index'])->name('landing');
    Route::post('/start', [LandingController::class,'start'])->name('landing.start');
});

// API Endpoint for external integration (muncak.id)
Route::get('/api/test-recommendation/{mountainId}', [LandingController::class,'testRecommendation'])->name('api.test-recommendation');
Route::post('/api/start-assessment', [LandingController::class,'startAssessmentApi'])->name('api.start-assessment');
Route::get('/api/mountains-list', [LandingController::class,'getMountainsList'])->name('api.mountains-list');

// CSRF token endpoint
Route::get('/csrf-token', function() {
    return response()->json(['csrf_token' => csrf_token()]);
});

// Auth routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = \Illuminate\Support\Facades\Auth::user();

        // Redirect based on role
        if ($user->hasRole('admin') || $user->hasRole('editor')) {
            return redirect()->route('admin.dashboard');
        }

        // User biasa redirect ke landing page (halaman pemilihan gunung)
        return redirect()->route('landing');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
});

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::middleware(['auth'])->group(function(){
    // Redirect old assessment interface to landing page
    Route::get('/assessments/start', function() {
        return redirect()->route('landing');
    })->name('assess.start');
    Route::get('/assessments', function() {
        return redirect()->route('landing');
    })->name('assess.index');
    Route::post('/assessments/{id}/run', [AssessmentRunController::class,'run'])->name('assess.run');
    Route::get('/assessments/{id}/result', ResultTop::class)->name('assess.result');
    Route::get('/assessments/{id}/wizard', UserWizard::class)->name('assess.wizard');
    
    // Export routes
    Route::get('/assessments/{id}/export/{algorithm}/{step}', [ExportController::class,'csv'])->name('assess.export.csv');
    Route::get('/assessments/{id}/export/summary', [ExportController::class,'summary'])->name('assess.export.summary');
    Route::get('/assessments/{id}/export/snapshot', [ExportController::class,'snapshot'])->name('assess.export.snapshot');
    
    // What-If analysis routes
    Route::post('/assessments/{id}/what-if', [WhatIfController::class,'run'])->name('assess.whatif');
    Route::get('/assessments/{id}/what-if/criteria', [WhatIfController::class,'criteria'])->name('assess.whatif.criteria');
    Route::get('/assessments/{id}/what-if/history', [WhatIfController::class,'history'])->name('assess.whatif.history');
    Route::post('/assessments/{id}/what-if/save-preset', [WhatIfController::class,'saveAsPreset'])->name('assess.whatif.save-preset');
    
    // Steps viewer (admin & editor bisa full; user hanya lihat ringkas)
    Route::get('/assessments/{id}/steps', StepsViewer::class)->name('assess.steps');

    // Export per step
    Route::get('/assessments/{id}/export/{step}.{format}', [AssessmentsExportController::class,'export'])
        ->whereIn('format',['csv','xlsx','pdf'])
        ->name('assess.export');
});

// Admin Panel Routes
Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function(){
    Route::view('/','admin.dashboard')->name('dashboard');

    // CRUD master
    Route::get('/mountains', \App\Livewire\Admin\MountainsCrud::class)->name('mountains');
    Route::get('/routes', \App\Livewire\Admin\RoutesCrud::class)->name('routes');
    Route::get('/criteria', \App\Livewire\Admin\CriteriaCrud::class)->name('criteria');
    Route::get('/criterion-weights', \App\Livewire\Admin\CriterionWeightsCrud::class)->name('criterion-weights');
    Route::get('/category-maps', \App\Livewire\Admin\CategoryMapsCrud::class)->name('categorymaps');
    Route::get('/fuzzy-terms', \App\Livewire\Admin\FuzzyTermsCrud::class)->name('fuzzy-terms');

    // Import functionality
    Route::post('/routes/import', [\App\Http\Controllers\Admin\ImportController::class,'routes'])->name('routes.import');

    // Monitoring assessments
    Route::get('/assessments', \App\Livewire\Admin\AssessmentsMonitor::class)->name('assessments');
});

// User Panel Routes
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function(){
    Route::get('/history', \App\Livewire\User\AssessmentHistory::class)->name('history');
    Route::get('/assessment/{id}/detail', \App\Livewire\User\AssessmentDetail::class)->name('assessment.detail');
});
