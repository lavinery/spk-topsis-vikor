{{-- Route Detail Page - Inspired by muncak.id --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Route Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Back Button -->
            <div class="mb-6">
                <x-back-button href="{{ route('landing') }}" text="Kembali ke Beranda" />
            </div>
            
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-4">
                        <x-level-badge :level="$level" size="lg" />
                        <x-status-pill :status="$status" size="lg" />
                    </div>
                    
                    <h1 class="text-3xl font-display font-bold text-gray-900 mb-2">{{ $route->name }}</h1>
                    <div class="flex items-center gap-2 text-gray-600 mb-4">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ $route->mountain->name }}, {{ $route->mountain->province }}</span>
                    </div>
                    
                    <!-- Quick Metrics -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-lg font-bold text-gray-900 tabular-nums">{{ $route->distance_km }} km</div>
                            <div class="text-xs text-gray-500">Jarak</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-lg font-bold text-gray-900 tabular-nums">{{ round($route->distance_km / 3, 1) }} jam</div>
                            <div class="text-xs text-gray-500">Estimasi</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-lg font-bold text-gray-900 tabular-nums">{{ $route->elevation_gain_m }} m</div>
                            <div class="text-xs text-gray-500">Elevasi</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-lg font-bold text-gray-900 tabular-nums">{{ $route->mountain->elevation_m }} m</div>
                            <div class="text-xs text-gray-500">Puncak</div>
                        </div>
                    </div>
                </div>
                
                <div class="ml-8">
                    <button class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2 transition-colors duration-200">
                        <span>🎯</span>
                        <span>Hitung Kecocokan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Weather Forecast -->
                <div>
                    <h2 class="text-xl font-display font-bold text-gray-900 mb-4">Prakiraan Cuaca</h2>
                    <x-weather-tile :location="$route->mountain->name" />
                </div>

                <!-- Route Information -->
                <div>
                    <h2 class="text-xl font-display font-bold text-gray-900 mb-4">Informasi Jalur</h2>
                    <div class="bg-white rounded-xl border-2 border-gray-200 p-6 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-3">Detail Teknis</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Kelas Kemiringan:</span>
                                        <span class="font-medium">{{ $route->slope_class ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tutupan Lahan:</span>
                                        <span class="font-medium">{{ $route->land_cover_key ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Sumber Air:</span>
                                        <span class="font-medium">{{ $route->water_sources_score ?? 'N/A' }}/10</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Fasilitas:</span>
                                        <span class="font-medium">{{ $route->support_facility_score ?? 'N/A' }}/10</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-3">Persyaratan</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center gap-2">
                                        @if($route->permit_required)
                                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-amber-700">Perlu Izin</span>
                                        @else
                                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-green-700">Tidak Perlu Izin</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-display font-bold text-gray-900">Ulasan Pendaki</h2>
                        <button class="text-brand-600 hover:text-brand-700 text-sm font-medium">Tulis Ulasan</button>
                    </div>
                    
                    <div class="bg-white rounded-xl border-2 border-gray-200 p-6 shadow-sm">
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-4">⭐</div>
                            <p class="text-gray-500 mb-4">Belum ada ulasan untuk jalur ini</p>
                            <button class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                Jadilah yang pertama mengulas
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Similar Routes -->
                <div>
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Jalur Serupa</h3>
                    <div class="space-y-4">
                        @php
                            $similarRoutes = \App\Models\Route::with('mountain')
                                ->where('mountain_id', '!=', $route->mountain_id)
                                ->where('mountain_id', $route->mountain_id)
                                ->take(3)
                                ->get();
                        @endphp
                        
                        @forelse($similarRoutes as $similarRoute)
                            <x-route-card :route="$similarRoute" :url="'#'" :compact="true" />
                        @empty
                            <div class="text-center py-4 text-gray-500 text-sm">
                                Tidak ada jalur serupa
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Safety Tips -->
                <div>
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Tips Keselamatan</h3>
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-sm">
                                <p class="font-medium text-amber-800 mb-2">Perhatikan kondisi cuaca</p>
                                <p class="text-amber-700">Selalu cek prakiraan cuaca sebelum berangkat dan siapkan perlengkapan sesuai kondisi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
