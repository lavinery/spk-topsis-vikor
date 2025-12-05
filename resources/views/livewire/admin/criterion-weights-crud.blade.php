<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button href="{{ route('admin.dashboard') }}" text="Kembali ke Dashboard" />
    </div>
    
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text">Kelola Bobot Kriteria</h1>
                <p class="text-sm text-neutral-sub">Atur bobot untuk setiap kriteria dalam perhitungan TOPSIS</p>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="create" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-brand text-white font-medium hover:bg-indigo-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Bobot
                </button>
                <button @click="$dispatch('open-import-modal')" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Impor
                </button>
                <button wire:click="normalizeWeights" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-green-600 text-white font-medium hover:bg-green-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Normalisasi Bobot
                </button>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('ok'))
        <div class="mb-6 flex items-center gap-2 text-sm text-green-700 bg-green-50 px-4 py-3 rounded-lg border border-green-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('ok') }}
        </div>
    @endif

    <!-- Search -->
    <div class="mb-4">
        <input type="text" wire:model.live="q" 
               placeholder="Cari kriteria..." 
               class="w-full max-w-md px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent">
    </div>

    <!-- Weight Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Total Weight -->
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center justify-between">
                <span class="text-blue-800 font-medium">Total Bobot:</span>
                <span class="text-blue-900 font-bold text-lg">{{ number_format($totalWeight, 4) }}</span>
            </div>
            @if(abs($totalWeight - 1.0) > 0.001)
                <p class="text-blue-700 text-sm mt-1">
                    ⚠️ Tidak sama dengan 1.0
                </p>
            @else
                <p class="text-green-700 text-sm mt-1">
                    ✅ Sudah normal (1.0)
                </p>
            @endif
        </div>

        <!-- Remaining Weight -->
        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center justify-between">
                <span class="text-green-800 font-medium">Sisa Bobot:</span>
                <span class="text-green-900 font-bold text-lg">{{ number_format($remainingWeight, 4) }}</span>
            </div>
            @if($remainingWeight > 0)
                <p class="text-green-700 text-sm mt-1">
                    📊 Masih bisa ditambahkan
                </p>
            @elseif($remainingWeight < 0)
                <p class="text-red-700 text-sm mt-1">
                    ⚠️ Melebihi batas maksimal
                </p>
            @else
                <p class="text-gray-700 text-sm mt-1">
                    ✅ Sudah penuh
                </p>
            @endif
        </div>

        <!-- Percentage -->
        <div class="p-4 bg-purple-50 border border-purple-200 rounded-lg">
            <div class="flex items-center justify-between">
                <span class="text-purple-800 font-medium">Persentase:</span>
                <span class="text-purple-900 font-bold text-lg">{{ number_format($totalWeight * 100, 1) }}%</span>
            </div>
            <div class="mt-2">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full transition-all duration-300"
                         style="width: {{ min(100, $totalWeight * 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-neutral-line overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sumber</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Versi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($weights as $weight)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $weight->criterion->code }}</div>
                                <div class="text-sm text-gray-500">{{ $weight->criterion->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $weight->criterion->source === 'USER' ? 'bg-blue-100 text-blue-800' : 
                                       ($weight->criterion->source === 'MOUNTAIN' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                                    {{ $weight->criterion->source }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($weight->weight, 4) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format(($weight->weight / max($totalWeight, 0.001)) * 100, 1) }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $weight->version }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <button wire:click="edit({{ $weight->id }})" class="px-3 py-1.5 rounded-lg bg-brand text-white text-xs hover:bg-indigo-700 transition-colors">
                                        Ubah
                                    </button>
                                    <button
                                        @click="$dispatch('open-delete-modal', {
                                            id: {{ $weight->id }},
                                            name: '{{ $weight->criterion->code }} - {{ $weight->criterion->name }}',
                                            details: 'Sumber: {{ $weight->criterion->source }}\nBobot: {{ number_format($weight->weight, 4) }}\nPersentase: {{ number_format(($weight->weight / max($totalWeight, 0.001)) * 100, 1) }}%\nVersi: {{ $weight->version }}\n\nMenghapus bobot ini akan mempengaruhi perhitungan TOPSIS!'
                                        })"
                                        class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 text-xs hover:bg-red-100 transition-colors">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada bobot kriteria ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $weights->links() }}
        </div>
    </div>

    {{-- Form Modal --}}
    <x-form-modal title="{{ $editingId ? 'Edit Bobot Kriteria' : 'Tambah Bobot Kriteria' }}">
        <form wire:submit.prevent="save">
            <div class="grid gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Kriteria <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="criterion_id" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all">
                        <option value="">-- Pilih Kriteria --</option>
                        @foreach($criteria as $criterion)
                            <option value="{{ $criterion->id }}">{{ $criterion->code }} - {{ $criterion->name }}</option>
                        @endforeach
                    </select>
                    @error('criterion_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Bobot (0-1) <span class="text-red-500">*</span>
                        @if($remainingWeight > 0 && !$editingId)
                            <span class="text-green-600 text-xs font-normal">
                                • Sisa: {{ number_format($remainingWeight, 4) }}
                            </span>
                        @endif
                    </label>
                    <input type="number" wire:model.live="weight" step="0.001" min="0" max="{{ $editingId ? 1 : $remainingWeight }}"
                           class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all"
                           placeholder="Contoh: 0.25">
                    @error('weight') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror

                    @if($weight && $remainingWeight < $weight && !$editingId)
                        <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                            ⚠️ Bobot yang dimasukkan ({{ $weight }}) melebihi sisa yang tersedia ({{ number_format($remainingWeight, 4) }})
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">Versi</label>
                    <input type="text" wire:model="version" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="v1">
                    @error('version') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-neutral-line">
                <button type="button" @click="$dispatch('close-form-modal')" class="px-5 py-2.5 rounded-lg bg-gray-100 text-neutral-text font-medium hover:bg-gray-200 transition-colors">
                    Batal
                </button>
                <button type="submit"
                        @if(!$editingId && $weight > $remainingWeight) disabled @endif
                        class="inline-flex items-center px-5 py-2.5 rounded-lg bg-brand text-white font-medium hover:bg-indigo-700 transition-colors shadow-sm disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ $editingId ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </form>
    </x-form-modal>

    {{-- Delete Confirmation Modal --}}
    <x-delete-modal title="Hapus Bobot Kriteria" onConfirm="delete" />

    {{-- Import Modal --}}
    <x-import-modal
        title="Impor Bobot Kriteria"
        route="{{ route('admin.criterion-weights.import') }}"
        templateFile="templates/criterion_weights_import_template.csv"
        requiredColumns="criterion_code, weight, version"
        description="Upload file Excel atau CSV untuk mengimport banyak bobot kriteria sekaligus"
    />
</div>
