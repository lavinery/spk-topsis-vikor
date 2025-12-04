{{-- resources/views/livewire/admin/mountains-crud.blade.php --}}
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header with Back Button -->
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="text-neutral-sub hover:text-neutral-text transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-neutral-text">Manajemen Gunung</h1>
            </div>
            <p class="text-sm text-neutral-sub ml-9">Kelola data gunung untuk sistem rekomendasi TOPSIS</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl border border-neutral-line shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-neutral-text mb-4">
                {{ $editingId ? 'Edit Gunung' : 'Tambah Gunung Baru' }}
            </h2>
            <form wire:submit.prevent="save">
                <div class="grid md:grid-cols-3 gap-4">
                    <!-- Nama Gunung -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">
                            Nama Gunung <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="name"
                            placeholder="Contoh: Gunung Semeru"
                            class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all"
                        >
                        @error('name') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- Elevasi -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">
                            Elevasi (m) <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            wire:model="elevation_m"
                            placeholder="3676"
                            class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all"
                        >
                        @error('elevation_m') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- Provinsi -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="province"
                            placeholder="Jawa Timur"
                            class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all"
                        >
                        @error('province') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- Latitude -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">
                            Latitude
                        </label>
                        <input
                            type="number"
                            step="0.000001"
                            wire:model="lat"
                            placeholder="-8.1084"
                            class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all"
                        >
                        @error('lat') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- Longitude -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">
                            Longitude
                        </label>
                        <input
                            type="number"
                            step="0.000001"
                            wire:model="lng"
                            placeholder="112.9225"
                            class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all"
                        >
                        @error('lng') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select
                            wire:model="status"
                            class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all"
                        >
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 mt-6 pt-6 border-t border-neutral-line">
                    <button
                        type="submit"
                        class="inline-flex items-center px-6 py-2.5 rounded-lg bg-brand text-white font-medium hover:bg-indigo-700 transition-colors shadow-sm"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $editingId ? 'Update' : 'Simpan' }}
                    </button>

                    @if ($editingId)
                        <button
                            type="button"
                            wire:click="resetForm"
                            class="px-6 py-2.5 rounded-lg bg-gray-100 text-neutral-text font-medium hover:bg-gray-200 transition-colors"
                        >
                            Batal
                        </button>
                    @endif

                    @if (session('ok'))
                        <div class="flex items-center gap-2 text-sm text-green-700 bg-green-50 px-4 py-2 rounded-lg border border-green-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ session('ok') }}
                        </div>
                    @endif
                </div>
            </form>
        </div>

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
                                    {{ ucfirst($m->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <button
                                        wire:click="edit({{ $m->id }})"
                                        class="px-3 py-1.5 rounded-lg bg-brand text-white text-xs hover:bg-indigo-700 transition-colors"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        wire:click="delete({{ $m->id }})"
                                        onclick="return confirm('Yakin ingin menghapus {{ $m->name }}?')"
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

            <!-- Pagination -->
            @if($mountains->hasPages())
                <div class="px-6 py-4 border-t border-neutral-line">
                    {{ $mountains->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
