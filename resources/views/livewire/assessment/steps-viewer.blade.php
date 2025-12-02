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
                    class="px-6 py-3 text-sm font-medium transition-colors border-b-2 border-brand text-brand">
                📊 TOPSIS Steps
            </button>
        </div>
    </div>

    {{-- Step Tabs --}}
    <div class="mt-6 overflow-x-auto">
        <div class="inline-flex border border-neutral-line rounded-xl bg-white shadow-soft">
            @foreach ($topsisSteps as $s)
                <button wire:click="loadStep('{{ $s }}')"
                    class="px-3 py-2 text-sm border-r border-neutral-line last:border-r-0 {{ $activeStep===$s ? 'bg-brand text-neutral-text' : 'text-neutral-sub hover:bg-white' }}">
                    {{ $s }}
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
        @elseif ($activeStep === 'FUZZY_PROCESSING')
            <div class="text-sm text-gray-600 mb-3">Fuzzy Processing Results</div>
            @php
                $fuzzySteps = \App\Models\AssessmentStep::where('assessment_id', $assessmentId)
                    ->where('step', 'FUZZY_PROCESSING')
                    ->get();
            @endphp
            
            @if($fuzzySteps->isEmpty())
                <div class="text-center py-8">
                    <div class="text-4xl mb-4">🔮</div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Tidak Ada Fuzzy Processing</h3>
                    <p class="text-gray-500">
                        Tidak ada kriteria yang menggunakan fuzzy processing atau fuzzy processing belum dijalankan.
                    </p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($fuzzySteps as $step)
                        @php
                            // Ensure payload is array (handle both casted and non-casted cases)
                            $stepData = is_string($step->payload)
                                ? json_decode($step->payload, true) ?? []
                                : $step->payload;

                            // Ensure nested arrays are decoded if still strings
                            if (isset($stepData['memberships']) && is_string($stepData['memberships'])) {
                                $stepData['memberships'] = json_decode($stepData['memberships'], true) ?? [];
                            }
                            if (isset($stepData['terms']) && is_string($stepData['terms'])) {
                                $stepData['terms'] = json_decode($stepData['terms'], true) ?? [];
                            }
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-3">
                                {{ $stepData['criterion_code'] }} - {{ $stepData['criterion_name'] }}
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="text-xs text-gray-500">Input Raw</label>
                                    <div class="text-sm font-mono">{{ $stepData['raw_input'] }}</div>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Nilai Defuzzifikasi</label>
                                    <div class="text-sm font-mono font-semibold">{{ number_format($stepData['defuzzified_value'], 4) }}</div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-xs text-gray-500 mb-2 block">Derajat Keanggotaan</label>
                                <div class="flex gap-4 text-sm">
                                    @foreach($stepData['memberships'] as $term => $membership)
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium">{{ $term }}:</span>
                                            <span class="font-mono">{{ number_format($membership, 3) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            @if(isset($stepData['terms']) && !empty($stepData['terms']))
                                <div>
                                    <label class="text-xs text-gray-500 mb-2 block">Fuzzy Terms</label>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                                        @foreach($stepData['terms'] as $term)
                                            <div class="border border-gray-100 rounded p-2 text-xs">
                                                <div class="font-medium">{{ $term['label'] }} ({{ $term['code'] }})</div>
                                                <div class="text-gray-500">{{ $term['shape'] }}</div>
                                                <div class="font-mono text-xs mt-1">
                                                    @php $params = is_string($term['params_json']) ? json_decode($term['params_json'], true) : $term['params_json']; @endphp
                                                    [{{ implode(', ', $params) }}]
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- SVG Visualization --}}
                                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                        <label class="text-xs text-gray-500 mb-2 block">Membership Functions Visualization</label>
                                        @php
                                            $rawInput = $stepData['raw_input'] ?? null;
                                            $defuzzValue = $stepData['defuzzified_value'] ?? null;
                                            $memberships = $stepData['memberships'] ?? [];

                                            // SVG dimensions
                                            $width = 400;
                                            $height = 200;
                                            $padding = 40;
                                            $chartWidth = $width - 2 * $padding;
                                            $chartHeight = $height - 2 * $padding;

                                            // Scale parameters (1-5 input range)
                                            $minX = 1;
                                            $maxX = 5;
                                            $scaleX = $chartWidth / ($maxX - $minX);

                                            // Colors for terms
                                            $colors = [
                                                'RENDAH' => '#ef4444',  // red
                                                'SEDANG' => '#3b82f6',  // blue
                                                'TINGGI' => '#10b981'   // green
                                            ];
                                        @endphp

                                        <svg width="{{ $width }}" height="{{ $height }}" class="border border-gray-300 bg-white rounded">
                                            {{-- Grid lines --}}
                                            @for($i = 1; $i <= 5; $i++)
                                                @php $x = $padding + ($i - $minX) * $scaleX; @endphp
                                                <line x1="{{ $x }}" y1="{{ $padding }}" x2="{{ $x }}" y2="{{ $height - $padding }}"
                                                      stroke="#e5e7eb" stroke-width="1" stroke-dasharray="2,2"/>
                                                <text x="{{ $x }}" y="{{ $height - $padding + 15 }}" text-anchor="middle"
                                                      class="text-xs fill-gray-500">{{ $i }}</text>
                                            @endfor

                                            {{-- Y-axis labels --}}
                                            @for($i = 0; $i <= 4; $i++)
                                                @php
                                                    $mu = $i / 4;
                                                    $y = $padding + $chartHeight * (1 - $mu);
                                                @endphp
                                                <line x1="{{ $padding - 5 }}" y1="{{ $y }}" x2="{{ $padding }}" y2="{{ $y }}"
                                                      stroke="#6b7280"/>
                                                <text x="{{ $padding - 10 }}" y="{{ $y + 3 }}" text-anchor="end"
                                                      class="text-xs fill-gray-500">{{ number_format($mu, 1) }}</text>
                                            @endfor

                                            {{-- Axes --}}
                                            <line x1="{{ $padding }}" y1="{{ $padding }}" x2="{{ $padding }}" y2="{{ $height - $padding }}"
                                                  stroke="#374151" stroke-width="2"/>
                                            <line x1="{{ $padding }}" y1="{{ $height - $padding }}" x2="{{ $width - $padding }}" y2="{{ $height - $padding }}"
                                                  stroke="#374151" stroke-width="2"/>

                                            {{-- Draw triangular membership functions --}}
                                            @foreach($stepData['terms'] as $term)
                                                @php
                                                    $params = is_string($term['params_json']) ? json_decode($term['params_json'], true) : $term['params_json'];
                                                    $a = $params[0]; // left
                                                    $b = $params[1]; // peak
                                                    $c = $params[2]; // right

                                                    $x1 = $padding + ($a - $minX) * $scaleX;
                                                    $x2 = $padding + ($b - $minX) * $scaleX;
                                                    $x3 = $padding + ($c - $minX) * $scaleX;

                                                    $y_bottom = $padding + $chartHeight * (1 - 0);
                                                    $y_top = $padding + $chartHeight * (1 - 1);

                                                    $color = $colors[$term['code']] ?? '#6b7280';
                                                    $membership = $memberships[$term['label']] ?? 0;
                                                @endphp

                                                {{-- Triangle path --}}
                                                <path d="M {{ $x1 }} {{ $y_bottom }} L {{ $x2 }} {{ $y_top }} L {{ $x3 }} {{ $y_bottom }} Z"
                                                      fill="{{ $color }}" fill-opacity="0.2" stroke="{{ $color }}" stroke-width="2"/>

                                                {{-- Membership badge near peak --}}
                                                @if($membership > 0)
                                                    <circle cx="{{ $x2 }}" cy="{{ $y_top - 10 }}" r="8"
                                                            fill="{{ $color }}" stroke="white" stroke-width="2"/>
                                                    <text x="{{ $x2 }}" y="{{ $y_top - 6 }}" text-anchor="middle"
                                                          class="text-xs fill-white font-bold">μ</text>
                                                    <text x="{{ $x2 }}" y="{{ $y_top - 25 }}" text-anchor="middle"
                                                          class="text-xs fill-gray-700 font-semibold">{{ number_format($membership, 2) }}</text>
                                                @endif

                                                {{-- Term label --}}
                                                <text x="{{ $x2 }}" y="{{ $y_bottom + 15 }}" text-anchor="middle"
                                                      class="text-xs font-medium" fill="{{ $color }}">{{ $term['label'] }}</text>
                                            @endforeach

                                            {{-- User input line --}}
                                            @if($rawInput !== null && is_numeric($rawInput))
                                                @php $inputX = $padding + ($rawInput - $minX) * $scaleX; @endphp
                                                <line x1="{{ $inputX }}" y1="{{ $padding }}" x2="{{ $inputX }}" y2="{{ $height - $padding }}"
                                                      stroke="#dc2626" stroke-width="3" stroke-dasharray="5,5"/>
                                                <text x="{{ $inputX }}" y="{{ $padding - 5 }}" text-anchor="middle"
                                                      class="text-xs font-bold fill-red-600">Input: {{ $rawInput }}</text>
                                            @endif

                                            {{-- Defuzzified value line --}}
                                            @if($defuzzValue !== null && is_numeric($defuzzValue))
                                                @php $defuzzX = $padding + ($defuzzValue - $minX) * $scaleX; @endphp
                                                <line x1="{{ $defuzzX }}" y1="{{ $padding }}" x2="{{ $defuzzX }}" y2="{{ $height - $padding }}"
                                                      stroke="#059669" stroke-width="3"/>
                                                <text x="{{ $defuzzX }}" y="{{ $padding - 5 }}" text-anchor="middle"
                                                      class="text-xs font-bold fill-emerald-600">Defuzz: {{ number_format($defuzzValue, 2) }}</text>
                                            @endif

                                            {{-- Axis labels --}}
                                            <text x="{{ $width / 2 }}" y="{{ $height - 5 }}" text-anchor="middle"
                                                  class="text-sm font-medium fill-gray-700">Input Value</text>
                                            <text x="15" y="{{ $height / 2 }}" text-anchor="middle" transform="rotate(-90, 15, {{ $height / 2 }})"
                                                  class="text-sm font-medium fill-gray-700">Membership Degree</text>
                                        </svg>

                                        {{-- Legend --}}
                                        <div class="flex items-center gap-6 mt-3 text-xs">
                                            <div class="flex items-center gap-2">
                                                <div class="w-4 h-0.5 bg-red-600" style="border-top: 3px dashed #dc2626;"></div>
                                                <span>Input User</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="w-4 h-0.5 bg-emerald-600"></div>
                                                <span>Nilai Defuzzifikasi</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="w-3 h-3 bg-blue-500 rounded-full flex items-center justify-center">
                                                    <span class="text-white text-xs">μ</span>
                                                </div>
                                                <span>Derajat Keanggotaan Aktif</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        @elseif ($activeStep === 'MATRIX_X')
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
        @endif
    </div>
</div>