{{-- resources/views/landing.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-neutral-bg">
    <!-- Hero Section -->
    <section class="bg-white">
        <div class="max-w-7xl mx-auto px-4 py-10 text-center">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-brand rounded-2xl flex items-center justify-center text-2xl text-neutral-text">
                    🏔️
                </div>
            </div>
            
            <h1 class="text-3xl font-bold text-neutral-text mb-4">
                SPK-TOPSIS
            </h1>
            <p class="mt-2 text-sm text-neutral-sub">
                Sistem Pendukung Keputusan
            </p>
                
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="text-center p-4 rounded-lg bg-white border border-neutral-line shadow-soft">
                    <div class="text-2xl font-bold text-brand tabular-nums">{{ $mountainsCount }}</div>
                    <div class="text-sm text-neutral-sub">Gunung</div>
                </div>
                <div class="text-center p-4 rounded-lg bg-white border border-neutral-line shadow-soft">
                    <div class="text-2xl font-bold text-brand tabular-nums">{{ $routesCount }}</div>
                    <div class="text-sm text-neutral-sub">Jalur</div>
                </div>
                <div class="text-center p-4 rounded-lg bg-white border border-neutral-line shadow-soft">
                    <div class="text-2xl font-bold text-brand tabular-nums">{{ $assessmentsCount }}</div>
                    <div class="text-sm text-neutral-sub">Assessment</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="bg-white border-t border-neutral-line">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-neutral-text mb-4">Fitur Utama</h2>
                <p class="text-sm text-neutral-sub">Teknologi algoritma canggih untuk rekomendasi jalur terbaik</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- TOPSIS Feature -->
                <div class="text-center p-6 rounded-xl bg-white border border-neutral-line shadow-soft hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">📊</span>
                    </div>
                    <h3 class="text-lg font-semibold text-neutral-text mb-3">TOPSIS Algorithm</h3>
                    <p class="text-sm text-neutral-sub">Algoritma pengambilan keputusan untuk seleksi jalur optimal berdasarkan jarak ideal positif dan negatif</p>
                </div>
                
                
                <!-- Smart Assessment Feature -->
                <div class="text-center p-6 rounded-xl bg-white border border-neutral-line shadow-soft hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🎯</span>
                    </div>
                    <h3 class="text-lg font-semibold text-neutral-text mb-3">Smart Assessment</h3>
                    <p class="text-sm text-neutral-sub">Evaluasi 21 kriteria untuk rekomendasi jalur yang sesuai dengan kemampuan dan preferensi Anda</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Routes -->
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-neutral-text mb-2">Jalur Populer</h2>
            <p class="text-sm text-neutral-sub">Jalur-jalur yang sering dipilih pendaki</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            
            @forelse($popularRoutes as $route)
                <div class="bg-white border border-neutral-line rounded-xl p-6 shadow-soft hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-neutral-text mb-1">{{ $route['name'] }}</h3>
                            <p class="text-sm text-neutral-sub">{{ $route['province'] }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-brand/10 text-brand">
                            {{ $route['difficulty'] }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-neutral-sub">📏</span>
                            <span class="text-neutral-text">{{ $route['distance'] }} km</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-neutral-sub">⏱️</span>
                            <span class="text-neutral-text">{{ $route['duration'] }} jam</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-neutral-sub">⛰️</span>
                            <span class="text-neutral-text">{{ $route['elevation'] }} m</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-neutral-sub">⭐</span>
                            <span class="text-neutral-text">{{ $route['rating'] }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-neutral-line flex items-center justify-between">
                        <span class="text-xs text-neutral-sub">{{ $route['status'] }} · diperbarui {{ $route['updated'] }}</span>
                        <a href="{{ route('assess.start') }}" class="text-xs text-brand hover:text-indigo-700 font-medium">
                            Mulai Assessment →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10">
                    <div class="text-neutral-sub text-3xl mb-4">🏔️</div>
                    <p class="text-neutral-sub">Belum ada jalur yang tersedia</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Algorithm Comparison Section -->
    <section class="bg-neutral-bg">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-neutral-text mb-4">Perbandingan Algoritma</h2>
                <p class="text-sm text-neutral-sub">Dua metode canggih untuk analisis multi-kriteria</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- TOPSIS Card -->
                <div class="bg-white border border-neutral-line rounded-xl p-8 shadow-soft">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-xl">📊</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-neutral-text">TOPSIS</h3>
                            <p class="text-sm text-neutral-sub">Technique for Order Preference by Similarity to Ideal Solution</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span class="text-blue-500 mr-3">•</span>
                            <p class="text-sm text-neutral-text">Mencari alternatif terdekat dengan solusi ideal positif</p>
                        </div>
                        <div class="flex items-start">
                            <span class="text-blue-500 mr-3">•</span>
                            <p class="text-sm text-neutral-text">Menghitung jarak Euclidean dari ideal solution</p>
                        </div>
                        <div class="flex items-start">
                            <span class="text-blue-500 mr-3">•</span>
                            <p class="text-sm text-neutral-text">Cocok untuk ranking berdasarkan closeness coefficient</p>
                        </div>
                        <div class="flex items-start">
                            <span class="text-blue-500 mr-3">•</span>
                            <p class="text-sm text-neutral-text">Hasil: Nilai CC (0-1), semakin tinggi semakin baik</p>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="mt-8 text-center">
                <div class="bg-white border border-neutral-line rounded-xl p-6 shadow-soft">
                    <h4 class="text-lg font-semibold text-neutral-text mb-3">Algoritma TOPSIS</h4>
                    <p class="text-sm text-neutral-sub">Sistem ini menggunakan algoritma TOPSIS untuk memberikan rekomendasi yang komprehensif berdasarkan kedekatan dengan solusi ideal positif dan negatif.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Criteria Section -->
    <section class="bg-white">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-neutral-text mb-4">21 Kriteria Evaluasi</h2>
                <p class="text-sm text-neutral-sub">Sistem komprehensif untuk menilai kemampuan dan preferensi pendaki</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- User Criteria (C1-C14) -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-blue-600 font-bold">👤</span>
                        </div>
                        <h3 class="text-lg font-semibold text-blue-800">Kriteria User (C1-C14)</h3>
                    </div>
                    <div class="space-y-2 text-sm text-blue-700">
                        <p>• Usia & Kebugaran Fisik</p>
                        <p>• Pengalaman & Kepercayaan Diri</p>
                        <p>• Peralatan & Pengetahuan P3K</p>
                        <p>• Motivasi & Perencanaan</p>
                        <p>• Kesiapan Tim & Pemandu</p>
                    </div>
                </div>
                
                <!-- Mountain Criteria (C15-C18) -->
                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-green-600 font-bold">🏔️</span>
                        </div>
                        <h3 class="text-lg font-semibold text-green-800">Kriteria Gunung (C15-C18)</h3>
                    </div>
                    <div class="space-y-2 text-sm text-green-700">
                        <p>• Ketinggian & Elevasi</p>
                        <p>• Slope Class & Kesulitan</p>
                        <p>• Cuaca & Kondisi</p>
                        <p>• Akses & Lokasi</p>
                    </div>
                </div>
                
                <!-- Route Criteria (C19-C21) -->
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-purple-600 font-bold">🛤️</span>
                        </div>
                        <h3 class="text-lg font-semibold text-purple-800">Kriteria Jalur (C19-C21)</h3>
                    </div>
                    <div class="space-y-2 text-sm text-purple-700">
                        <p>• Jarak & Durasi</p>
                        <p>• Sumber Air & Fasilitas</p>
                        <p>• Tutupan Lahan</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <div class="bg-neutral-bg border border-neutral-line rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-neutral-text mb-3">Penilaian Komprehensif</h4>
                    <p class="text-sm text-neutral-sub">Setiap kriteria dinilai dengan skala 1-5 dan diberi bobot yang dapat disesuaikan oleh admin. Sistem ini memastikan rekomendasi yang akurat dan sesuai dengan kemampuan serta preferensi individual pendaki.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Assessment CTA -->
    <div class="bg-white border-t border-neutral-line">
        <div class="max-w-4xl mx-auto px-4 py-12">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-neutral-text mb-4">
                    Mulai Assessment
                </h2>
                <p class="text-sm text-neutral-sub mb-8">
                    Isi profil pendakian Anda untuk mendapatkan rekomendasi jalur yang sesuai
                </p>
                
                <!-- Assessment Button -->
                <div class="text-center">
                    <button onclick="startAssessment()" 
                            class="inline-flex items-center px-8 py-4 rounded-xl bg-brand text-white hover:bg-indigo-700 transition-colors text-lg font-semibold shadow-lg hover:shadow-xl">
                        <span class="mr-3">🏔️</span>
                        <span>Mulai Assessment</span>
                    </button>
                    <p class="text-sm text-neutral-sub mt-4">
                        Assessment akan dibuka dalam popup yang mudah digunakan
                    </p>
                </div>
            </div>
            
            <!-- Hidden form for creating assessment -->
            <form method="POST" action="{{ route('landing.start') }}" id="assessment-form" style="display: none;">
                @csrf
                
                <!-- Title -->
                <input type="text" name="title" value="{{ old('title', 'Assessment ' . now()->format('Y-m-d H:i')) }}" style="display: none;">
                <input type="number" name="top_k" value="5" style="display: none;">
                
                <!-- Default values for all criteria -->
                @foreach($userCriteria as $criterion)
                    <input type="hidden" name="{{ $criterion->code }}" value="{{ $criterion->default_value ?? '3' }}">
                @endforeach
                
                <!-- Submit Button -->
                <button type="submit" style="display: none;">Submit</button>
            </form>
        </div>
    </div>
    
    <!-- User Wizard Component will be loaded when needed -->
    
    <script>
        function startAssessment() {
            // Get fresh CSRF token
            fetch('/csrf-token')
                .then(response => response.json())
                .then(data => {
                    // Update CSRF token in form
                    const csrfInput = document.querySelector('input[name="_token"]');
                    if (csrfInput) {
                        csrfInput.value = data.csrf_token;
                    }
                    
                    // Submit the form
                    document.getElementById('assessment-form').submit();
                })
                .catch(error => {
                    console.error('Error getting CSRF token:', error);
                    
                    // Fallback: use meta tag CSRF token
                    const metaToken = document.querySelector('meta[name="csrf-token"]');
                    if (metaToken) {
                        const csrfInput = document.querySelector('input[name="_token"]');
                        if (csrfInput) {
                            csrfInput.value = metaToken.getAttribute('content');
                        }
                    }
                    
                    // Submit the form
                    document.getElementById('assessment-form').submit();
                });
        }
    </script>
@endsection