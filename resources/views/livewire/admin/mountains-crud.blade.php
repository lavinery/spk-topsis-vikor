{{-- resources/views/livewire/admin/mountains-crud.blade.php --}}
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
                    <h1 class="text-2xl font-bold text-neutral-text">Manajemen Gunung</h1>
                </div>
                <div class="flex items-center gap-3">
                    <button wire:click="create" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-brand text-white font-medium hover:bg-indigo-700 transition-colors shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Gunung
                    </button>
                    <button @click="$dispatch('open-import-modal')" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Impor
                    </button>
                </div>
            </div>
            <p class="text-sm text-neutral-sub ml-9">Kelola data gunung untuk sistem rekomendasi TOPSIS</p>
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
                    placeholder="Cari nama gunung atau provinsi..."
                    class="flex-1 px-4 py-2 border-0 focus:ring-0 text-sm"
                >
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl border border-neutral-line shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-line">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Elevasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provinsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Koordinat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-line">
                    @forelse ($mountains as $m)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-neutral-text">{{ $m->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-text">
                                {{ number_format($m->elevation_m) }} m
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-text">
                                {{ $m->province }}
                            </td>
                            <td class="px-6 py-4 text-xs text-neutral-sub font-mono">
                                @if($m->lat && $m->lng)
                                    {{ number_format($m->lat, 6) }}, {{ number_format($m->lng, 6) }}
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full font-medium
                                    @if($m->status === 'active') bg-green-100 text-green-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ $m->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <button
                                        wire:click="edit({{ $m->id }})"
                                        class="px-3 py-1.5 rounded-lg bg-brand text-white text-xs hover:bg-indigo-700 transition-colors"
                                    >
                                        Ubah
                                    </button>
                                    <button
                                        @click="$dispatch('open-delete-modal', {
                                            id: {{ $m->id }},
                                            name: '{{ $m->name }}',
                                            details: 'Provinsi: {{ $m->province }}\nElevasi: {{ number_format($m->elevation_m) }} m\nStatus: {{ ucfirst($m->status) }}\n\n⚠️ PERINGATAN:\nSemua jalur pendakian di gunung ini juga akan terhapus!\n\nProses ini tidak dapat dibatalkan!'
                                        })"
                                        class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 text-xs hover:bg-red-100 transition-colors"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-neutral-sub text-sm">Tidak ada data gunung</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            <!-- Pagination -->
            @if($mountains->hasPages())
                <div class="px-6 py-4 border-t border-neutral-line">
                    {{ $mountains->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Form Modal --}}
    <x-form-modal title="{{ $editingId ? 'Edit Gunung' : 'Tambah Gunung Baru' }}">
        <form wire:submit.prevent="save">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Nama Gunung <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="name" placeholder="Contoh: Gunung Semeru" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all">
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Elevasi (m) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" wire:model="elevation_m" placeholder="3676" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all">
                    @error('elevation_m') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Provinsi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="province" placeholder="Jawa Timur" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all">
                    @error('province') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="status" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all">
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                    @error('status') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Latitude
                    </label>
                    <input type="number" step="0.000001" wire:model="lat" placeholder="-8.1084" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all">
                    @error('lat') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Longitude
                    </label>
                    <input type="number" step="0.000001" wire:model="lng" placeholder="112.9225" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all">
                    @error('lng') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
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
    <x-delete-modal title="Hapus Data Gunung" onConfirm="delete" />

    {{-- Import Modal --}}
    <x-import-modal
        title="Impor Data Gunung"
        route="{{ route('admin.mountains.import') }}"
        templateFile="templates/mountains_import_template.csv"
        requiredColumns="name, elevation_m, province, lat, lng, status"
        description="Upload file Excel atau CSV untuk mengimport banyak data gunung sekaligus"
    />
</div>
