<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <x-back-button href="{{ route('admin.dashboard') }}" text="Kembali ke Dashboard" />
        </div>

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="page-title">Criteria Management</h1>
                <p class="page-subtitle">Kelola kriteria untuk perhitungan TOPSIS</p>
            </div>
            <input type="text" wire:model.live="q" placeholder="Cari kode/nama..." class="search-input">
        </div>

        <!-- Form Card -->
        <div class="ui-card mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">{{ $editingId ? 'Edit Kriteria' : 'Tambah Kriteria Baru' }}</h2>

            <form wire:submit.prevent="save">
                <!-- Basic Information -->
                <div class="mb-8">
                    <h3 class="text-base font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Informasi Dasar</h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="ui-field">
                            <label class="ui-label">Kode Kriteria</label>
                            <input wire:model="code" class="w-full" placeholder="Contoh: C15">
                            <p class="ui-hint">Kode unik untuk identifikasi kriteria</p>
                        </div>
                        <div class="ui-field md:col-span-2">
                            <label class="ui-label">Nama Kriteria</label>
                            <input wire:model="name" class="w-full" placeholder="Contoh: Ketinggian">
                            <p class="ui-hint">Nama deskriptif untuk kriteria ini</p>
                        </div>
                    </div>
                </div>

                <!-- Configuration -->
                <div class="mb-8">
                    <h3 class="text-base font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Konfigurasi</h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="ui-field">
                            <label class="ui-label">Sumber Data</label>
                            <select wire:model="source" class="w-full">
                                <option value="USER">USER - Input Pengguna</option>
                                <option value="ROUTE" selected>ROUTE - Data Jalur</option>
                                <option value="MOUNTAIN">MOUNTAIN - Data Gunung</option>
                            </select>
                        </div>
                        <div class="ui-field">
                            <label class="ui-label">Tipe Kriteria</label>
                            <select wire:model="type" class="w-full">
                                <option value="benefit">Benefit (Semakin tinggi semakin baik)</option>
                                <option value="cost">Cost (Semakin rendah semakin baik)</option>
                            </select>
                        </div>
                        <div class="ui-field">
                            <label class="ui-label">Tipe Data</label>
                            <select wire:model="data_type" class="w-full">
                                <option value="numeric">Numeric - Angka</option>
                                <option value="ordinal">Ordinal - Urutan</option>
                                <option value="categorical">Categorical - Kategori</option>
                                <option value="boolean">Boolean - Ya/Tidak</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Scale & Unit -->
                <div class="mb-8">
                    <h3 class="text-base font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Skala & Satuan</h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="ui-field">
                            <label class="ui-label">Skala</label>
                            <select wire:model="scale" class="w-full">
                                <option value="numeric">Numeric</option>
                                <option value="categorical">Categorical</option>
                            </select>
                        </div>
                        <div class="ui-field">
                            <label class="ui-label">Satuan</label>
                            <input wire:model="unit" class="w-full" placeholder="m, km, atau 0..10">
                            <p class="ui-hint">Contoh: meter, km, skor 1-10</p>
                        </div>
                        <div class="ui-field">
                            <label class="ui-label">Nilai Minimum</label>
                            <input type="number" step="0.001" wire:model="min_hint" class="w-full" placeholder="0">
                        </div>
                        <div class="ui-field">
                            <label class="ui-label">Nilai Maksimum</label>
                            <input type="number" step="0.001" wire:model="max_hint" class="w-full" placeholder="100">
                        </div>
                    </div>
                </div>

                <!-- Options -->
                <div class="mb-8">
                    <h3 class="text-base font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Opsi Tambahan</h3>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="ui-field">
                            <label class="flex items-center gap-3 cursor-pointer p-4 rounded-lg border-2 border-gray-200 hover:border-indigo-300 transition-colors">
                                <input type="checkbox" wire:model="active">
                                <div>
                                    <span class="text-sm font-semibold text-gray-800">Aktif</span>
                                    <p class="text-xs text-gray-600 mt-0.5">Gunakan kriteria ini dalam perhitungan</p>
                                </div>
                            </label>
                        </div>
                        <div class="ui-field">
                            <label class="flex items-center gap-3 cursor-pointer p-4 rounded-lg border-2 border-gray-200 hover:border-indigo-300 transition-colors">
                                <input type="checkbox" wire:model="is_fuzzy">
                                <div>
                                    <span class="text-sm font-semibold text-gray-800">Proses dengan Fuzzy</span>
                                    <p class="text-xs text-gray-600 mt-0.5">Gunakan logika fuzzy untuk kriteria ini</p>
                                </div>
                            </label>
                        </div>
                        <div class="ui-field">
                            <label class="ui-label">Urutan Tampilan</label>
                            <input type="number" wire:model="sort_order" class="w-full" placeholder="0">
                            <p class="ui-hint">Urutan untuk menampilkan kriteria</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center gap-3 pt-6 border-t border-gray-200">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 inline-block mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $editingId ? 'Update Kriteria' : 'Simpan Kriteria' }}
                    </button>
                    @if($editingId)
                        <button type="button" wire:click="resetForm" class="btn-secondary">
                            <svg class="w-4 h-4 inline-block mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </button>
                    @endif
                    @if(session('ok'))
                        <div class="flex items-center gap-2 text-green-700 bg-green-50 px-4 py-2 rounded-lg border border-green-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ session('ok') }}</span>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-xl border-2 border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b-2 border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">Daftar Kriteria</h2>
                <p class="text-sm text-gray-600 mt-1">Total: {{ $list->total() }} kriteria</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y-2 divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-16">#</th>
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Sumber</th>
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tipe</th>
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Skala</th>
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Satuan</th>
                            <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Fuzzy</th>
                            <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Urutan</th>
                            <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($list as $i => $c)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3.5 text-sm text-gray-600 font-medium">{{ $list->firstItem() + $i }}</td>
                                <td class="px-4 py-3.5 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 font-mono font-semibold">
                                        {{ $c->code }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-sm font-medium text-gray-900">{{ $c->name }}</td>
                                <td class="px-4 py-3.5 text-sm">
                                    <span class="badge badge-neutral">{{ $c->source }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-sm">
                                    <span class="badge {{ $c->type === 'benefit' ? 'badge-success' : 'badge-warning' }}">
                                        {{ $c->type === 'benefit' ? '↗ Benefit' : '↘ Cost' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-sm text-gray-700">{{ ucfirst($c->scale) }}</td>
                                <td class="px-4 py-3.5 text-sm text-gray-600 font-mono">{{ $c->unit ?? '-' }}</td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="badge {{ $c->active ? 'badge-success' : 'badge-neutral' }}">
                                        {{ $c->active ? '✓ Aktif' : '○ Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="badge {{ $c->is_fuzzy ? 'badge-info' : 'badge-neutral' }}">
                                        {{ $c->is_fuzzy ? 'Ya' : 'Tidak' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <div class="inline-flex rounded-lg border-2 border-gray-300 overflow-hidden">
                                        <button wire:click="up({{ $c->id }})" class="px-3 py-1.5 hover:bg-indigo-50 hover:text-indigo-600 transition-colors text-sm font-bold">
                                            ▲
                                        </button>
                                        <button wire:click="down({{ $c->id }})" class="px-3 py-1.5 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border-l-2 border-gray-300 text-sm font-bold">
                                            ▼
                                        </button>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="edit({{ $c->id }})" class="px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-medium hover:bg-indigo-100 transition-colors">
                                            Edit
                                        </button>
                                        <button wire:click="delete({{ $c->id }})" class="btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus kriteria {{ $c->code }}?')">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-600">Belum ada kriteria</p>
                                        <p class="text-sm text-gray-500 mt-1">Tambahkan kriteria baru menggunakan form di atas</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t-2 border-gray-200">
                {{ $list->links() }}
            </div>
        </div>
    </div>
</div>