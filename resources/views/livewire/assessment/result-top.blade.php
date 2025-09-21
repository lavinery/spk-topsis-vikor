{{-- resources/views/livewire/assessment/result-top.blade.php --}}
<div class="max-w-5xl mx-auto px-4 py-8" x-data="{ activeTab: 'topsis' }">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button href="{{ route('landing') }}" text="Kembali ke Beranda" />
    </div>
    
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text">Hasil Rekomendasi — Top {{ count($top) }}</h1>
                <p class="text-sm text-neutral-sub">Menampilkan {{ count($top) }} rute paling cocok berdasarkan perhitungan TOPSIS.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('assess.wizard', $assessment->id) }}" 
                   class="px-4 py-2 rounded-lg border border-neutral-line text-sm text-neutral-text hover:bg-white transition-colors">
                    📝 Edit Kuesioner
                </a>
                <button onclick="submitRunBothCalculation({{ $assessment->id }}, 0.5)" 
                        class="px-4 py-2 rounded-lg bg-brand text-neutral-text hover:bg-indigo-700 transition-colors">
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
                📊 TOPSIS Results
            </button>
        </div>
    </div>

    {{-- TOPSIS Results --}}
    <div>
        {{-- Kartu Top-5 TOPSIS --}}
        <div class="grid grid-cols-1 gap-4">
        @foreach ($top as $idx => $item)
            <div class="rounded-xl border border-neutral-line p-4 bg-white shadow-soft">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <div class="text-base font-semibold text-neutral-text">{{ $item['name'] }}</div>
                        <div class="mt-1 text-xs text-neutral-sub">Closeness Coefficient (CC)</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-brand">{{ number_format($item['cc'], 4) }}</div>
                        <div class="text-xs text-neutral-sub">Peringkat #{{ $idx+1 }}</div>
                    </div>
                </div>

                {{-- bar visual --}}
                <div class="mt-3 h-2 bg-neutral-line rounded-full overflow-hidden">
                    @php $w = max(0, min(100, $item['cc']*100)); @endphp
                    <div class="h-full bg-brand rounded-full" style="width: {{ $w }}%"></div>
                </div>

                {{-- Explainability singkat --}}
                @php $exp = $explain[$item['i']] ?? ['pro'=>[],'con'=>[]]; @endphp
                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <div class="text-xs font-medium text-neutral-text">Kontributor Positif (top)</div>
                        <ul class="mt-1 text-xs text-neutral-sub list-disc list-inside">
                            @foreach (array_slice($exp['pro'] ?? [], 0, 3, true) as $code => $val)
                                <li>{{ $code }} <span class="text-neutral-sub">({{ number_format($val,4) }})</span></li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-neutral-text">Kontributor Negatif (top)</div>
                        <ul class="mt-1 text-xs text-neutral-sub list-disc list-inside">
                            @foreach (array_slice($exp['con'] ?? [], 0, 3, true) as $code => $val)
                                <li>{{ $code }} <span class="text-neutral-sub">({{ number_format($val,4) }})</span></li>
                            @endforeach
                        </ul>
                    </div>
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
    <div class="mt-8 flex gap-4">
        <form method="POST" action="{{ route('assess.run', $assessmentId) }}" class="inline">
            @csrf
            <button class="inline-flex items-center px-4 py-2 rounded-xl bg-indigo-600 text-neutral-text hover:bg-indigo-700">
                🔁 Hitung Ulang
            </button>
        </form>
        
        <a href="{{ route('assess.steps', $assessmentId) }}" 
           class="inline-flex items-center px-4 py-2 rounded-xl bg-brand text-white hover:opacity-90">
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
        const response = await fetch(`/assessments/${assessmentId}/run-both`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `_token=${encodeURIComponent(csrfToken.getAttribute('content'))}&v=${encodeURIComponent(v)}`
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