{{-- resources/views/livewire/assessment/result-top.blade.php --}}
<div class="max-w-5xl mx-auto px-4 py-8" x-data="{ activeTab: 'topsis' }">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button href="{{ route('landing') }}" text="Kembali ke Beranda" />
    </div>
    
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-neutral-text">Hasil Rekomendasi — Top {{ count($top) }}</h1>
                <p class="text-sm text-neutral-sub">Menampilkan {{ count($top) }} rute paling cocok berdasarkan perhitungan TOPSIS.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('assess.wizard', $assessment->id) }}"
                   class="px-4 py-2 rounded-lg border border-neutral-line text-sm text-neutral-text hover:bg-white transition-colors text-center">
                    📝 Edit Kuesioner
                </a>
                <button onclick="submitTopsisCalculation({{ $assessment->id }})"
                        class="px-4 py-2 rounded-lg bg-brand text-white hover:bg-indigo-700 transition-colors text-sm">
                    🔄 Hitung Ulang
                </button>
            </div>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="mb-6">
        <div class="flex border-b border-neutral-line">
            <button @click="activeTab = 'topsis'"
                    :class="activeTab === 'topsis' ? 'border-b-2 border-brand text-brand' : 'text-neutral-sub hover:text-neutral-text'"
                    class="px-6 py-3 text-sm font-medium transition-colors">
                📊 Hasil TOPSIS
            </button>
        </div>
    </div>

    {{-- Assessment Summary --}}
    <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">Ringkasan Assessment</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <div class="text-blue-700 font-medium">Algoritma Digunakan</div>
                        <div class="text-blue-600">TOPSIS dengan Fuzzy Logic</div>
                    </div>
                    <div>
                        <div class="text-blue-700 font-medium">Kriteria Evaluasi</div>
                        <div class="text-blue-600">{{ $totalCriteria }} Kriteria ({{ $userCriteria }} User + {{ $systemCriteria }} Sistem)</div>
                    </div>
                    <div>
                        <div class="text-blue-700 font-medium">Jalur Tersedia</div>
                        <div class="text-blue-600">{{ count($all) }} jalur pendakian</div>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-blue-100 rounded-lg">
                    <div class="text-xs text-blue-800">
                        <strong>💡 Interpretasi Hasil:</strong> Semakin tinggi nilai Closeness Coefficient (CC), semakin sesuai jalur tersebut dengan profil dan kemampuan Anda.
                        Jalur dengan CC ≥ 0.6 sangat direkomendasikan untuk Anda.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TOPSIS Results --}}
    <div>
        {{-- Kartu Top-5 TOPSIS --}}
        <div class="grid grid-cols-1 gap-6">
        @foreach ($top as $idx => $item)
            <div class="rounded-2xl border p-6 bg-white shadow-lg hover:shadow-xl transition-all duration-300 relative overflow-hidden
                      {{ $idx === 0 ? 'border-yellow-400 bg-gradient-to-br from-yellow-50 to-white' :
                         ($idx === 1 ? 'border-gray-400 bg-gradient-to-br from-gray-50 to-white' :
                         ($idx === 2 ? 'border-orange-400 bg-gradient-to-br from-orange-50 to-white' : 'border-neutral-line')) }}">

                {{-- Ranking badge --}}
                <div class="absolute top-4 right-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg
                              {{ $idx === 0 ? 'bg-yellow-500' :
                                 ($idx === 1 ? 'bg-gray-500' :
                                 ($idx === 2 ? 'bg-orange-500' : 'bg-brand')) }}">
                        #{{ $idx+1 }}
                    </div>
                </div>

                <div class="flex items-start justify-between gap-4 pr-16">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            {{-- Trophy icon for top 3 --}}
                            @if($idx === 0)
                                <span class="text-2xl">🏆</span>
                            @elseif($idx === 1)
                                <span class="text-2xl">🥈</span>
                            @elseif($idx === 2)
                                <span class="text-2xl">🥉</span>
                            @else
                                <span class="text-xl">🏔️</span>
                            @endif
                            <div>
                                <div class="text-xl font-bold text-neutral-text">{{ $item['name'] }}</div>
                                <div class="text-sm text-neutral-sub">Jalur Pendakian Rekomendasi</div>
                            </div>
                        </div>

                        {{-- Score metrics --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div class="bg-white/70 rounded-lg p-3 border border-neutral-line/50">
                                <div class="text-xs text-neutral-sub">Closeness Coefficient</div>
                                <div class="text-lg font-bold text-brand">{{ number_format($item['cc'], 4) }}</div>
                                <div class="text-xs text-neutral-sub">Skor Kesesuaian</div>
                            </div>
                            <div class="bg-white/70 rounded-lg p-3 border border-neutral-line/50">
                                <div class="text-xs text-neutral-sub">Tingkat Rekomendasi</div>
                                <div class="text-lg font-bold {{ $idx < 3 ? 'text-green-600' : 'text-blue-600' }}">
                                    {{ $item['cc'] >= 0.8 ? 'Sangat Cocok' :
                                       ($item['cc'] >= 0.6 ? 'Cocok' :
                                       ($item['cc'] >= 0.4 ? 'Cukup Cocok' : 'Kurang Cocok')) }}
                                </div>
                                <div class="text-xs text-neutral-sub">Berdasarkan Profil Anda</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Enhanced progress bar --}}
                <div class="mt-4">
                    <div class="flex items-center justify-between text-xs text-neutral-sub mb-2">
                        <span>Kesesuaian</span>
                        <span>{{ number_format($item['cc'] * 100, 1) }}%</span>
                    </div>
                    <div class="h-3 bg-neutral-line rounded-full overflow-hidden">
                        @php $w = max(0, min(100, $item['cc']*100)); @endphp
                        <div class="h-full bg-gradient-to-r from-brand to-indigo-600 rounded-full transition-all duration-1000 ease-out"
                             style="width: {{ $w }}%"></div>
                    </div>
                </div>

                {{-- Explainability with better design --}}
                @php $exp = $explain[$item['i']] ?? ['pro'=>[],'con'=>[]]; @endphp
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm font-semibold text-green-800">Kelebihan Utama</div>
                        </div>
                        <div class="space-y-2">
                            @foreach (array_slice($exp['pro'] ?? [], 0, 3, true) as $code => $val)
                                @php
                                    // Calculate percentage based on max value in the array
                                    $maxVal = max(array_values($exp['pro'] ?? [1]));
                                    $percentage = $maxVal > 0 ? ($val / $maxVal) * 100 : 0;
                                @endphp
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-green-700 font-medium">{{ $code }}</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 h-2.5 bg-green-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-green-500 rounded-full transition-all" style="width: {{ min(100, $percentage) }}%"></div>
                                        </div>
                                        <span class="text-xs text-green-600 font-mono w-16 text-right">{{ $val >= 0.001 ? number_format($val, 4) : number_format($val, 6) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.802-.833-2.572 0L3.262 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div class="text-sm font-semibold text-red-800">Perhatian Khusus</div>
                        </div>
                        <div class="space-y-2">
                            @foreach (array_slice($exp['con'] ?? [], 0, 3, true) as $code => $val)
                                @php
                                    // Calculate percentage based on max value in the array
                                    $maxVal = max(array_values($exp['con'] ?? [1]));
                                    $percentage = $maxVal > 0 ? ($val / $maxVal) * 100 : 0;
                                @endphp
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-red-700 font-medium">{{ $code }}</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 h-2.5 bg-red-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-red-500 rounded-full transition-all" style="width: {{ min(100, $percentage) }}%"></div>
                                        </div>
                                        <span class="text-xs text-red-600 font-mono w-16 text-right">{{ $val >= 0.001 ? number_format($val, 4) : number_format($val, 6) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <a href="{{ route('assess.steps', $assessment->id) }}"
                       class="inline-flex items-center justify-center px-4 py-2 bg-brand text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Lihat Detail Perhitungan
                    </a>
                    <button class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        Bagikan Hasil
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    {{-- (Opsional) Tampilkan semua buat admin --}}
    @if(count($all) > count($top))
        <div class="mt-8">
            <details class="rounded-xl border border-neutral-line bg-white p-4 open:ring-1 open:ring-indigo-200">
                <summary class="cursor-pointer font-medium text-neutral-text">Lihat semua peringkat ({{ count($all) }} total)</summary>
                <div class="mt-3">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-neutral-sub">
                                    <th class="py-2 pr-4">#</th>
                                    <th class="py-2 pr-4">Rute</th>
                                    <th class="py-2">CC</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all as $i => $r)
                                    <tr class="border-t">
                                        <td class="py-2 pr-4 text-neutral-sub">{{ $i+1 }}</td>
                                        <td class="py-2 pr-4">{{ $r['name'] }}</td>
                                        <td class="py-2 font-semibold">{{ number_format($r['cc'],4) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </details>
        </div>
    @endif
    </div>


    {{-- Action buttons --}}
    <div class="mt-8 flex flex-col sm:flex-row gap-4">
        <form method="POST" action="{{ route('assess.run', $assessmentId) }}" class="w-full sm:w-auto">
            @csrf
            <button class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                🔁 Hitung Ulang
            </button>
        </form>

        <a href="{{ route('assess.steps', $assessmentId) }}"
           class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 rounded-xl bg-brand text-white hover:opacity-90">
            📊 Lihat Tahapan
        </a>
    </div>
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('run-topsis-calculation', (data) => {
        submitTopsisCalculation(data.assessmentId);
    });
});

async function submitTopsisCalculation(assessmentId) {
    console.log('Submitting TOPSIS calculation:', { assessmentId });
    
    try {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found!');
            return;
        }
        
        // Use fetch API to submit POST request
        const response = await fetch(`/assessments/${assessmentId}/run`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `_token=${encodeURIComponent(csrfToken.getAttribute('content'))}`
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success && data.redirect_url) {
                window.location.href = data.redirect_url;
            } else {
                // Fallback: redirect to result page
                window.location.href = `/assessments/${assessmentId}/result`;
            }
        } else {
            console.error('Error submitting form:', response.status, response.statusText);
            alert('Terjadi kesalahan saat menjalankan perhitungan. Silakan coba lagi.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menjalankan perhitungan. Silakan coba lagi.');
    }
}
</script>