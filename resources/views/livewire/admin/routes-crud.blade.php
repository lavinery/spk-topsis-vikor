{{-- resources/views/livewire/admin/routes-crud.blade.php --}}
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <x-back-button href="{{ route('admin.dashboard') }}" text="Kembali ke Dashboard" />
        </div>

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="page-title">Routes Management</h1>
                <p class="page-subtitle">Kelola data jalur pendakian gunung</p>
            </div>
            <input type="text" wire:model.live="q" placeholder="Cari jalur..." class="search-input">
        </div>

        {{-- Import Form --}}
        <div class="ui-card mb-8 bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-blue-900 mb-2">Import Routes</h3>
                    <p class="text-sm text-blue-700 mb-4">Upload file Excel atau CSV untuk mengimport banyak jalur sekaligus</p>

                    @if (session('ok'))
                        <div class="mb-4 p-3 bg-green-50 border-2 border-green-300 rounded-lg text-green-800 text-sm font-medium">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ session('ok') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-3 bg-red-50 border-2 border-red-300 rounded-lg text-red-800 text-sm font-medium">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.routes.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3">
                        @csrf
                        <input type="file" name="file" accept=".xlsx,.csv"
                               class="border-2 border-gray-300 rounded-lg text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-600 file:text-white file:font-medium hover:file:bg-indigo-700 file:transition-colors" required>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 inline-block mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Import Sekarang
                        </button>
                    </form>

                    <div class="mt-4 text-xs text-gray-700 bg-white p-4 rounded-lg border-2 border-gray-200">
                        <div class="font-bold text-sm mb-2 text-gray-800">Kolom yang Diperlukan:</div>
                        <p class="mb-3">mountain_name, route_name, distance_km, elevation_gain_m, slope_class, land_cover_key, water_sources_score, support_facility_score, permit_required, province, elevation_m</p>
                        <a href="{{ asset('templates/routes_import_template.csv') }}" download class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-medium underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Template CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Input --}}
        <div class="ui-card mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">{{ $editingId ? 'Edit Jalur' : 'Tambah Jalur Baru' }}</h2>
            <form wire:submit.prevent="save">
                    <div class="ui-field">
                        <label class="ui-label">Gunung</label>
                        <select wire:model="mountain_id" class="w-full">
                            <option value="">-- Pilih Gunung --</option>
                            @foreach ($mountains as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                        @error('mountain_id') <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div class="ui-field">
                        <label class="ui-label">Nama Jalur</label>
                        <input type="text" wire:model="name" class="w-full" placeholder="Contoh: Jalur Selatan">
                        @error('name') <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div class="ui-field">
                        <label class="ui-label">Panjang Jalur (km)</label>
                        <input type="number" step="0.01" wire:model="distance_km" class="w-full" placeholder="0.00">
                    </div>
                    <div class="ui-field">
                        <label class="ui-label">Elevasi Gain (meter)</label>
                        <input type="number" wire:model="elevation_gain_m" class="w-full" placeholder="0">
                    </div>
                    <div class="ui-field">
                        <label class="ui-label">Kelas Kecuraman (1–5)</label>
                        <input type="number" min="1" max="5" wire:model="slope_class" class="w-full" placeholder="1-5">
                        <p class="ui-hint">1 = Landai, 5 = Sangat Terjal</p>
                    </div>
                    <div class="ui-field">
                        <label class="ui-label">Tutupan Lahan</label>
                        <input type="text" wire:model="land_cover_key" class="w-full" placeholder="hutan-lebat / savana">
                    </div>
                    <div class="ui-field">
                        <label class="ui-label">Sumber Air (0–10)</label>
                        <input type="number" min="0" max="10" wire:model="water_sources_score" class="w-full" placeholder="0-10">
                        <p class="ui-hint">Skor ketersediaan sumber air</p>
                    </div>
                    <div class="ui-field">
                        <label class="ui-label">Sarana Pendukung (0–10)</label>
                        <input type="number" min="0" max="10" wire:model="support_facility_score" class="w-full" placeholder="0-10">
                        <p class="ui-hint">Skor kelengkapan fasilitas pendukung</p>
                    </div>
                    <div class="ui-field">
                        <label class="flex items-center gap-3 cursor-pointer p-4 rounded-lg border-2 border-gray-200 hover:border-indigo-300 transition-colors">
                            <input id="permit" type="checkbox" wire:model="permit_required">
                            <div>
                                <span class="text-sm font-semibold text-gray-800">Perlu Izin</span>
                                <p class="text-xs text-gray-600 mt-0.5">Jalur ini memerlukan izin khusus</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-6 border-t border-gray-200 mt-8">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 inline-block mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $editingId ? 'Update Jalur' : 'Simpan Jalur' }}
                    </button>
                    @if ($editingId)
                        <button type="button" wire:click="resetForm" class="btn-secondary">
                            <svg class="w-4 h-4 inline-block mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </button>
                    @endif
                    @if (session('ok'))
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

        {{-- Data Table --}}
        <div class="bg-white rounded-xl border-2 border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b-2 border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">Daftar Jalur</h2>
                <p class="text-sm text-gray-600 mt-1">Total: {{ $routes->total() }} jalur pendakian</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y-2 divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Gunung</th>
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Jalur</th>
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Panjang</th>
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Elevasi</th>
                            <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Slope</th>
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tutupan</th>
                            <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Air</th>
                            <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Fasilitas</th>
                            <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($routes as $r)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3.5 text-sm font-medium text-gray-900">
                                    {{ $r->mountain?->name }}
                                </td>
                                <td class="px-4 py-3.5 text-sm">
                                    <div class="font-medium text-gray-900">{{ $r->name }}</div>
                                    @if($r->permit_required)
                                        <span class="inline-flex items-center text-xs text-amber-700 mt-1">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            Perlu Izin
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-sm text-gray-700 font-mono">
                                    {{ $r->distance_km }} km
                                </td>
                                <td class="px-4 py-3.5 text-sm text-gray-700 font-mono">
                                    {{ number_format($r->elevation_gain_m) }}m
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $r->slope_class >= 4 ? 'bg-red-100 text-red-700' : ($r->slope_class >= 3 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }} font-bold text-sm">
                                        {{ $r->slope_class }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-sm text-gray-600">
                                    <span class="badge badge-neutral">{{ $r->land_cover_key }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        @for($i = 0; $i < 10; $i++)
                                            <div class="w-1.5 h-3 rounded-full {{ $i < $r->water_sources_score ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                                        @endfor
                                    </div>
                                    <div class="text-xs text-gray-600 mt-1 font-mono">{{ $r->water_sources_score }}/10</div>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        @for($i = 0; $i < 10; $i++)
                                            <div class="w-1.5 h-3 rounded-full {{ $i < $r->support_facility_score ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                                        @endfor
                                    </div>
                                    <div class="text-xs text-gray-600 mt-1 font-mono">{{ $r->support_facility_score }}/10</div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="edit({{ $r->id }})" class="px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-medium hover:bg-indigo-100 transition-colors">
                                            Edit
                                        </button>
                                        <button wire:click="delete({{ $r->id }})" class="btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus jalur {{ $r->name }}?')">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-600">Belum ada jalur</p>
                                        <p class="text-sm text-gray-500 mt-1">Tambahkan jalur baru atau import dari file</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t-2 border-gray-200">
                {{ $routes->links() }}
            </div>
        </div>
    </div>
</div>