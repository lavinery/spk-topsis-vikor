{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 text-white border-b-4 border-indigo-900">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <!-- Back Button -->
            <div class="mb-8">
                <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-indigo-100 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="font-medium">Kembali ke Beranda</span>
                </a>
            </div>

            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center text-4xl shadow-xl">
                        🔧
                    </div>
                </div>

                <h1 class="text-4xl font-bold mb-4">
                    Admin Dashboard
                </h1>
                <p class="text-xl text-indigo-100 mb-4">
                    Kelola Sistem SPK-TOPSIS Anda
                </p>
                <p class="text-indigo-100 max-w-2xl mx-auto">
                    Selamat datang kembali, <span class="text-white font-bold">{{ auth()->user()->name }}</span>! 🎉
                </p>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="max-w-7xl mx-auto px-4 py-10">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Menu Utama</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Monitoring -->
            <a href="{{ route('admin.assessments') }}" class="group relative bg-white border-2 border-gray-200 rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:border-indigo-500 hover:-translate-y-1">
                <div class="absolute top-6 right-6 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300">
                    📊
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 pr-16">Monitoring</h3>
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">Pantau semua assessment dan hasilnya secara real-time</p>
                <div class="flex items-center text-indigo-600 font-semibold text-sm group-hover:gap-2 transition-all">
                    <span>Lihat Assessment</span>
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <!-- Master Data -->
            <a href="{{ route('admin.routes') }}" class="group relative bg-white border-2 border-gray-200 rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:border-green-500 hover:-translate-y-1">
                <div class="absolute top-6 right-6 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300">
                    🏔️
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 pr-16">Data Jalur</h3>
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">Kelola data gunung, jalur pendakian, dan kriteria</p>
                <div class="flex items-center text-green-600 font-semibold text-sm group-hover:gap-2 transition-all">
                    <span>Kelola Jalur</span>
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <!-- Configuration -->
            <a href="{{ route('admin.criteria') }}" class="group relative bg-white border-2 border-gray-200 rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:border-purple-500 hover:-translate-y-1">
                <div class="absolute top-6 right-6 w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300">
                    ⚙️
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 pr-16">Kriteria</h3>
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">Atur definisi kriteria untuk perhitungan TOPSIS</p>
                <div class="flex items-center text-purple-600 font-semibold text-sm group-hover:gap-2 transition-all">
                    <span>Kelola Kriteria</span>
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <!-- Criterion Weights -->
            <a href="{{ route('admin.criterion-weights') }}" class="group relative bg-white border-2 border-gray-200 rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:border-amber-500 hover:-translate-y-1">
                <div class="absolute top-6 right-6 w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300">
                    ⚖️
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 pr-16">Bobot Kriteria</h3>
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">Tentukan bobot untuk perhitungan TOPSIS</p>
                <div class="flex items-center text-amber-600 font-semibold text-sm group-hover:gap-2 transition-all">
                    <span>Atur Bobot</span>
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <!-- Mountains -->
            <a href="{{ route('admin.mountains') }}" class="group relative bg-white border-2 border-gray-200 rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:border-teal-500 hover:-translate-y-1">
                <div class="absolute top-6 right-6 w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300">
                    ⛰️
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 pr-16">Data Gunung</h3>
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">Kelola database gunung yang tersedia</p>
                <div class="flex items-center text-teal-600 font-semibold text-sm group-hover:gap-2 transition-all">
                    <span>Kelola Gunung</span>
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
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
