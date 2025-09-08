{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-white border border-neutral-line border border-neutral-line-neutral-line">
    <!-- Hero Section -->
    <div class="bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line-b-2 border border-neutral-line-neutral-line shadow-soft">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <!-- Back Button -->
            <div class="mb-6">
                <x-back-button href="{{ route('landing') }}" text="Kembali ke Beranda" />
            </div>
            
            <div class="text-center mb-8">
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-16 bg-brand-500 rounded border border-neutral-line-2xl flex items-center justify-center text-2xl text-neutral-text">
                        🔧
                    </div>
                </div>
                
                <h1 class="text-3xl font-semibold font-bold text-neutral-text mb-4">
                    Admin Dashboard
                </h1>
                <p class="text-lg text-neutral-sub mb-4 ">
                    Manage your SPK-TOPSIS system
                </p>
                <p class="text-neutral-sub max-w-2xl mx-auto ">
                    Welcome back, <span class="text-brand font-semibold">{{ auth()->user()->name }}</span>! 
                    Ready to manage the system?
                </p>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Monitoring -->
            <a href="{{ route('admin.assessments') }}" class="bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-neutral-line rounded border border-neutral-line-xl p-6 shadow-soft hover:shadow-soft transition-all duration-200 hover:border border-neutral-line-brand-300 text-center group">
                <div class="text-3xl mb-4 group-hover:scale-110 transition-transform duration-300">📊</div>
                <h3 class="text-lg font-semibold text-neutral-text mb-2">Monitoring</h3>
                <p class="text-neutral-sub mb-4 text-sm">Track all assessments and their results</p>
                <div class="text-brand font-semibold text-sm">View Assessments →</div>
            </a>

            <!-- Master Data -->
            <a href="{{ route('admin.routes') }}" class="bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-neutral-line rounded border border-neutral-line-xl p-6 shadow-soft hover:shadow-soft transition-all duration-200 hover:border border-neutral-line-brand-300 text-center group">
                <div class="text-3xl mb-4 group-hover:scale-110 transition-transform duration-300">🏔️</div>
                <h3 class="text-lg font-semibold text-neutral-text mb-2">Master Data</h3>
                <p class="text-neutral-sub mb-4 text-sm">Manage mountains, routes, and criteria</p>
                <div class="text-brand font-semibold text-sm">Manage Routes →</div>
            </a>

            <!-- Configuration -->
            <a href="{{ route('admin.criteria') }}" class="bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-neutral-line rounded border border-neutral-line-xl p-6 shadow-soft hover:shadow-soft transition-all duration-200 hover:border border-neutral-line-brand-300 text-center group">
                <div class="text-3xl mb-4 group-hover:scale-110 transition-transform duration-300">⚙️</div>
                <h3 class="text-lg font-semibold text-neutral-text mb-2">Criteria</h3>
                <p class="text-neutral-sub mb-4 text-sm">Manage criteria definitions</p>
                <div class="text-brand font-semibold text-sm">Manage Criteria →</div>
            </a>

            <!-- Criterion Weights -->
            <a href="{{ route('admin.criterion-weights') }}" class="bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-neutral-line rounded border border-neutral-line-xl p-6 shadow-soft hover:shadow-soft transition-all duration-200 hover:border border-neutral-line-brand-300 text-center group">
                <div class="text-3xl mb-4 group-hover:scale-110 transition-transform duration-300">⚖️</div>
                <h3 class="text-lg font-semibold text-neutral-text mb-2">Criterion Weights</h3>
                <p class="text-neutral-sub mb-4 text-sm">Set weights for TOPSIS calculation</p>
                <div class="text-brand font-semibold text-sm">Manage Weights →</div>
            </a>

            <!-- Mountains -->
            <a href="{{ route('admin.mountains') }}" class="bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-neutral-line rounded border border-neutral-line-xl p-6 shadow-soft hover:shadow-soft transition-all duration-200 hover:border border-neutral-line-brand-300 text-center group">
                <div class="text-3xl mb-4 group-hover:scale-110 transition-transform duration-300">⛰️</div>
                <h3 class="text-lg font-semibold text-neutral-text mb-2">Mountains</h3>
                <p class="text-neutral-sub mb-4 text-sm">Manage mountain database</p>
                <div class="text-brand font-semibold text-sm">Manage Mountains →</div>
            </a>


            <!-- Constraints -->
            <a href="{{ route('admin.constraints') }}" class="bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-neutral-line rounded border border-neutral-line-xl p-6 shadow-soft hover:shadow-soft transition-all duration-200 hover:border border-neutral-line-brand-300 text-center group">
                <div class="text-3xl mb-4 group-hover:scale-110 transition-transform duration-300">🛡️</div>
                <h3 class="text-lg font-semibold text-neutral-text mb-2">Constraints</h3>
                <p class="text-neutral-sub mb-4 text-sm">Setup safety and filtering rules</p>
                <div class="text-brand font-semibold text-sm">Manage Constraints →</div>
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="mt-12">
            <h2 class="text-2xl font-semibold font-bold text-neutral-text text-center mb-8">
                Quick Stats
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-neutral-line rounded border border-neutral-line-xl p-6 shadow-soft text-center">
                    <div class="text-3xl mb-2">🏔️</div>
                    <div class="text-2xl font-bold text-brand tabular-nums">{{ \App\Models\Mountain::count() }}</div>
                    <div class="text-sm text-neutral-sub">Mountains</div>
                </div>
                <div class="bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-neutral-line rounded border border-neutral-line-xl p-6 shadow-soft text-center">
                    <div class="text-3xl mb-2">🛣️</div>
                    <div class="text-2xl font-bold text-brand tabular-nums">{{ \App\Models\Route::count() }}</div>
                    <div class="text-sm text-neutral-sub">Routes</div>
                </div>
                <div class="bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-neutral-line rounded border border-neutral-line-xl p-6 shadow-soft text-center">
                    <div class="text-3xl mb-2">📊</div>
                    <div class="text-2xl font-bold text-brand tabular-nums">{{ \App\Models\Assessment::count() }}</div>
                    <div class="text-sm text-neutral-sub">Assessments</div>
                </div>
                <div class="bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-neutral-line rounded border border-neutral-line-xl p-6 shadow-soft text-center">
                    <div class="text-3xl mb-2">👥</div>
                    <div class="text-2xl font-bold text-brand tabular-nums">{{ \App\Models\User::count() }}</div>
                    <div class="text-sm text-neutral-sub">Users</div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-12">
            <h2 class="text-2xl font-semibold font-bold text-neutral-text text-center mb-8">
                Recent Activity
            </h2>
            <div class="bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-neutral-line rounded border border-neutral-line-xl p-6 shadow-soft">
                <div class="space-y-4">
                    @php
                        $recentAssessments = \App\Models\Assessment::with('user')->latest()->take(5)->get();
                    @endphp
                    
                    @forelse($recentAssessments as $assessment)
                        <div class="flex items-center justify-between p-4 bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-neutral-line rounded border border-neutral-line-lg border border-neutral-line border border-neutral-line-neutral-line">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-brand-500 rounded border border-neutral-line-full flex items-center justify-center text-sm font-bold text-neutral-text">
                                    {{ substr($assessment->user->name ?? 'Guest', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-neutral-text font-semibold">{{ $assessment->title }}</div>
                                    <div class="text-sm text-neutral-sub">
                                        by {{ $assessment->user->name ?? 'Guest' }} • {{ $assessment->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-sm text-neutral-sub">
                                {{ $assessment->status }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-neutral-sub">
                            <div class="text-3xl mb-4">📭</div>
                            <p>No assessments yet. Time to get started!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
