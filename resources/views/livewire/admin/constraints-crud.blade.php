{{-- resources/views/livewire/admin/constraints-crud.blade.php --}}
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
                <h1 class="text-2xl font-bold text-neutral-text">Manajemen Constraints</h1>
            </div>
            <p class="text-sm text-neutral-sub ml-9">Kelola aturan constraint untuk filter alternatif</p>
        </div>

        <!-- Global Constraints Toggle -->
        <div class="bg-white rounded-xl border-2 {{ $constraintsEnabled ? 'border-green-500' : 'border-gray-300' }} shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <svg class="w-6 h-6 {{ $constraintsEnabled ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold {{ $constraintsEnabled ? 'text-green-900' : 'text-gray-700' }}">
                            Sistem Constraints
                        </h3>
                    </div>
                    <p class="text-sm {{ $constraintsEnabled ? 'text-green-700' : 'text-gray-600' }}">
                        @if($constraintsEnabled)
                            ✓ Sistem constraints <strong>AKTIF</strong> - Filter otomatis akan diterapkan saat perhitungan TOPSIS
                        @else
                            ○ Sistem constraints <strong>NONAKTIF</strong> - Semua constraints tidak akan dijalankan
                        @endif
                    </p>
                </div>
                <button
                    wire:click="toggleConstraintsSystem"
                    class="relative inline-flex items-center h-12 w-24 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand {{ $constraintsEnabled ? 'bg-green-500' : 'bg-gray-300' }}"
                >
                    <span class="sr-only">Toggle constraints system</span>
                    <span class="inline-block h-10 w-10 transform rounded-full bg-white shadow transition-transform {{ $constraintsEnabled ? 'translate-x-12' : 'translate-x-1' }}"></span>
                </button>
            </div>
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
                    placeholder="Cari nama constraint..."
                    class="flex-1 px-4 py-2 border-0 focus:ring-0 text-sm"
                >
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl border border-neutral-line shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-neutral-text mb-4">{{ $editingId ? 'Edit Constraint' : 'Tambah Constraint Baru' }}</h2>
            <form wire:submit.prevent="save">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">
                            Nama Constraint <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="name" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="CardioHigh_vs_Slope">
                        @error('name') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-text mb-2">
                            Action <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="action" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all">
                            <option value="exclude_alternative">Exclude Alternative</option>
                            <option value="warn_only">Warn Only</option>
                        </select>
                        @error('action') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="flex items-center gap-3 cursor-pointer p-4 rounded-lg border border-neutral-line hover:bg-gray-50 transition-colors">
                        <input id="active" type="checkbox" wire:model="active" class="w-4 h-4 text-brand border-neutral-line rounded focus:ring-brand">
                        <div>
                            <span class="text-sm font-medium text-neutral-text">Active</span>
                            <p class="text-xs text-neutral-sub mt-0.5">Gunakan constraint ini dalam perhitungan</p>
                        </div>
                    </label>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-neutral-text mb-2">Expression JSON</label>
                    <div class="text-xs text-neutral-sub mb-2">
                        Format: {"field": "user.C3", "op": "eq", "value": "high", "then": {"field": "route.slope_class", "op": "lte", "value": 3}}
                    </div>
                    <textarea wire:model="expr_json" rows="8" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all font-mono text-sm" placeholder='{"field": "user.C3", "op": "eq", "value": "high", "then": {"field": "route.slope_class", "op": "lte", "value": 3}}'></textarea>
                    @error('expr_json') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="flex items-center gap-3 pt-6 border-t border-neutral-line">
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 rounded-lg bg-brand text-white font-medium hover:bg-indigo-700 transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $editingId ? 'Update' : 'Simpan' }}
                    </button>
                    @if ($editingId)
                        <button type="button" wire:click="resetForm" class="px-6 py-2.5 rounded-lg bg-gray-100 text-neutral-text font-medium hover:bg-gray-200 transition-colors">
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

        <!-- Constraint Examples -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
            <h3 class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Contoh Constraint
            </h3>
            <div class="space-y-2">
                <div class="bg-white p-3 rounded-lg border border-blue-200">
                    <div class="font-semibold text-sm text-blue-900">CardioHigh_vs_Slope</div>
                    <div class="text-xs text-blue-700 mt-1">Jika tingkat kardio user tinggi, exclude rute dengan slope class > 3</div>
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-200">
                    <div class="font-semibold text-sm text-blue-900">Closed_Mountain</div>
                    <div class="text-xs text-blue-700 mt-1">Exclude rute dari gunung dengan status 'closed'</div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl border border-neutral-line shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-neutral-line">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expression</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-line">
                    @forelse ($constraints as $c)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-neutral-text">{{ $c->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full font-medium {{ $c->action === 'exclude_alternative' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $c->action === 'exclude_alternative' ? 'Exclude' : 'Warn Only' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-mono bg-gray-100 p-2 rounded max-w-xs truncate text-neutral-sub">
                                    {{ json_encode($c->expr_json) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 text-xs rounded-full font-medium {{ $c->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $c->active ? '✓ Active' : '○ Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="edit({{ $c->id }})" class="px-3 py-1.5 rounded-lg bg-brand text-white text-xs hover:bg-indigo-700 transition-colors">
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $c->id }})" onclick="return confirm('Yakin ingin menghapus constraint \'{{ $c->name }}\'?\n\nTipe: {{ ucfirst($c->type) }}\nKriteria: {{ $c->criterion_code }}\nOperator: {{ $c->operator }}\nNilai: {{ $c->value }}\n\nProses ini tidak dapat dibatalkan!')" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 text-xs hover:bg-red-100 transition-colors">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <p class="text-neutral-sub text-sm">Tidak ada data constraint</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            @if($constraints->hasPages())
                <div class="px-6 py-4 border-t border-neutral-line">
                    {{ $constraints->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
