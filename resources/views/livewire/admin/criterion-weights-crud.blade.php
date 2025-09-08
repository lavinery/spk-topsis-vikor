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
            <div class="flex gap-2">
                <button wire:click="normalizeWeights" 
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                    📊 Normalisasi Bobot
                </button>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="mb-4">
        <input type="text" wire:model.live="q" 
               placeholder="Cari kriteria..." 
               class="w-full max-w-md px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent">
    </div>

    <!-- Total Weight Info -->
    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-center justify-between">
            <span class="text-blue-800 font-medium">Total Bobot Saat Ini:</span>
            <span class="text-blue-900 font-bold text-lg">{{ number_format($totalWeight, 4) }}</span>
        </div>
        @if(abs($totalWeight - 1.0) > 0.001)
            <p class="text-blue-700 text-sm mt-1">
                ⚠️ Total bobot tidak sama dengan 1.0. Klik "Normalisasi Bobot" untuk menyesuaikan.
            </p>
        @else
            <p class="text-green-700 text-sm mt-1">
                ✅ Total bobot sudah normal (1.0)
            </p>
        @endif
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl border border-neutral-line p-6 mb-6">
        <h3 class="text-lg font-semibold text-neutral-text mb-4">
            {{ $editingId ? 'Edit Bobot Kriteria' : 'Tambah Bobot Kriteria' }}
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-neutral-text mb-2">Kriteria</label>
                <select wire:model="criterion_id" class="w-full px-3 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent">
                    <option value="">Pilih Kriteria</option>
                    @foreach($criteria as $criterion)
                        <option value="{{ $criterion->id }}">{{ $criterion->code }} - {{ $criterion->name }}</option>
                    @endforeach
                </select>
                @error('criterion_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-neutral-text mb-2">Bobot (0-1)</label>
                <input type="number" wire:model="weight" step="0.001" min="0" max="1"
                       class="w-full px-3 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent">
                @error('weight') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-neutral-text mb-2">Versi</label>
                <input type="text" wire:model="version" 
                       class="w-full px-3 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent">
                @error('version') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <div class="flex gap-2 mt-4">
            <button wire:click="save" 
                    class="px-4 py-2 bg-brand text-white rounded-lg hover:bg-indigo-700 transition-colors">
                {{ $editingId ? 'Update' : 'Simpan' }}
            </button>
            <button wire:click="resetForm" 
                    class="px-4 py-2 border border-neutral-line text-neutral-text rounded-lg hover:bg-gray-50 transition-colors">
                Reset
            </button>
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
                                <button wire:click="edit({{ $weight->id }})" 
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <button wire:click="delete({{ $weight->id }})" 
                                        onclick="return confirm('Yakin hapus bobot ini?')"
                                        class="text-red-600 hover:text-red-900">Hapus</button>
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

    <!-- Flash Messages -->
    @if (session()->has('ok'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('ok') }}
        </div>
    @endif

    @if (session()->has('err'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('err') }}
        </div>
    @endif
</div>
