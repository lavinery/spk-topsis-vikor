{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-white border-b-2 border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <!-- Back Button -->
            <div class="mb-6">
                <x-back-button href="{{ route('landing') }}" text="Kembali ke Beranda" />
            </div>

            <div class="text-center mb-8">
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center text-2xl">
                        🔧
                    </div>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    Admin Dashboard
                </h1>
                <p class="text-lg text-gray-600 mb-4">
                    Manage your SPK-TOPSIS system
                </p>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Welcome back, <span class="text-indigo-600 font-semibold">{{ auth()->user()->name }}</span>!
                    Ready to manage the system?
                </p>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Monitoring -->
            <a href="{{ route('admin.assessments') }}" class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200 hover:border-indigo-300 text-center group">
                <div class="text-3xl mb-4 group-hover:scale-110 transition-transform duration-300">📊</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Monitoring</h3>
                <p class="text-gray-600 mb-4 text-sm">Track all assessments and their results</p>
                <div class="text-indigo-600 font-semibold text-sm">View Assessments →</div>
            </a>

            <!-- Master Data -->
            <a href="{{ route('admin.routes') }}" class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200 hover:border-indigo-300 text-center group">
                <div class="text-3xl mb-4 group-hover:scale-110 transition-transform duration-300">🏔️</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Master Data</h3>
                <p class="text-gray-600 mb-4 text-sm">Manage mountains, routes, and criteria</p>
                <div class="text-indigo-600 font-semibold text-sm">Manage Routes →</div>
            </a>

            <!-- Configuration -->
            <a href="{{ route('admin.criteria') }}" class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200 hover:border-indigo-300 text-center group">
                <div class="text-3xl mb-4 group-hover:scale-110 transition-transform duration-300">⚙️</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Criteria</h3>
                <p class="text-gray-600 mb-4 text-sm">Manage criteria definitions</p>
                <div class="text-indigo-600 font-semibold text-sm">Manage Criteria →</div>
            </a>

            <!-- Criterion Weights -->
            <a href="{{ route('admin.criterion-weights') }}" class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200 hover:border-indigo-300 text-center group">
                <div class="text-3xl mb-4 group-hover:scale-110 transition-transform duration-300">⚖️</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Criterion Weights</h3>
                <p class="text-gray-600 mb-4 text-sm">Set weights for TOPSIS calculation</p>
                <div class="text-indigo-600 font-semibold text-sm">Manage Weights →</div>
            </a>

            <!-- Mountains -->
            <a href="{{ route('admin.mountains') }}" class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200 hover:border-indigo-300 text-center group">
                <div class="text-3xl mb-4 group-hover:scale-110 transition-transform duration-300">⛰️</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Mountains</h3>
                <p class="text-gray-600 mb-4 text-sm">Manage mountain database</p>
                <div class="text-indigo-600 font-semibold text-sm">Manage Mountains →</div>
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">
                Quick Stats
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm text-center">
                    <div class="text-3xl mb-2">🏔️</div>
                    <div class="text-2xl font-bold text-indigo-600 tabular-nums">{{ \App\Models\Mountain::count() }}</div>
                    <div class="text-sm text-gray-600">Mountains</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm text-center">
                    <div class="text-3xl mb-2">🛣️</div>
                    <div class="text-2xl font-bold text-indigo-600 tabular-nums">{{ \App\Models\Route::count() }}</div>
                    <div class="text-sm text-gray-600">Routes</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm text-center">
                    <div class="text-3xl mb-2">📊</div>
                    <div class="text-2xl font-bold text-indigo-600 tabular-nums">{{ \App\Models\Assessment::count() }}</div>
                    <div class="text-sm text-gray-600">Assessments</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm text-center">
                    <div class="text-3xl mb-2">👥</div>
                    <div class="text-2xl font-bold text-indigo-600 tabular-nums">{{ \App\Models\User::count() }}</div>
                    <div class="text-sm text-gray-600">Users</div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">
                Recent Activity
            </h2>
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="space-y-4">
                    @php
                        $recentAssessments = \App\Models\Assessment::with('user')->latest()->take(5)->get();
                    @endphp

                    @forelse($recentAssessments as $assessment)
                        <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-sm font-bold text-indigo-700">
                                    {{ substr($assessment->user->name ?? 'Guest', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-gray-900 font-semibold">{{ $assessment->title }}</div>
                                    <div class="text-sm text-gray-600">
                                        by {{ $assessment->user->name ?? 'Guest' }} • {{ $assessment->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ $assessment->status }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-600">
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
