{{-- resources/views/livewire/assessment/steps-viewer.blade.php --}}
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button href="{{ route('assess.result', $assessmentId) }}" text="Kembali ke Hasil" />
    </div>
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-neutral-text">Tahapan Perhitungan</h1>
            <p class="text-sm text-neutral-sub">Assessment #{{ $assessmentId }} — Method: <span class="font-semibold">{{ strtoupper($activeMethod) }}</span> — Step: <span class="font-semibold">{{ $activeStep }}</span></p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('assess.export', [$assessmentId, $activeStep, 'csv']) }}"
               class="px-3 py-1.5 rounded-lg border border-neutral-line text-sm text-neutral-text hover:bg-white transition-colors">Export CSV</a>
            <a href="{{ route('assess.export', [$assessmentId, $activeStep, 'xlsx']) }}"
               class="px-3 py-1.5 rounded-lg border border-neutral-line text-sm text-neutral-text hover:bg-white transition-colors">Export XLSX</a>
            <a href="{{ route('assess.export', [$assessmentId, $activeStep, 'pdf']) }}"
               class="px-3 py-1.5 rounded-lg border border-neutral-line text-sm text-neutral-text hover:bg-white transition-colors">Export PDF</a>
        </div>
    </div>

    {{-- Method Selection --}}
    <div class="mt-6">
        <div class="flex border-b border-neutral-line">
            <button wire:click="switchMethod('topsis')" 
                    class="px-6 py-3 text-sm font-medium transition-colors {{ $activeMethod === 'topsis' ? 'border-b-2 border-brand text-brand' : 'text-neutral-sub hover:text-neutral-text' }}">
                📊 TOPSIS Steps
            </button>
            <button wire:click="switchMethod('vikor')" 
                    class="px-6 py-3 text-sm font-medium transition-colors {{ $activeMethod === 'vikor' ? 'border-b-2 border-brand text-brand' : 'text-neutral-sub hover:text-neutral-text' }}">
                📈 VIKOR Steps
            </button>
        </div>
    </div>

    {{-- Step Tabs --}}
    <div class="mt-6 overflow-x-auto">
        <div class="inline-flex border border-neutral-line rounded-xl bg-white shadow-soft">
            @foreach (($activeMethod === 'topsis' ? $topsisSteps : $vikorSteps) as $s)
                <button wire:click="loadStep('{{ $s }}')"
                    class="px-3 py-2 text-sm border-r border-neutral-line last:border-r-0 {{ $activeStep===$s ? 'bg-brand text-neutral-text' : 'text-neutral-sub hover:bg-white' }}">
                    {{ str_replace(['VIKOR_', 'MATRIX_X'], ['', 'MATRIX_X'], $s) }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Content --}}
    <div class="mt-6 bg-white border border-neutral-line rounded-2xl shadow-soft p-4">
        @if (empty($data))
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📊</div>
                <h3 class="text-lg font-semibold text-neutral-text mb-2">Belum Ada Data Perhitungan</h3>
                <p class="text-neutral-sub mb-6">
                    Assessment ini belum dijalankan perhitungan TOPSIS. Silakan jalankan perhitungan terlebih dahulu untuk melihat tahapan perhitungan.
                </p>
                <div class="flex gap-3 justify-center">
                    <a href="{{ route('assess.wizard', $assessmentId) }}" 
                       class="px-4 py-2 rounded-lg border border-neutral-line text-sm text-neutral-text hover:bg-white transition-colors">
                        📝 Edit Kuesioner
                    </a>
                    <a href="{{ route('assess.result', $assessmentId) }}" 
                       class="px-4 py-2 rounded-lg bg-brand text-neutral-text hover:bg-indigo-700 transition-colors">
                        🚀 Jalankan Perhitungan
                    </a>
                </div>
            </div>
        @elseif ($activeStep === 'MATRIX_X' || $activeStep === 'VIKOR_MATRIX_X')
            @php $rows = $data['rows'] ?? []; $cols = $data['cols'] ?? []; $X = $data['X'] ?? []; @endphp
            <div class="text-sm text-gray-600 mb-3">Decision Matrix (X)</div>
            <div class="overflow-auto">
                <table class="min-w-full text-xs">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="px-2 py-2 sticky left-0 bg-gray-50">Alternatif</th>
                            @foreach ($cols as $c)
                                <th class="px-2 py-2">{{ $c }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $i => $name)
                            <tr class="border-t">
                                <td class="px-2 py-1 sticky left-0 bg-white font-medium">{{ $name }}</td>
                                @foreach ($cols as $j => $c)
                                    <td class="px-2 py-1 tabular-nums">{{ is_numeric($X[$i][$j] ?? null) ? number_format($X[$i][$j], 4) : '-' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif ($activeStep === 'NORMALIZED_R')
            @php $rows = $data['rows'] ?? []; $cols = $data['cols'] ?? []; $R = $data['R'] ?? []; @endphp
            <div class="text-sm text-gray-600 mb-3">Normalized Matrix (R)</div>
            <div class="overflow-auto">
                <table class="min-w-full text-xs">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="px-2 py-2 sticky left-0 bg-gray-50">Alternatif</th>
                            @foreach ($cols as $c)
                                <th class="px-2 py-2">{{ $c }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $i => $name)
                            <tr class="border-t">
                                <td class="px-2 py-1 sticky left-0 bg-white font-medium">{{ $name }}</td>
                                @foreach ($cols as $j => $c)
                                    <td class="px-2 py-1 tabular-nums">{{ number_format($R[$i][$j] ?? 0, 6) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif ($activeStep === 'WEIGHTED_Y')
            @php $rows = $data['rows'] ?? []; $cols = $data['cols'] ?? []; $Y = $data['Y'] ?? []; $W = $data['weights'] ?? []; @endphp
            <div class="text-sm text-gray-600 mb-3">Weighted Matrix (Y)</div>
            <div class="mb-2 text-xs text-gray-500">Bobot:
                @foreach ($W as $code => $w) <span class="mr-2">{{ $code }}={{ number_format($w,4) }}</span> @endforeach
            </div>
            <div class="overflow-auto">
                <table class="min-w-full text-xs">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="px-2 py-2 sticky left-0 bg-gray-50">Alternatif</th>
                            @foreach ($cols as $c)
                                <th class="px-2 py-2">{{ $c }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $i => $name)
                            <tr class="border-t">
                                <td class="px-2 py-1 sticky left-0 bg-white font-medium">{{ $name }}</td>
                                @foreach ($cols as $j => $c)
                                    <td class="px-2 py-1 tabular-nums">{{ number_format($Y[$i][$j] ?? 0, 6) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif ($activeStep === 'IDEAL_SOLUTION')
            @php $cols = $data['cols'] ?? []; $Ap = $data['A_plus'] ?? []; $Am = $data['A_minus'] ?? []; $types = $data['types'] ?? []; @endphp
            <div class="text-sm text-gray-600 mb-3">Ideal Solutions</div>
            <div class="overflow-auto">
                <table class="min-w-full text-xs">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="px-2 py-2">Kriteria</th>
                            <th class="px-2 py-2">Type</th>
                            <th class="px-2 py-2">A⁺</th>
                            <th class="px-2 py-2">A⁻</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cols as $j => $c)
                            <tr class="border-t">
                                <td class="px-2 py-1">{{ $c }}</td>
                                <td class="px-2 py-1">{{ $types[$c] ?? '-' }}</td>
                                <td class="px-2 py-1 tabular-nums">{{ number_format($Ap[$j] ?? 0, 6) }}</td>
                                <td class="px-2 py-1 tabular-nums">{{ number_format($Am[$j] ?? 0, 6) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif ($activeStep === 'DISTANCES')
            @php $rows = $data['rows'] ?? []; $Sp = $data['S_plus'] ?? []; $Sm = $data['S_minus'] ?? []; @endphp
            <div class="text-sm text-gray-600 mb-3">Distances to Ideals</div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="px-2 py-2">Alternatif</th>
                            <th class="px-2 py-2">S⁺</th>
                            <th class="px-2 py-2">S⁻</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $i => $name)
                            <tr class="border-t">
                                <td class="px-2 py-1 font-medium">{{ $name }}</td>
                                <td class="px-2 py-1 tabular-nums">{{ number_format($Sp[$i] ?? 0, 6) }}</td>
                                <td class="px-2 py-1 tabular-nums">{{ number_format($Sm[$i] ?? 0, 6) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif ($activeStep === 'CLOSENESS_COEFF')
            @php $rows = $data['rows'] ?? []; $CC = $data['CC'] ?? []; @endphp
            <div class="text-sm text-gray-600 mb-3">Closeness Coefficient</div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs">
                    <thead><tr class="text-left bg-gray-50"><th class="px-2 py-2">Alternatif</th><th class="px-2 py-2">CC</th></tr></thead>
                    <tbody>
                        @foreach ($rows as $i => $name)
                            <tr class="border-t">
                                <td class="px-2 py-1 font-medium">{{ $name }}</td>
                                <td class="px-2 py-1 font-semibold tabular-nums">{{ number_format($CC[$i] ?? 0, 6) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif ($activeStep === 'RANKING')
            @php $rank = $data['ranking'] ?? []; $CC = $data['CC'] ?? []; $mat = \App\Models\AssessmentStep::where('assessment_id',$assessmentId)->where('step','MATRIX_X')->first();
                $rows = $mat ? (json_decode($mat->payload,true)['rows'] ?? []) : []; @endphp
            <div class="text-sm text-gray-600 mb-3">Final Ranking</div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs">
                    <thead><tr class="text-left bg-gray-50"><th class="px-2 py-2">#</th><th class="px-2 py-2">Alternatif</th><th class="px-2 py-2">CC</th></tr></thead>
                    <tbody>
                        @foreach ($rank as $k => $idx)
                            <tr class="border-t">
                                <td class="px-2 py-1">{{ $k+1 }}</td>
                                <td class="px-2 py-1 font-medium">{{ $rows[$idx] ?? ('Route #'.$idx) }}</td>
                                <td class="px-2 py-1 font-semibold">{{ number_format($CC[$idx] ?? 0, 6) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif ($activeStep === 'VIKOR_BEST_WORST')
            @php $cols = $data['cols'] ?? []; $F_star = $data['F_star'] ?? []; $F_minus = $data['F_minus'] ?? []; $types = $data['types'] ?? []; @endphp
            <div class="text-sm text-gray-600 mb-3">Best & Worst Values (F* & F-)</div>
            <div class="overflow-auto">
                <table class="min-w-full text-xs">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="px-2 py-2">Kriteria</th>
                            <th class="px-2 py-2">Type</th>
                            <th class="px-2 py-2">F* (Best)</th>
                            <th class="px-2 py-2">F- (Worst)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cols as $j => $c)
                            <tr class="border-t">
                                <td class="px-2 py-1">{{ $c }}</td>
                                <td class="px-2 py-1">{{ $types[$c] ?? '-' }}</td>
                                <td class="px-2 py-1 tabular-nums">{{ number_format($F_star[$j] ?? 0, 6) }}</td>
                                <td class="px-2 py-1 tabular-nums">{{ number_format($F_minus[$j] ?? 0, 6) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif ($activeStep === 'VIKOR_S_R')
            @php $rows = $data['rows'] ?? []; $S = $data['S'] ?? []; $R = $data['R'] ?? []; $weights = $data['weights'] ?? []; @endphp
            <div class="text-sm text-gray-600 mb-3">S (Utility) & R (Regret) Values</div>
            @if (!empty($weights))
                <div class="mb-2 text-xs text-gray-500">Bobot:
                    @foreach ($weights as $idx => $w) <span class="mr-2">C{{ $idx + 1 }}={{ number_format($w,4) }}</span> @endforeach
                </div>
            @endif
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="px-2 py-2">Alternatif</th>
                            <th class="px-2 py-2">S (Utility)</th>
                            <th class="px-2 py-2">R (Regret)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $i => $name)
                            <tr class="border-t">
                                <td class="px-2 py-1 font-medium">{{ $name }}</td>
                                <td class="px-2 py-1 tabular-nums">{{ number_format($S[$i] ?? 0, 6) }}</td>
                                <td class="px-2 py-1 tabular-nums">{{ number_format($R[$i] ?? 0, 6) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif ($activeStep === 'VIKOR_Q')
            @php $rows = $data['rows'] ?? []; $Q = $data['Q'] ?? []; $v = $data['v'] ?? 0.5; @endphp
            <div class="text-sm text-gray-600 mb-3">VIKOR Index (Q) - v = {{ $v }}</div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs">
                    <thead><tr class="text-left bg-gray-50"><th class="px-2 py-2">Alternatif</th><th class="px-2 py-2">Q</th></tr></thead>
                    <tbody>
                        @foreach ($rows as $i => $name)
                            <tr class="border-t">
                                <td class="px-2 py-1 font-medium">{{ $name }}</td>
                                <td class="px-2 py-1 font-semibold tabular-nums">{{ number_format($Q[$i] ?? 0, 6) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif ($activeStep === 'VIKOR_RANKING')
            @php $rank = $data['ranking'] ?? []; $Q = $data['Q'] ?? []; $mat = \App\Models\AssessmentStep::where('assessment_id',$assessmentId)->where('step','VIKOR_MATRIX_X')->first();
                $rows = $mat ? (json_decode($mat->payload,true)['rows'] ?? []) : []; @endphp
            <div class="text-sm text-gray-600 mb-3">VIKOR Final Ranking</div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs">
                    <thead><tr class="text-left bg-gray-50"><th class="px-2 py-2">#</th><th class="px-2 py-2">Alternatif</th><th class="px-2 py-2">Q</th></tr></thead>
                    <tbody>
                        @foreach ($rank as $k => $idx)
                            <tr class="border-t">
                                <td class="px-2 py-1">{{ $k+1 }}</td>
                                <td class="px-2 py-1 font-medium">{{ $rows[$idx] ?? ('Route #'.$idx) }}</td>
                                <td class="px-2 py-1 font-semibold">{{ number_format($Q[$idx] ?? 0, 6) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>