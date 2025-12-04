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

            <!-- Action Button -->
            <div class="text-center">
                <button onclick="openMountainModal()"
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

    <!-- How It Works -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Cara Kerja Sistem</h2>
                <p class="text-gray-600">3 langkah mudah untuk mendapatkan rekomendasi jalur terbaik</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <span class="text-2xl font-bold text-blue-600">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3 text-center">Pilih Gunung</h3>
                    <p class="text-gray-600 text-center">Pilih gunung yang ingin Anda daki dari daftar gunung yang tersedia</p>
                </div>

                <!-- Step 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <span class="text-2xl font-bold text-indigo-600">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3 text-center">Isi Assessment</h3>
                    <p class="text-gray-600 text-center">Jawab 14 pertanyaan tentang kemampuan dan preferensi pendakian Anda</p>
                </div>

                <!-- Step 3 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <span class="text-2xl font-bold text-emerald-600">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3 text-center">Dapatkan Rekomendasi</h3>
                    <p class="text-gray-600 text-center">Sistem akan merekomendasikan jalur terbaik sesuai profil Anda</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
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

    <!-- Criteria Overview -->
    <section class="py-16 bg-gray-50">
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
            <div class="text-center">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center mr-3">
                        <span class="text-lg">🏔️</span>
                    </div>
                    <span class="text-xl font-bold">SPK Pendakian</span>
                </div>
                <p class="text-gray-300 mb-4 max-w-2xl mx-auto">
                    Sistem Pendukung Keputusan untuk rekomendasi jalur pendakian yang aman dan sesuai kemampuan Anda.
                </p>
                <p class="text-sm text-gray-400">
                    &copy; {{ date('Y') }} SPK Pendakian - Powered by Laravel & TOPSIS Algorithm
                </p>
            </div>
        </div>
    </footer>

    <!-- Modal Pemilihan Gunung -->
    <div id="mountain-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" onclick="closeMountainModal()"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-3xl mr-3">🏔️</span>
                            <div>
                                <div class="flex items-center gap-3">
                                    <h3 class="text-xl font-bold text-white">Pilih Gunung untuk Pendakian</h3>
                                    <span id="selection-counter" class="hidden px-3 py-1 bg-white/20 rounded-full text-sm font-semibold text-white">
                                        0 gunung terpilih
                                    </span>
                                </div>
                                <p class="text-sm text-blue-100">Pilih satu atau lebih gunung untuk mendapatkan rekomendasi jalur terbaik</p>
                            </div>
                        </div>
                        <button onclick="closeMountainModal()" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="bg-white px-6 py-4">
                    <div class="mb-4">
                        <input type="text" id="search-mountain" placeholder="🔍 Cari gunung..."
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all"
                               onkeyup="filterMountains()">
                    </div>

                    <div id="mountains-list" class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto pr-2">
                        <!-- Will be populated by JavaScript -->
                    </div>

                    <div id="no-selection-error" class="hidden mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <p class="text-sm text-red-600 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293z" clip-rule="evenodd"/>
                            </svg>
                            Silakan pilih minimal satu gunung untuk memulai assessment
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                    <button onclick="submitMountainSelection()"
                            class="flex-1 sm:flex-none inline-flex justify-center items-center rounded-xl border border-transparent shadow-sm px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-base font-semibold text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none transition-all">
                        <span class="mr-2">🎯</span>
                        <span>Mulai Assessment</span>
                    </button>
                    <button onclick="closeMountainModal()"
                            class="flex-1 sm:flex-none inline-flex justify-center rounded-xl border-2 border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition-all">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for creating assessment -->
    <form method="POST" action="{{ route('landing.start') }}" id="assessment-form" style="display: none;">
        @csrf
        <input type="text" name="title" value="{{ old('title', 'Assessment ' . now()->format('Y-m-d H:i')) }}" style="display: none;">
        <input type="number" name="top_k" value="5" style="display: none;">
        <input type="hidden" name="mountain_ids" id="selected-mountain-ids" value="">

        @foreach($userCriteria as $criterion)
            <input type="hidden" name="{{ $criterion->code }}" value="{{ $criterion->default_value ?? '3' }}">
        @endforeach

        <button type="submit" style="display: none;">Submit</button>
    </form>

    <script>
        let selectedMountainIds = new Set();
        let mountainsData = @json($mountains);

        function openMountainModal() {
            document.getElementById('mountain-modal').classList.remove('hidden');
            renderMountainsList();
        }

        function closeMountainModal() {
            document.getElementById('mountain-modal').classList.add('hidden');
            document.getElementById('no-selection-error').classList.add('hidden');
        }

        function renderMountainsList(filter = '') {
            const container = document.getElementById('mountains-list');
            const filtered = mountainsData.filter(m =>
                m.name.toLowerCase().includes(filter.toLowerCase()) ||
                m.province.toLowerCase().includes(filter.toLowerCase())
            );

            if (filtered.length === 0) {
                container.innerHTML = '<div class="col-span-2 text-center py-8 text-gray-500">Tidak ada gunung ditemukan</div>';
                return;
            }

            container.innerHTML = filtered.map(mountain => {
                const isSelected = selectedMountainIds.has(mountain.id);
                return `
                <div class="border-2 rounded-xl p-4 transition-all duration-200 ${isSelected ? 'border-blue-500 bg-blue-50' : 'border-gray-200'}">
                    <div class="flex items-start gap-3 mb-3">
                        <input type="checkbox"
                               id="mountain-${mountain.id}"
                               class="mt-1 w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer"
                               ${isSelected ? 'checked' : ''}
                               onchange="toggleMountain(${mountain.id})">
                        <div class="flex-1">
                            <label for="mountain-${mountain.id}" class="cursor-pointer">
                                <h4 class="font-semibold text-gray-900 text-base mb-1">${mountain.name}</h4>
                                <p class="text-xs text-gray-500">${mountain.province}</p>
                            </label>
                        </div>
                    </div>
                    <div class="ml-8">
                        <div class="grid grid-cols-2 gap-2 text-xs mb-2">
                            <div class="flex items-center gap-1 text-gray-600">
                                <span>⛰️</span>
                                <span>${new Intl.NumberFormat('id-ID').format(mountain.elevation)} mdpl</span>
                            </div>
                            <div class="flex items-center gap-1 text-gray-600">
                                <span>🛤️</span>
                                <span>${mountain.route_count} jalur</span>
                            </div>
                        </div>
                        ${mountain.routes && mountain.routes.length > 0 ? `
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-xs font-semibold text-gray-700 mb-2">Jalur Tersedia:</p>
                                <div class="space-y-1">
                                    ${mountain.routes.map(route => `
                                        <div class="flex items-center justify-between text-xs text-gray-600 py-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-gray-400">→</span>
                                                <span class="font-medium">${route.name}</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="px-2 py-0.5 rounded-full text-xs ${
                                                    route.difficulty === 'Pemula' ? 'bg-green-100 text-green-700' :
                                                    route.difficulty === 'Menengah' ? 'bg-yellow-100 text-yellow-700' :
                                                    'bg-red-100 text-red-700'
                                                }">${route.difficulty}</span>
                                                <span>${route.distance_km} km</span>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        ` : ''}
                        ${mountain.status === 'open' ? `
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                    ✓ Dibuka
                                </span>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `}).join('');

            // Update selection counter
            updateSelectionCounter();
        }

        function toggleMountain(mountainId) {
            if (selectedMountainIds.has(mountainId)) {
                selectedMountainIds.delete(mountainId);
            } else {
                selectedMountainIds.add(mountainId);
            }

            // Re-render to update UI
            const searchValue = document.getElementById('search-mountain').value;
            renderMountainsList(searchValue);

            // Hide error message
            document.getElementById('no-selection-error').classList.add('hidden');

            console.log('Selected mountains:', Array.from(selectedMountainIds));
        }

        function updateSelectionCounter() {
            const counter = document.getElementById('selection-counter');
            if (counter) {
                const count = selectedMountainIds.size;
                if (count > 0) {
                    counter.textContent = `${count} gunung terpilih`;
                    counter.classList.remove('hidden');
                } else {
                    counter.classList.add('hidden');
                }
            }
        }

        function filterMountains() {
            const search = document.getElementById('search-mountain').value;
            renderMountainsList(search);
        }

        function submitMountainSelection() {
            if (selectedMountainIds.size === 0) {
                document.getElementById('no-selection-error').classList.remove('hidden');
                return;
            }

            // Set mountain_ids in form (comma-separated)
            document.getElementById('selected-mountain-ids').value = Array.from(selectedMountainIds).join(',');

            // Hide modal and show loading
            closeMountainModal();
            const startBtn = document.getElementById('start-assessment-btn');
            const loadingBtn = document.getElementById('loading-btn');
            startBtn.style.display = 'none';
            loadingBtn.style.display = 'inline-flex';

            // Submit form
            setTimeout(() => {
                document.getElementById('assessment-form').submit();
            }, 500);
        }

        // Reset button states on page show
        window.addEventListener('pageshow', function() {
            const startBtn = document.getElementById('start-assessment-btn');
            const loadingBtn = document.getElementById('loading-btn');
            startBtn.style.display = 'inline-flex';
            loadingBtn.style.display = 'none';
        });

        // Handle deep linking from external sources (e.g., muncak.id)
        window.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const mountainId = urlParams.get('mountain_id');
            const mountainIds = urlParams.get('mountain_ids'); // Support multiple IDs

            if (mountainIds) {
                // Multiple mountains from URL
                const ids = mountainIds.split(',').map(id => parseInt(id.trim())).filter(id => !isNaN(id));
                ids.forEach(id => {
                    const mountain = mountainsData.find(m => m.id === id);
                    if (mountain) {
                        selectedMountainIds.add(id);
                    }
                });
                if (selectedMountainIds.size > 0) {
                    openMountainModal();
                }
            } else if (mountainId) {
                // Single mountain from URL (backward compatibility)
                const id = parseInt(mountainId);
                const mountain = mountainsData.find(m => m.id === id);
                if (mountain) {
                    selectedMountainIds.add(id);
                    openMountainModal();
                }
            }
        });
    </script>
</div>
@endsection
