{{-- resources/views/livewire/admin/mountains-crud.blade.php --}}
<div class="max-w-7xl mx-auto p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button href="{{ route('admin.dashboard') }}" text="Kembali ke Dashboard" />
    </div>
    

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Mountains</h1>
        <input type="text" wire:model.live="q" placeholder="Cari..." class="w-72 px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent">
    </div>
    <div class="ui-card">
        <form wire:submit.prevent="save" class="grid md:grid-cols-3 gap-4">
            <div class="ui-field">
                <label class="ui-label">Nama Gunung</label>
                <input type="text" wire:model="name" class="w-full">
                @error('name') <div class="text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>
            <div class="ui-field">
                <label class="ui-label">Elevasi (m)</label>
                <input type="number" wire:model="elevation_m" class="w-full">
                @error('elevation_m') <div class="text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>
            <div class="ui-field">
                <label class="ui-label">Provinsi</label>
                <input type="text" wire:model="province" class="w-full">
                @error('province') <div class="text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>
            <div class="ui-field">
                <label class="ui-label">Latitude</label>
                <input type="number" step="0.000001" wire:model="lat" class="w-full">
                @error('lat') <div class="text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>
            <div class="ui-field">
                <label class="ui-label">Longitude</label>
                <input type="number" step="0.000001" wire:model="lng" class="w-full">
                @error('lng') <div class="text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>
            <div class="ui-field">
                <label class="ui-label">Status</label>
                <select wire:model="status" class="w-full">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                @error('status') <div class="text-xs text-rose-600">{{ $message }}</div> @enderror
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
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Elevasi</th>
                    <th class="px-4 py-2">Provinsi</th>
                    <th class="px-4 py-2">Koordinat</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mountains as $m)
                    <tr class="border-t">
                        <td class="px-4 py-2 font-medium">{{ $m->name }}</td>
                        <td class="px-4 py-2">{{ number_format($m->elevation_m) }} m</td>
                        <td class="px-4 py-2">{{ $m->province }}</td>
                        <td class="px-4 py-2 text-xs">
                            @if($m->lat && $m->lng)
                                {{ number_format($m->lat, 6) }}, {{ number_format($m->lng, 6) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-0.5 rounded-full text-xs
                                @class([
                                    'bg-white text-neutral-text' => $m->status === 'active',
                                    'bg-gray-50 text-gray-700' => $m->status === 'inactive',
                                ])">{{ $m->status }}</span>
                        </td>
                        <td class="px-4 py-2">
                            <button wire:click="edit({{ $m->id }})" class="px-3 py-1.5 rounded bg-gray-100 text-xs">Edit</button>
                            <button wire:click="delete({{ $m->id }})" class="px-3 py-1.5 rounded bg-white text-neutral-text text-xs">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $mountains->links() }}</div>
    </div>
</div>