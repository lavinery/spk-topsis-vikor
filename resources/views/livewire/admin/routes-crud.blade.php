{{-- resources/views/livewire/admin/routes-crud.blade.php --}}
<div class="max-w-7xl mx-auto p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button href="{{ route('admin.dashboard') }}" text="Kembali ke Dashboard" />
    </div>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Routes</h1>
        <div>
            <input type="text" wire:model.live="q" placeholder="Cari..." class="w-72 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        </div>
    </div>

    {{-- Import Form --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6 shadow-sm">
        <h3 class="text-lg font-semibold text-blue-900 mb-2">📥 Import Routes</h3>
        <p class="text-sm text-blue-700 mb-4">Upload Excel/CSV file to import multiple routes at once.</p>

        @if (session('ok'))
            <div class="mb-3 p-3 bg-green-50 border border-green-300 rounded-lg text-green-800 text-sm">
                ✅ {{ session('ok') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-3 p-3 bg-red-50 border border-red-300 rounded-lg text-red-800 text-sm">
                ❌ {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.routes.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
            @csrf
            <input type="file" name="file" accept=".xlsx,.csv" class="border border-gray-300 rounded-lg text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
            <button type="submit" class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors">
                📤 Import
            </button>
        </form>

        <div class="mt-3 text-xs text-gray-600 bg-white p-3 rounded-lg border border-gray-200">
            <strong class="text-gray-700">Required columns:</strong> mountain_name, route_name, distance_km, elevation_gain_m, slope_class, land_cover_key, water_sources_score, support_facility_score, permit_required, province, elevation_m
            <br>
            <a href="{{ asset('templates/routes_import_template.csv') }}" download class="text-indigo-600 hover:text-indigo-700 underline inline-flex items-center mt-2">
                📄 Download CSV Template
            </a>
        </div>
    </div>

    {{-- Form Input --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tambah / Edit Route</h3>
        <form wire:submit.prevent="save" class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gunung</label>
                <select wire:model="mountain_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- pilih --</option>
                    @foreach ($mountains as $m) <option value="{{ $m->id }}">{{ $m->name }}</option> @endforeach
                </select>
                @error('mountain_id') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Jalur</label>
                <input type="text" wire:model="name" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                @error('name') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Panjang (km)</label>
                <input type="number" step="0.01" wire:model="distance_km" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Elevasi (m)</label>
                <input type="number" wire:model="elevation_gain_m" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas Kecuraman (1–5)</label>
                <input type="number" min="1" max="5" wire:model="slope_class" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tutupan Lahan (key)</label>
                <input type="text" wire:model="land_cover_key" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="hutan-lebat / savana / ...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sumber Air (0–10)</label>
                <input type="number" min="0" max="10" wire:model="water_sources_score" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sarana Pendukung (0–10)</label>
                <input type="number" min="0" max="10" wire:model="support_facility_score" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="flex items-center gap-2 pt-6">
                <input id="permit" type="checkbox" wire:model="permit_required" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                <label for="permit" class="text-sm font-medium text-gray-700">Perlu Izin</label>
            </div>
            <div class="md:col-span-3 flex items-center gap-3 pt-2">
                <button type="submit" class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                    Simpan
                </button>
                @if ($editingId)
                    <button type="button" wire:click="resetForm" class="px-6 py-2 rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                @endif
                @if (session('ok')) <span class="text-sm text-green-600 font-medium">✓ {{ session('ok') }}</span> @endif
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">Gunung</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">Jalur</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">Km</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">Gain</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">Slope</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">Lahan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">Air</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">Sarana</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($routes as $r)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">{{ $r->mountain?->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">{{ $r->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">{{ $r->distance_km }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">{{ $r->elevation_gain_m }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">{{ $r->slope_class }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 border-r border-gray-200">{{ $r->land_cover_key }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">{{ $r->water_sources_score }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">{{ $r->support_facility_score }}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center gap-2">
                                    <button wire:click="edit({{ $r->id }})" class="px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-medium hover:bg-indigo-100 transition-colors">
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $r->id }})" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 text-xs font-medium hover:bg-red-100 transition-colors">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
            {{ $routes->links() }}
        </div>
    </div>
</div>