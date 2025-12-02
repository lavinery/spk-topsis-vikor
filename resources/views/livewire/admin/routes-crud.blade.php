{{-- resources/views/livewire/admin/routes-crud.blade.php --}}
<div class="max-w-7xl mx-auto p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button href="{{ route('admin.dashboard') }}" text="Kembali ke Dashboard" />
    </div>
    

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Routes</h1>
        <div class="flex items-center gap-3">
            <input type="text" wire:model.live="q" placeholder="Cari..." class="w-72 px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent">
        </div>
    </div>

    {{-- Import Form --}}
    <div class="bg-white border border-blue-200 rounded-xl p-4 mb-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-2">📥 Import Routes</h3>
        <p class="text-sm text-blue-600 mb-3">Upload Excel/CSV file to import multiple routes at once.</p>
        
        @if (session('ok'))
            <div class="mb-3 p-3 bg-green-100 border border-green-300 rounded-lg text-neutral-text text-sm">
                ✅ {{ session('ok') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="mb-3 p-3 bg-red-100 border border-red-300 rounded-lg text-neutral-text text-sm">
                ❌ {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ route('admin.routes.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
            @csrf
            <input type="file" name="file" accept=".xlsx,.csv" class="rounded-lg border-gray-300 text-sm" required>
            <button type="submit" class="px-4 py-2 rounded-lg bg-brand text-white text-sm hover:bg-brand">
                📤 Import
            </button>
        </form>
        
        <div class="mt-2 text-xs text-gray-600">
            <strong>Required columns:</strong> mountain_name, route_name, distance_km, elevation_gain_m, slope_class, land_cover_key, water_sources_score, support_facility_score, permit_required, province, elevation_m
            <br>
            <a href="{{ asset('templates/routes_import_template.csv') }}" download class="text-brand hover:text-brand underline">
                📄 Download CSV Template
            </a>
        </div>
    </div>

    <div class="ui-card">
        <form wire:submit.prevent="save" class="grid md:grid-cols-3 gap-4">
            <div class="ui-field">
                <label class="ui-label">Gunung</label>
                <select wire:model="mountain_id" class="w-full">
                    <option value="">-- pilih --</option>
                    @foreach ($mountains as $m) <option value="{{ $m->id }}">{{ $m->name }}</option> @endforeach
                </select>
                @error('mountain_id') <div class="text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>
            <div class="ui-field">
                <label class="ui-label">Nama Jalur</label>
                <input type="text" wire:model="name" class="w-full">
                @error('name') <div class="text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>
            <div class="ui-field">
                <label class="ui-label">Panjang (km)</label>
                <input type="number" step="0.01" wire:model="distance_km" class="w-full">
            </div>
            <div class="ui-field">
                <label class="ui-label">Elevasi (m)</label>
                <input type="number" wire:model="elevation_gain_m" class="w-full">
            </div>
            <div class="ui-field">
                <label class="ui-label">Kelas Kecuraman (1–5)</label>
                <input type="number" min="1" max="5" wire:model="slope_class" class="w-full">
            </div>
            <div class="ui-field">
                <label class="ui-label">Tutupan Lahan (key)</label>
                <input type="text" wire:model="land_cover_key" class="w-full" placeholder="hutan-lebat / savana / ...">
            </div>
            <div class="ui-field">
                <label class="ui-label">Sumber Air (0–10)</label>
                <input type="number" min="0" max="10" wire:model="water_sources_score" class="w-full">
            </div>
            <div class="ui-field">
                <label class="ui-label">Sarana Pendukung (0–10)</label>
                <input type="number" min="0" max="10" wire:model="support_facility_score" class="w-full">
            </div>
            <div class="ui-field flex items-center gap-2">
                <input id="permit" type="checkbox" wire:model="permit_required">
                <label for="permit" class="text-sm">Perlu Izin</label>
            </div>
            <div class="md:col-span-3 flex items-center gap-2">
                <button class="px-4 py-2 rounded-xl bg-brand text-white">Simpan</button>
                @if ($editingId)
                    <button type="button" wire:click="resetForm" class="px-4 py-2 rounded-xl bg-gray-100">Batal</button>
                @endif
                @if (session('ok')) <span class="text-sm text-emerald-600">{{ session('ok') }}</span> @endif
            </div>
        </form>
    </div>

    <div class="mt-6 bg-white rounded-xl border border-neutral-line overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="px-4 py-2">Gunung</th>
                    <th class="px-4 py-2">Jalur</th>
                    <th class="px-4 py-2">Km</th>
                    <th class="px-4 py-2">Gain</th>
                    <th class="px-4 py-2">Slope</th>
                    <th class="px-4 py-2">Lahan</th>
                    <th class="px-4 py-2">Air</th>
                    <th class="px-4 py-2">Sarana</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($routes as $r)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $r->mountain?->name }}</td>
                        <td class="px-4 py-2">{{ $r->name }}</td>
                        <td class="px-4 py-2">{{ $r->distance_km }}</td>
                        <td class="px-4 py-2">{{ $r->elevation_gain_m }}</td>
                        <td class="px-4 py-2">{{ $r->slope_class }}</td>
                        <td class="px-4 py-2">{{ $r->land_cover_key }}</td>
                        <td class="px-4 py-2">{{ $r->water_sources_score }}</td>
                        <td class="px-4 py-2">{{ $r->support_facility_score }}</td>
                        <td class="px-4 py-2">
                            <button wire:click="edit({{ $r->id }})" class="px-3 py-1.5 rounded bg-gray-100 text-xs">Edit</button>
                            <button wire:click="delete({{ $r->id }})" class="px-3 py-1.5 rounded bg-white text-neutral-text text-xs">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $routes->links() }}</div>
    </div>
</div>