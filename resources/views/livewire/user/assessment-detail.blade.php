<div class="min-h-screen bg-gray-50" x-data>
    <!-- Page Header with Back Button -->
    <div class="bg-white shadow-sm border-b border-neutral-line">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('user.history') }}" class="text-neutral-sub hover:text-neutral-text">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-brand">Detail Assessment</h1>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="bg-white rounded-xl border border-neutral-line shadow-sm p-6 mb-6">
                <h2 class="text-2xl font-bold text-neutral-text">{{ $assessment->title }}</h2>
                <div class="mt-2 flex items-center gap-4 text-sm text-neutral-sub">
                    <span>{{ $assessment->created_at->format('d M Y, H:i') }}</span>
                    <span>•</span>
                    <span class="px-2 py-0.5 rounded-full text-xs
                        @if($assessment->status === 'done') bg-green-100 text-green-700
                        @elseif($assessment->status === 'running') bg-blue-100 text-blue-700
                        @elseif($assessment->status === 'failed') bg-red-100 text-red-700
                        @else bg-gray-100 text-gray-700
                        @endif">
                        {{ ucfirst($assessment->status) }}
                    </span>
                    <span>•</span>
                    <span>{{ $assessment->n_criteria }} kriteria</span>
                    <span>•</span>
                    <span>{{ $assessment->n_alternatives }} alternatif</span>
                </div>
            </div>

            @if($assessment->status === 'done')
                <!-- Ranking Results -->
                <div class="bg-white rounded-xl border border-neutral-line shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-neutral-text mb-4">Hasil Ranking</h3>
                    @if(!empty($ranking) && !empty($ranking['ranking']))
                        <div class="space-y-3">
                            @foreach($ranking['ranking'] as $index => $altIndex)
                                @php
                                    $cc = $ranking['CC'][$altIndex] ?? 0;
                                    $name = $matrixX['rows'][$altIndex] ?? "Alternatif #{$altIndex}";
                                @endphp
                                <div class="flex items-center gap-4 p-4 border border-neutral-line rounded-lg">
                                    <div class="w-10 h-10 rounded-full bg-brand/10 flex items-center justify-center flex-shrink-0">
                                        <span class="font-bold text-brand">{{ $index + 1 }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-neutral-text">{{ $name }}</h4>
                                        <p class="text-xs text-neutral-sub">Closeness Coefficient: {{ number_format($cc, 4) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-brand h-2 rounded-full" style="width: {{ $cc * 100 }}%"></div>
                                        </div>
                                        <span class="text-xs text-neutral-sub">{{ number_format($cc * 100, 1) }}%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-neutral-sub text-sm">Tidak ada data ranking.</p>
                    @endif
                </div>

                <!-- Calculation Steps -->
                <div class="space-y-6">
                    <!-- Matrix X -->
                    @if(!empty($matrixX))
                        <div class="bg-white rounded-xl border border-neutral-line shadow-sm p-6" x-data="{ open: false }">
                            <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                                <h3 class="text-lg font-semibold text-neutral-text">1. Matriks Keputusan (X)</h3>
                                <svg class="w-5 h-5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div x-show="open" x-transition class="mt-4">
                                <p class="text-sm text-neutral-sub mb-4">Matriks nilai awal sebelum normalisasi.</p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-neutral-line">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 border border-neutral-line text-left font-medium">Alternatif</th>
                                                @foreach($matrixX['cols'] ?? [] as $col)
                                                    <th class="px-3 py-2 border border-neutral-line text-center font-medium">{{ $col }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($matrixX['X'] ?? [] as $rowIdx => $row)
                                                <tr>
                                                    <td class="px-3 py-2 border border-neutral-line font-medium">{{ $matrixX['rows'][$rowIdx] ?? "Row {$rowIdx}" }}</td>
                                                    @foreach($row as $val)
                                                        <td class="px-3 py-2 border border-neutral-line text-center">{{ number_format($val, 2) }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Normalized R -->
                    @if(!empty($normalizedR))
                        <div class="bg-white rounded-xl border border-neutral-line shadow-sm p-6" x-data="{ open: false }">
                            <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                                <h3 class="text-lg font-semibold text-neutral-text">2. Matriks Ternormalisasi (R)</h3>
                                <svg class="w-5 h-5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div x-show="open" x-transition class="mt-4">
                                <p class="text-sm text-neutral-sub mb-4">Normalisasi vektor menggunakan rumus: r_ij = x_ij / √(Σx²)</p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-neutral-line">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 border border-neutral-line text-left font-medium">Alternatif</th>
                                                @foreach($normalizedR['cols'] ?? [] as $col)
                                                    <th class="px-3 py-2 border border-neutral-line text-center font-medium">{{ $col }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($normalizedR['R'] ?? [] as $rowIdx => $row)
                                                <tr>
                                                    <td class="px-3 py-2 border border-neutral-line font-medium">{{ $normalizedR['rows'][$rowIdx] ?? "Row {$rowIdx}" }}</td>
                                                    @foreach($row as $val)
                                                        <td class="px-3 py-2 border border-neutral-line text-center">{{ number_format($val, 4) }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Weighted Y -->
                    @if(!empty($weightedY))
                        <div class="bg-white rounded-xl border border-neutral-line shadow-sm p-6" x-data="{ open: false }">
                            <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                                <h3 class="text-lg font-semibold text-neutral-text">3. Matriks Terbobot (Y)</h3>
                                <svg class="w-5 h-5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div x-show="open" x-transition class="mt-4">
                                <p class="text-sm text-neutral-sub mb-4">Matriks hasil perkalian normalisasi dengan bobot kriteria: y_ij = r_ij × w_j</p>

                                <!-- Weights -->
                                <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <h4 class="text-sm font-medium text-blue-900 mb-2">Bobot Kriteria:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($weightedY['weights'] ?? [] as $col => $weight)
                                            <span class="px-2 py-1 bg-white rounded text-xs border border-blue-300">
                                                <strong>{{ $col }}</strong>: {{ number_format($weight, 4) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-neutral-line">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 border border-neutral-line text-left font-medium">Alternatif</th>
                                                @foreach($weightedY['cols'] ?? [] as $col)
                                                    <th class="px-3 py-2 border border-neutral-line text-center font-medium">{{ $col }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($weightedY['Y'] ?? [] as $rowIdx => $row)
                                                <tr>
                                                    <td class="px-3 py-2 border border-neutral-line font-medium">{{ $weightedY['rows'][$rowIdx] ?? "Row {$rowIdx}" }}</td>
                                                    @foreach($row as $val)
                                                        <td class="px-3 py-2 border border-neutral-line text-center">{{ number_format($val, 4) }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Ideal Solution -->
                    @if(!empty($idealSolution))
                        <div class="bg-white rounded-xl border border-neutral-line shadow-sm p-6" x-data="{ open: false }">
                            <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                                <h3 class="text-lg font-semibold text-neutral-text">4. Solusi Ideal (A+ dan A-)</h3>
                                <svg class="w-5 h-5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div x-show="open" x-transition class="mt-4">
                                <p class="text-sm text-neutral-sub mb-4">A+ = solusi ideal positif, A- = solusi ideal negatif</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                                        <h4 class="text-sm font-medium text-green-900 mb-2">A+ (Solusi Ideal Positif)</h4>
                                        <div class="space-y-1">
                                            @foreach($idealSolution['A_plus'] ?? [] as $idx => $val)
                                                <div class="text-xs flex justify-between">
                                                    <span>{{ $idealSolution['cols'][$idx] ?? "C{$idx}" }}</span>
                                                    <strong>{{ number_format($val, 4) }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                                        <h4 class="text-sm font-medium text-red-900 mb-2">A- (Solusi Ideal Negatif)</h4>
                                        <div class="space-y-1">
                                            @foreach($idealSolution['A_minus'] ?? [] as $idx => $val)
                                                <div class="text-xs flex justify-between">
                                                    <span>{{ $idealSolution['cols'][$idx] ?? "C{$idx}" }}</span>
                                                    <strong>{{ number_format($val, 4) }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Distances -->
                    @if(!empty($distances))
                        <div class="bg-white rounded-xl border border-neutral-line shadow-sm p-6" x-data="{ open: false }">
                            <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                                <h3 class="text-lg font-semibold text-neutral-text">5. Jarak ke Solusi Ideal (S+ dan S-)</h3>
                                <svg class="w-5 h-5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div x-show="open" x-transition class="mt-4">
                                <p class="text-sm text-neutral-sub mb-4">Jarak Euclidean dari setiap alternatif ke A+ dan A-</p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-neutral-line">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 border border-neutral-line text-left font-medium">Alternatif</th>
                                                <th class="px-3 py-2 border border-neutral-line text-center font-medium">S+ (Jarak ke A+)</th>
                                                <th class="px-3 py-2 border border-neutral-line text-center font-medium">S- (Jarak ke A-)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($distances['rows'] ?? [] as $idx => $name)
                                                <tr>
                                                    <td class="px-3 py-2 border border-neutral-line font-medium">{{ $name }}</td>
                                                    <td class="px-3 py-2 border border-neutral-line text-center">{{ number_format($distances['S_plus'][$idx] ?? 0, 4) }}</td>
                                                    <td class="px-3 py-2 border border-neutral-line text-center">{{ number_format($distances['S_minus'][$idx] ?? 0, 4) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Closeness Coefficient -->
                    @if(!empty($closeness))
                        <div class="bg-white rounded-xl border border-neutral-line shadow-sm p-6" x-data="{ open: false }">
                            <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                                <h3 class="text-lg font-semibold text-neutral-text">6. Koefisien Kedekatan (CC)</h3>
                                <svg class="w-5 h-5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div x-show="open" x-transition class="mt-4">
                                <p class="text-sm text-neutral-sub mb-4">CC = S- / (S+ + S-). Nilai mendekati 1 menunjukkan alternatif terbaik.</p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-neutral-line">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 border border-neutral-line text-left font-medium">Alternatif</th>
                                                <th class="px-3 py-2 border border-neutral-line text-center font-medium">CC</th>
                                                <th class="px-3 py-2 border border-neutral-line text-center font-medium">Persentase</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($closeness['rows'] ?? [] as $idx => $name)
                                                @php
                                                    $cc = $closeness['CC'][$idx] ?? 0;
                                                @endphp
                                                <tr>
                                                    <td class="px-3 py-2 border border-neutral-line font-medium">{{ $name }}</td>
                                                    <td class="px-3 py-2 border border-neutral-line text-center font-mono">{{ number_format($cc, 4) }}</td>
                                                    <td class="px-3 py-2 border border-neutral-line text-center">
                                                        <div class="flex items-center gap-2">
                                                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                                <div class="bg-brand h-2 rounded-full" style="width: {{ $cc * 100 }}%"></div>
                                                            </div>
                                                            <span>{{ number_format($cc * 100, 1) }}%</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-white rounded-xl border border-neutral-line shadow-sm p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-neutral-sub">Assessment ini belum selesai atau belum dijalankan.</p>
                    @if($assessment->status === 'draft')
                        <a href="{{ route('assess.wizard', $assessment->id) }}" class="inline-block mt-4 px-4 py-2 rounded-lg bg-brand text-white text-sm hover:bg-indigo-700 transition-colors">
                            Lanjutkan Assessment
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
