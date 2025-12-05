<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header with Action Buttons -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="text-neutral-sub hover:text-neutral-text transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-neutral-text">Manajemen Kriteria</h1>
                </div>
                <div class="flex items-center gap-3">
                    <button wire:click="create" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-brand text-white font-medium hover:bg-indigo-700 transition-colors shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Kriteria
                    </button>
                    <button @click="$dispatch('open-import-modal')" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Impor
                    </button>
                </div>
            </div>
            <p class="text-sm text-neutral-sub ml-9">Kelola kriteria untuk perhitungan TOPSIS</p>
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

        <!-- Search Bar -->
        <div class="bg-white rounded-xl border border-neutral-line shadow-sm p-4 mb-6">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-neutral-sub" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input
                    type="text"
                    wire:model.live="q"
                    placeholder="Cari kode atau nama kriteria..."
                    class="flex-1 px-4 py-2 border-0 focus:ring-0 text-sm"
                >
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-xl border border-neutral-line shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-line">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sumber</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skala</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fuzzy</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-line">
                    @forelse($list as $i => $c)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-neutral-sub font-medium">{{ $list->firstItem() + $i }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 font-mono font-semibold text-xs">
                                    {{ $c->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-neutral-text">{{ $c->name }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700 font-medium">{{ $c->source }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 text-xs rounded-full font-medium {{ $c->type === 'benefit' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $c->type === 'benefit' ? '↗ Manfaat' : '↘ Biaya' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-text">{{ ucfirst($c->scale) }}</td>
                            <td class="px-6 py-4 text-sm text-neutral-sub font-mono">{{ $c->unit ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 text-xs rounded-full font-medium {{ $c->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $c->active ? '✓ Aktif' : '○ Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 text-xs rounded-full font-medium {{ $c->is_fuzzy ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $c->is_fuzzy ? 'Ya' : 'Tidak' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex rounded-lg border border-neutral-line overflow-hidden">
                                    <button wire:click="up({{ $c->id }})" class="px-3 py-1.5 hover:bg-brand hover:text-white transition-colors text-sm font-bold">
                                        ▲
                                    </button>
                                    <button wire:click="down({{ $c->id }})" class="px-3 py-1.5 hover:bg-brand hover:text-white transition-colors border-l border-neutral-line text-sm font-bold">
                                        ▼
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="edit({{ $c->id }})" class="px-3 py-1.5 rounded-lg bg-brand text-white text-xs hover:bg-indigo-700 transition-colors">
                                        Ubah
                                    </button>
                                    <button
                                        @click="$dispatch('open-delete-modal', {
                                            id: {{ $c->id }},
                                            name: '{{ $c->code }} - {{ $c->name }}',
                                            details: 'Sumber: {{ $c->source }}\nTipe: {{ $c->type }}\nSkala: {{ $c->scale }}\nStatus: {{ $c->active ? "Aktif" : "Nonaktif" }}\n\n⚠️ PERINGATAN:\nData terkait yang akan terhapus:\n- Bobot kriteria\n- Fuzzy terms (jika ada)\n- Category maps (jika ada)\n- Jawaban assessment terkait\n\nProses ini tidak dapat dibatalkan!'
                                        })"
                                        class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 text-xs hover:bg-red-100 transition-colors">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-neutral-sub text-sm">Tidak ada data kriteria</p>
                                <p class="text-xs text-neutral-sub mt-1">Tambahkan kriteria baru menggunakan form di atas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            <!-- Pagination -->
            @if($list->hasPages())
                <div class="px-6 py-4 border-t border-neutral-line">
                    {{ $list->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Form Modal --}}
    <x-form-modal title="{{ $editingId ? 'Edit Kriteria' : 'Tambah Kriteria Baru' }}">
        <form wire:submit.prevent="save">
            <!-- Basic Information -->
            <div class="mb-6">
                <h3 class="text-sm font-medium text-neutral-text mb-3 pb-2 border-b border-neutral-line">Informasi Dasar</h3>
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">
                            Kode Kriteria <span class="text-red-500">*</span>
                        </label>
                        <input wire:model="code" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="Contoh: C15">
                        @error('code') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-neutral-text mb-2">
                            Nama Kriteria <span class="text-red-500">*</span>
                        </label>
                        <input wire:model="name" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="Contoh: Ketinggian Gunung">
                        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Configuration -->
            <div class="mb-6">
                <h3 class="text-sm font-medium text-neutral-text mb-3 pb-2 border-b border-neutral-line">Konfigurasi</h3>
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">Sumber Data</label>
                        <select wire:model="source" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all">
                            <option value="USER">USER - Input Pengguna</option>
                            <option value="ROUTE">ROUTE - Data Jalur</option>
                            <option value="MOUNTAIN">MOUNTAIN - Data Gunung</option>
                        </select>
                        @error('source') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">Tipe Kriteria</label>
                        <select wire:model="type" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all">
                            <option value="benefit">Manfaat (Lebih tinggi lebih baik)</option>
                            <option value="cost">Biaya (Lebih rendah lebih baik)</option>
                        </select>
                        @error('type') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">Tipe Data</label>
                        <select wire:model="data_type" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all">
                            <option value="numeric">Numeric - Angka</option>
                            <option value="ordinal">Ordinal - Urutan</option>
                            <option value="categorical">Categorical - Kategori</option>
                            <option value="boolean">Boolean - Ya/Tidak</option>
                        </select>
                        @error('data_type') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Scale & Unit -->
            <div class="mb-6">
                <h3 class="text-sm font-medium text-neutral-text mb-3 pb-2 border-b border-neutral-line">Skala & Satuan</h3>
                <div class="grid md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">Skala</label>
                        <select wire:model="scale" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all">
                            <option value="numeric">Numeric</option>
                            <option value="categorical">Categorical</option>
                        </select>
                        @error('scale') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">Satuan</label>
                        <input wire:model="unit" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="m, km, 0-10">
                        @error('unit') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">Nilai Minimum</label>
                        <input type="number" step="0.001" wire:model="min_hint" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="0">
                        @error('min_hint') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">Nilai Maksimum</label>
                        <input type="number" step="0.001" wire:model="max_hint" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="100">
                        @error('max_hint') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Options -->
            <div class="mb-6">
                <h3 class="text-sm font-medium text-neutral-text mb-3 pb-2 border-b border-neutral-line">Opsi Tambahan</h3>
                <div class="grid md:grid-cols-3 gap-4">
                    <label class="flex items-center gap-3 cursor-pointer p-4 rounded-lg border border-neutral-line hover:bg-gray-50 transition-colors">
                        <input type="checkbox" wire:model="active" class="w-4 h-4 text-brand border-neutral-line rounded focus:ring-brand">
                        <div>
                            <span class="text-sm font-medium text-neutral-text">Aktif</span>
                            <p class="text-xs text-neutral-sub mt-0.5">Gunakan kriteria ini dalam perhitungan</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer p-4 rounded-lg border border-neutral-line hover:bg-gray-50 transition-colors">
                        <input type="checkbox" wire:model="is_fuzzy" class="w-4 h-4 text-brand border-neutral-line rounded focus:ring-brand">
                        <div>
                            <span class="text-sm font-medium text-neutral-text">Proses dengan Fuzzy</span>
                            <p class="text-xs text-neutral-sub mt-0.5">Gunakan logika fuzzy untuk kriteria ini</p>
                        </div>
                    </label>
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">Urutan Tampilan</label>
                        <input type="number" wire:model="sort_order" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="0">
                        @error('sort_order') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-neutral-line">
                <button type="button" @click="$dispatch('close-form-modal')" class="px-5 py-2.5 rounded-lg bg-gray-100 text-neutral-text font-medium hover:bg-gray-200 transition-colors">
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-brand text-white font-medium hover:bg-indigo-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ $editingId ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </form>
    </x-form-modal>

    {{-- Delete Confirmation Modal --}}
    <x-delete-modal title="Hapus Data Kriteria" onConfirm="delete" />

    {{-- Import Modal --}}
    <x-import-modal
        title="Impor Data Kriteria"
        route="{{ route('admin.criteria.import') }}"
        templateFile="templates/criteria_import_template.csv"
        requiredColumns="code, name, source, type, scale, data_type, unit, active, is_fuzzy"
        description="Upload file Excel atau CSV untuk mengimport banyak data kriteria sekaligus"
    />
</div>
