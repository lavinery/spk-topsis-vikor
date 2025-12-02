<div class="max-w-7xl mx-auto p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button href="{{ route('admin.dashboard') }}" text="Kembali ke Dashboard" />
    </div>
    
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Criteria Management</h1>
        <input type="text" wire:model.live="q" placeholder="Cari kode/nama..." class="w-72 px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent">
    </div>

    <div class="ui-card">
        <form wire:submit.prevent="save" class="grid md:grid-cols-4 gap-4">
            <div class="ui-field">
                <label class="ui-label">Code</label>
                <input wire:model="code" class="w-full" placeholder="C15">
            </div>
            <div class="ui-field">
                <label class="ui-label">Name</label>
                <input wire:model="name" class="w-full" placeholder="Ketinggian">
            </div>
            <div class="ui-field">
                <label class="ui-label">Source</label>
                <select wire:model="source" class="w-full">
                    <option value="USER">USER</option>
                    <option value="ROUTE" selected>ROUTE</option>
                    <option value="MOUNTAIN">MOUNTAIN</option>
                </select>
            </div>
            <div class="ui-field">
                <label class="ui-label">Type</label>
                <select wire:model="type" class="w-full">
                    <option value="benefit">benefit</option>
                    <option value="cost">cost</option>
                </select>
            </div>

            <div class="ui-field">
                <label class="ui-label">Data Type</label>
                <select wire:model="data_type" class="w-full">
                    <option value="numeric">numeric</option>
                    <option value="ordinal">ordinal</option>
                    <option value="categorical">categorical</option>
                    <option value="boolean">boolean</option>
                </select>
            </div>
            <div class="ui-field">
                <label class="ui-label">Scale</label>
                <select wire:model="scale" class="w-full">
                    <option value="numeric">numeric</option>
                    <option value="categorical">categorical</option>
                </select>
            </div>
            <div class="ui-field">
                <label class="ui-label">Unit</label>
                <input wire:model="unit" class="w-full" placeholder="m / km / 0..10">
            </div>
            <div class="ui-field">
                <label class="ui-label">Min Hint</label>
                <input type="number" step="0.001" wire:model="min_hint" class="w-full">
            </div>
            <div class="ui-field">
                <label class="ui-label">Max Hint</label>
                <input type="number" step="0.001" wire:model="max_hint" class="w-full">
            </div>

            <div class="ui-field flex items-center gap-2">
                <input type="checkbox" wire:model="active">
                <span class="text-sm">Active</span>
            </div>
            <div class="ui-field flex items-center gap-2">
                <input type="checkbox" wire:model="is_fuzzy">
                <span class="text-sm">Proses dengan Fuzzy?</span>
            </div>
            <div class="ui-field">
                <label class="ui-label">Sort</label>
                <input type="number" wire:model="sort_order" class="w-full">
            </div>

            <div class="md:col-span-4 flex items-center gap-2">
                <button class="px-4 py-2 rounded-xl bg-brand text-white">Simpan</button>
                @if($editingId)
                    <button type="button" wire:click="resetForm" class="px-4 py-2 rounded-xl bg-gray-100">Batal</button>
                @endif
                @if(session('ok'))
                    <span class="text-sm text-emerald-600">{{ session('ok') }}</span>
                @endif
            </div>
        </form>
    </div>

    <div class="mt-6 bg-white rounded-xl border border-neutral-line overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Code</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Source</th>
                    <th class="px-4 py-2">Data</th>
                    <th class="px-4 py-2">Fuzzy</th>
                    <th class="px-4 py-2">Type</th>
                    <th class="px-4 py-2">Scale</th>
                    <th class="px-4 py-2">Unit</th>
                    <th class="px-4 py-2">Active</th>
                    <th class="px-4 py-2">Sort</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $i => $c)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $list->firstItem() + $i }}</td>
                        <td class="px-4 py-2 font-mono">{{ $c->code }}</td>
                        <td class="px-4 py-2">{{ $c->name }}</td>
                        <td class="px-4 py-2">{{ $c->source }}</td>
                        <td class="px-4 py-2">{{ $c->data_type }}</td>
                        <td class="px-4 py-2">{{ $c->is_fuzzy ? 'Ya' : 'Tidak' }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $c->type === 'benefit' ? 'bg-white text-neutral-text' : 'bg-white text-neutral-text' }}">
                                {{ $c->type }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $c->scale }}</td>
                        <td class="px-4 py-2">{{ $c->unit ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $c->active ? 'bg-white text-neutral-text' : 'bg-gray-100 text-gray-500' }}">
                                {{ $c->active ? 'ON' : 'OFF' }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <div class="inline-flex rounded-lg border">
                                <button wire:click="up({{ $c->id }})" class="px-2 hover:bg-gray-100">▲</button>
                                <button wire:click="down({{ $c->id }})" class="px-2 hover:bg-gray-100">▼</button>
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            <button wire:click="edit({{ $c->id }})" class="px-3 py-1.5 rounded bg-gray-100 text-xs hover:bg-gray-200">Edit</button>
                            <button wire:click="delete({{ $c->id }})" class="px-3 py-1.5 rounded bg-white text-neutral-text text-xs hover:bg-rose-100" 
                                    onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $list->links() }}</div>
    </div>
</div>