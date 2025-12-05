{{-- resources/views/livewire/admin/routes-crud.blade.php --}}
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header with Add Button -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="text-neutral-sub hover:text-neutral-text transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-neutral-text">Manajemen Jalur Pendakian</h1>
                </div>
                <div class="flex items-center gap-3">
                    <button wire:click="create" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-brand text-white font-medium hover:bg-indigo-700 transition-colors shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Jalur
                    </button>
                    <button @click="$dispatch('open-import-modal')" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Impor
                    </button>
                </div>
            </div>
            <p class="text-sm text-neutral-sub ml-9">Kelola data jalur pendakian untuk setiap gunung</p>
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
                    placeholder="Cari nama jalur atau gunung..."
                    class="flex-1 px-4 py-2 border-0 focus:ring-0 text-sm"
                >
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-white rounded-xl border border-neutral-line shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-line">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gunung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Panjang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Elevasi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Slope</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tutupan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Air</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-line">
                    @forelse ($routes as $r)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-neutral-text">
                                {{ $r->mountain?->name }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium text-neutral-text">{{ $r->name }}</div>
                                @if($r->permit_required)
                                    <span class="inline-flex items-center text-xs text-amber-700 mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        Perlu Izin
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-text font-mono">
                                {{ $r->distance_km }} km
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-text font-mono">
                                {{ number_format($r->elevation_gain_m) }}m
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $r->slope_class >= 4 ? 'bg-red-100 text-red-700' : ($r->slope_class >= 3 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }} font-bold text-sm">
                                    {{ $r->slope_class }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-sub">
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700 font-medium">{{ $r->land_cover_key }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @for($i = 0; $i < 10; $i++)
                                        <div class="w-1.5 h-3 rounded-full {{ $i < $r->water_sources_score ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                                    @endfor
                                </div>
                                <div class="text-xs text-neutral-sub mt-1 font-mono">{{ $r->water_sources_score }}/10</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @for($i = 0; $i < 10; $i++)
                                        <div class="w-1.5 h-3 rounded-full {{ $i < $r->support_facility_score ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                                    @endfor
                                </div>
                                <div class="text-xs text-neutral-sub mt-1 font-mono">{{ $r->support_facility_score }}/10</div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="edit({{ $r->id }})" class="px-3 py-1.5 rounded-lg bg-brand text-white text-xs hover:bg-indigo-700 transition-colors">
                                        Ubah
                                    </button>
                                    <button
                                        @click="$dispatch('open-delete-modal', {
                                            id: {{ $r->id }},
                                            name: '{{ $r->name }}',
                                            details: 'Gunung: {{ $r->mountain?->name }}\nPanjang: {{ $r->distance_km }} km\nElevasi: {{ number_format($r->elevation_gain_m) }} m\nKecuraman: {{ $r->slope_class }}/6\n\nData jalur ini akan dihapus permanen!'
                                        })"
                                        class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 text-xs hover:bg-red-100 transition-colors">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                                <p class="text-neutral-sub text-sm">Tidak ada data jalur</p>
                                <p class="text-xs text-neutral-sub mt-1">Klik "Tambah Jalur" atau import dari file</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            <!-- Pagination -->
            @if($routes->hasPages())
                <div class="px-6 py-4 border-t border-neutral-line">
                    {{ $routes->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Form Modal --}}
    <x-form-modal title="{{ $editingId ? 'Edit Jalur' : 'Tambah Jalur Baru' }}">
        <form wire:submit.prevent="save">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Gunung <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="mountain_id" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all">
                        <option value="">-- Pilih Gunung --</option>
                        @foreach ($mountains as $m)
                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                        @endforeach
                    </select>
                    @error('mountain_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Nama Jalur <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="name" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="Contoh: Jalur Selatan">
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Panjang Jalur (km)
                    </label>
                    <input type="number" step="0.01" min="0" max="500" wire:model="distance_km" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="0.00">
                    @error('distance_km') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Elevasi Gain (meter)
                    </label>
                    <input type="number" min="0" max="9000" wire:model="elevation_gain_m" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="0">
                    @error('elevation_gain_m') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Kelas Kecuraman (1–6)
                    </label>
                    <input type="number" min="1" max="6" wire:model="slope_class" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="1-6">
                    @error('slope_class') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-neutral-sub mt-1">1 = Landai, 6 = Sangat Terjal</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Tutupan Lahan
                    </label>
                    <input type="text" maxlength="50" wire:model="land_cover_key" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="hutan-lebat / savana / terbuka">
                    @error('land_cover_key') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Sumber Air (0–10)
                    </label>
                    <input type="number" min="0" max="10" wire:model="water_sources_score" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="0-10">
                    @error('water_sources_score') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-neutral-sub mt-1">Skor ketersediaan sumber air</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Sarana Pendukung (0–10)
                    </label>
                    <input type="number" min="0" max="10" wire:model="support_facility_score" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="0-10">
                    @error('support_facility_score') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-neutral-sub mt-1">Skor kelengkapan fasilitas pendukung</p>
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer p-4 rounded-lg border border-neutral-line hover:bg-gray-50 transition-colors">
                        <input id="permit" type="checkbox" wire:model="permit_required" class="w-4 h-4 text-brand border-neutral-line rounded focus:ring-brand">
                        <div>
                            <span class="text-sm font-medium text-neutral-text">Perlu Izin</span>
                            <p class="text-xs text-neutral-sub mt-0.5">Jalur ini memerlukan izin khusus</p>
                        </div>
                    </label>
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
    <x-delete-modal title="Hapus Jalur Pendakian" onConfirm="delete" />

    {{-- Import Modal --}}
    <x-import-modal
        title="Impor Jalur Pendakian"
        route="{{ route('admin.routes.import') }}"
        templateRoute="{{ asset('templates/routes_import_template.csv') }}"
        requiredColumns="mountain_name, route_name, distance_km, elevation_gain_m, slope_class, land_cover_key, water_sources_score, support_facility_score, permit_required, province, elevation_m"
        description="Upload file Excel atau CSV untuk mengimport banyak jalur pendakian sekaligus"
    />
</div>
