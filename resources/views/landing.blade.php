{{-- resources/views/landing.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">

    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-indigo-600/5"></div>
        <div class="relative max-w-7xl mx-auto px-4 py-16 text-center">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl flex items-center justify-center shadow-xl">
                    <span class="text-3xl">🏔️</span>
                </div>
            </div>

            <!-- Hero Text -->
            <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-6">
                <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">SPK</span>
                <span class="text-gray-900">Pendakian</span>
            </h1>
            <p class="text-xl text-gray-600 mb-4 max-w-3xl mx-auto">
                Sistem Pendukung Keputusan untuk menemukan jalur pendakian yang sempurna berdasarkan kemampuan dan preferensi Anda
            </p>
            <p class="text-sm text-gray-500 mb-12">
                Menggunakan algoritma TOPSIS dengan 21 kriteria evaluasi komprehensif
            </p>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <div class="bg-white/80 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="text-3xl font-bold text-blue-600 mb-2">{{ $mountainsCount }}</div>
                    <div class="text-gray-600 font-medium">Gunung Terdaftar</div>
                </div>
                <div class="bg-white/80 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="text-3xl font-bold text-indigo-600 mb-2">{{ $routesCount }}</div>
                    <div class="text-gray-600 font-medium">Jalur Tersedia</div>
                </div>
                <div class="bg-white/80 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="text-3xl font-bold text-emerald-600 mb-2">{{ $assessmentsCount }}</div>
                    <div class="text-gray-600 font-medium">Assessment Selesai</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main CTA -->
    <section class="py-16 bg-white" id="start-assessment">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">
                    Mulai Penilaian Sekarang
                </h2>
                <p class="text-lg text-gray-600 mb-8">
                    Proses hanya 3-5 menit • Hasil langsung tersedia • Gratis 100%
                </p>
            </div>

            <!-- Mountain Selection -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl p-8 mb-8 border-2 border-blue-200">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                        <span class="text-white text-2xl">🏔️</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Pilih Gunung</h3>
                        <p class="text-sm text-gray-600">Pilih gunung yang ingin Anda daki untuk mendapatkan rekomendasi jalur terbaik</p>
                    </div>
                </div>

                <!-- Mountain Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto pr-2">
                    @foreach($mountains as $mountain)
                    <div class="mountain-card bg-white rounded-xl p-4 border-2 border-gray-200 hover:border-blue-500 cursor-pointer transition-all duration-200 hover:shadow-lg"
                         onclick="selectMountain({{ $mountain['id'] }}, '{{ $mountain['name'] }}')"
                         data-mountain-id="{{ $mountain['id'] }}">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 text-base mb-1">{{ $mountain['name'] }}</h4>
                                <p class="text-xs text-gray-500">{{ $mountain['province'] }}</p>
                            </div>
                            <div class="mountain-check hidden">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="flex items-center gap-1 text-gray-600">
                                <span>⛰️</span>
                                <span>{{ number_format($mountain['elevation']) }} mdpl</span>
                            </div>
                            <div class="flex items-center gap-1 text-gray-600">
                                <span>🛤️</span>
                                <span>{{ $mountain['route_count'] }} jalur</span>
                            </div>
                        </div>
                        @if($mountain['status'] === 'open')
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                ✓ Dibuka
                            </span>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <!-- Error message -->
                <div id="mountain-error" class="hidden mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-sm text-red-600 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293z" clip-rule="evenodd"/>
                        </svg>
                        Silakan pilih gunung terlebih dahulu
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center">
                <!-- Assessment Button -->
                <button onclick="startAssessment()"
                        id="start-assessment-btn"
                        class="inline-flex items-center px-10 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 text-lg font-semibold shadow-xl hover:shadow-2xl transform hover:scale-105 group">
                    <span class="mr-3 text-2xl">🎯</span>
                    <span>Mulai Assessment</span>
                    <svg class="ml-3 w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </button>

                <!-- Loading state -->
                <button id="loading-btn" style="display: none;"
                        class="inline-flex items-center px-10 py-4 rounded-2xl bg-gray-400 text-white text-lg font-semibold cursor-not-allowed">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Mempersiapkan Assessment...</span>
                </button>

                <div class="mt-6 text-sm text-gray-500 space-y-1">
                    <div>✓ Interface intuitif dan mudah digunakan</div>
                    <div>✓ Auto-save setiap langkah</div>
                    <div>✓ Hasil tersimpan dan dapat diakses kapan saja</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Fitur Unggulan</h2>
                <p class="text-gray-600">Teknologi canggih untuk rekomendasi jalur terbaik</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- TOPSIS Feature -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                        <span class="text-2xl">📊</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">TOPSIS Algorithm</h3>
                    <p class="text-gray-600">Algoritma pengambilan keputusan untuk seleksi jalur optimal berdasarkan jarak ideal positif dan negatif</p>
                </div>

                <!-- Smart Assessment Feature -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                        <span class="text-2xl">🎯</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Smart Assessment</h3>
                    <p class="text-gray-600">Evaluasi 21 kriteria untuk rekomendasi jalur yang sesuai dengan kemampuan dan preferensi Anda</p>
                </div>

                <!-- Safety Feature -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-6">
                        <span class="text-2xl">🛡️</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Safety First</h3>
                    <p class="text-gray-600">Sistem constraint otomatis memfilter jalur berisiko tinggi untuk menjamin keselamatan pendaki</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Cara Kerja Sistem</h2>
                <p class="text-gray-600">3 langkah mudah untuk mendapatkan rekomendasi jalur terbaik</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center relative">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-xl font-bold shadow-lg">1</div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Isi Assessment</h3>
                    <p class="text-gray-600">Jawab 14 pertanyaan tentang pengalaman, kondisi fisik, dan preferensi pendakian Anda</p>

                    <!-- Arrow for desktop -->
                    <div class="hidden md:block absolute top-8 left-full w-8 h-0.5 bg-gray-300 transform -translate-x-4">
                        <div class="absolute right-0 top-0 w-2 h-2 bg-gray-300 rounded-full transform translate-x-1 -translate-y-0.75"></div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="text-center relative">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-xl font-bold shadow-lg">2</div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Analisis TOPSIS</h3>
                    <p class="text-gray-600">Sistem menganalisis 21 kriteria menggunakan algoritma TOPSIS untuk mencocokkan profil Anda</p>

                    <!-- Arrow for desktop -->
                    <div class="hidden md:block absolute top-8 left-full w-8 h-0.5 bg-gray-300 transform -translate-x-4">
                        <div class="absolute right-0 top-0 w-2 h-2 bg-gray-300 rounded-full transform translate-x-1 -translate-y-0.75"></div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-xl font-bold shadow-lg">3</div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Dapatkan Rekomendasi</h3>
                    <p class="text-gray-600">Terima ranking jalur terbaik lengkap dengan analisis detail dan visualisasi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Routes -->
    @if($popularRoutes->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Jalur Populer</h2>
                <p class="text-gray-600">Jalur-jalur yang sering dipilih pendaki</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($popularRoutes as $route)
                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $route['name'] }}</h3>
                                <p class="text-sm text-gray-500">{{ $route['province'] }}</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                {{ $route['difficulty'] === 'Pemula' ? 'bg-green-100 text-green-800' :
                                   ($route['difficulty'] === 'Menengah' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $route['difficulty'] }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400">📏</span>
                                <span class="text-gray-700">{{ $route['distance'] }} km</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400">⏱️</span>
                                <span class="text-gray-700">{{ $route['duration'] }} jam</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400">⛰️</span>
                                <span class="text-gray-700">{{ $route['elevation'] }} m</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400">⭐</span>
                                <span class="text-gray-700">{{ $route['rating'] }}</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-xs text-gray-500">{{ $route['status'] }} · {{ $route['updated'] }}</span>
                            <button onclick="startAssessment()" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                Mulai Assessment →
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Criteria Overview -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">21 Kriteria Evaluasi</h2>
                <p class="text-gray-600">Sistem komprehensif untuk menilai kemampuan dan preferensi pendaki</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- User Criteria -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 border border-blue-200">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-white text-xl">👤</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-blue-900">Kriteria Pendaki</h3>
                            <p class="text-sm text-blue-700">C1-C14</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm text-blue-800">
                        <div>• Usia & Kebugaran Fisik</div>
                        <div>• Pengalaman & Kepercayaan Diri</div>
                        <div>• Peralatan & Pengetahuan P3K</div>
                        <div>• Motivasi & Perencanaan</div>
                        <div>• Kesiapan Tim & Pemandu</div>
                    </div>
                </div>

                <!-- Mountain Criteria -->
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl p-8 border border-emerald-200">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-white text-xl">🏔️</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-emerald-900">Kriteria Gunung</h3>
                            <p class="text-sm text-emerald-700">C15</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm text-emerald-800">
                        <div>• Ketinggian MDPL</div>
                        <div>• Status Gunung</div>
                        <div>• Kondisi Cuaca</div>
                        <div>• Aksesibilitas</div>
                    </div>
                </div>

                <!-- Route Criteria -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-8 border border-purple-200">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-white text-xl">🛤️</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-purple-900">Kriteria Jalur</h3>
                            <p class="text-sm text-purple-700">C16-C21</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm text-purple-800">
                        <div>• Jarak & Durasi Pendakian</div>
                        <div>• Tingkat Kesulitan Slope</div>
                        <div>• Sumber Air & Fasilitas</div>
                        <div>• Tutupan Lahan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center mr-3">
                            <span class="text-lg">🏔️</span>
                        </div>
                        <span class="text-xl font-bold">SPK Pendakian</span>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Sistem Pendukung Keputusan untuk rekomendasi jalur pendakian yang aman dan sesuai kemampuan Anda.
                    </p>
                    <p class="text-sm text-gray-400">
                        Powered by Laravel & TOPSIS Algorithm
                    </p>
                </div>

                <!-- Features -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Fitur</h4>
                    <div class="space-y-2 text-gray-300">
                        <div>🎯 Smart Assessment</div>
                        <div>📊 TOPSIS Algorithm</div>
                        <div>🛡️ Safety Constraints</div>
                        <div>⚡ Real-time Results</div>
                    </div>
                </div>

                <!-- Tech -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Teknologi</h4>
                    <div class="space-y-2 text-gray-300">
                        <div>🔹 21 Kriteria Evaluasi</div>
                        <div>🔹 Modern Interface</div>
                        <div>🔹 Auto-save Progress</div>
                        <div>🔹 Export Results</div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} SPK Pendakian - Sistem Pendukung Keputusan Rekomendasi Jalur Gunung</p>
            </div>
        </div>
    </footer>

    <!-- Hidden form for creating assessment -->
    <form method="POST" action="{{ route('landing.start') }}" id="assessment-form" style="display: none;">
        @csrf
        <input type="text" name="title" value="{{ old('title', 'Assessment ' . now()->format('Y-m-d H:i')) }}" style="display: none;">
        <input type="number" name="top_k" value="5" style="display: none;">
        <input type="hidden" name="mountain_id" id="selected-mountain-id" value="">

        @foreach($userCriteria as $criterion)
            <input type="hidden" name="{{ $criterion->code }}" value="{{ $criterion->default_value ?? '3' }}">
        @endforeach

        <button type="submit" style="display: none;">Submit</button>
    </form>

    <script>
        let selectedMountainId = null;
        let selectedMountainName = null;

        function selectMountain(mountainId, mountainName) {
            // Remove selection from all cards
            document.querySelectorAll('.mountain-card').forEach(card => {
                card.classList.remove('border-blue-500', 'bg-blue-50');
                card.classList.add('border-gray-200');
                card.querySelector('.mountain-check').classList.add('hidden');
            });

            // Add selection to clicked card
            const selectedCard = document.querySelector(`[data-mountain-id="${mountainId}"]`);
            if (selectedCard) {
                selectedCard.classList.remove('border-gray-200');
                selectedCard.classList.add('border-blue-500', 'bg-blue-50');
                selectedCard.querySelector('.mountain-check').classList.remove('hidden');
            }

            // Store selected mountain
            selectedMountainId = mountainId;
            selectedMountainName = mountainName;

            // Hide error message if shown
            document.getElementById('mountain-error').classList.add('hidden');

            console.log('Selected mountain:', mountainId, mountainName);
        }

        function startAssessment() {
            // Validate mountain selection
            if (!selectedMountainId) {
                const errorDiv = document.getElementById('mountain-error');
                errorDiv.classList.remove('hidden');

                // Scroll to error
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            // Set mountain_id in form
            document.getElementById('selected-mountain-id').value = selectedMountainId;

            const startBtn = document.getElementById('start-assessment-btn');
            const loadingBtn = document.getElementById('loading-btn');

            startBtn.style.display = 'none';
            loadingBtn.style.display = 'inline-flex';

            fetch('/csrf-token')
                .then(response => response.json())
                .then(data => {
                    const csrfInput = document.querySelector('input[name="_token"]');
                    if (csrfInput) {
                        csrfInput.value = data.csrf_token;
                    }

                    setTimeout(() => {
                        document.getElementById('assessment-form').submit();
                    }, 500);
                })
                .catch(error => {
                    console.error('Error getting CSRF token:', error);

                    const metaToken = document.querySelector('meta[name="csrf-token"]');
                    if (metaToken) {
                        const csrfInput = document.querySelector('input[name="_token"]');
                        if (csrfInput) {
                            csrfInput.value = metaToken.getAttribute('content');
                        }
                    }

                    setTimeout(() => {
                        document.getElementById('assessment-form').submit();
                    }, 500);
                });
        }

        window.addEventListener('pageshow', function() {
            const startBtn = document.getElementById('start-assessment-btn');
            const loadingBtn = document.getElementById('loading-btn');

            startBtn.style.display = 'inline-flex';
            loadingBtn.style.display = 'none';
        });

        // Check URL parameters for mountain_id (from external links)
        window.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const mountainId = urlParams.get('mountain_id');

            if (mountainId) {
                const mountainCard = document.querySelector(`[data-mountain-id="${mountainId}"]`);
                if (mountainCard) {
                    mountainCard.click();
                    // Scroll to assessment section
                    document.getElementById('start-assessment').scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    </script>
</div>
@endsection