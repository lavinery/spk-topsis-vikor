{{-- resources/views/livewire/admin/constraints-crud.blade.php --}}
<div class="max-w-7xl mx-auto p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button href="{{ route('admin.dashboard') }}" text="Kembali ke Dashboard" />
    </div>
    

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Constraints</h1>
        <input type="text" wire:model.live="q" placeholder="Cari..." class="rounded-lg border-gray-300">
    </div>

    <div class="bg-white border rounded-2xl p-4">
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm">Nama Constraint</label>
                    <input type="text" wire:model="name" class="mt-1 w-full rounded-lg border-gray-300" placeholder="CardioHigh_vs_Slope">
                    @error('name') <div class="text-xs text-rose-600">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="text-sm">Action</label>
                    <select wire:model="action" class="mt-1 w-full rounded-lg border-gray-300">
                        <option value="exclude_alternative">Exclude Alternative</option>
                        <option value="warn_only">Warn Only</option>
                    </select>
                    @error('action') <div class="text-xs text-rose-600">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <input id="active" type="checkbox" wire:model="active" class="rounded border-gray-300">
                <label for="active" class="text-sm">Active</label>
            </div>
            
            <div>
                <label class="text-sm">Expression JSON</label>
                <div class="mt-1 text-xs text-gray-600 mb-2">
                    Format: {"field": "user.C3", "op": "eq", "value": "high", "then": {"field": "route.slope_class", "op": "lte", "value": 3}}
                </div>
                <textarea wire:model="expr_json" rows="8" class="w-full rounded-lg border-gray-300 font-mono text-sm" placeholder='{"field": "user.C3", "op": "eq", "value": "high", "then": {"field": "route.slope_class", "op": "lte", "value": 3}}'></textarea>
                @error('expr_json') <div class="text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>
            
            <div class="flex items-center gap-2">
                <button class="px-4 py-2 rounded-xl bg-brand text-white">Simpan</button>
                @if ($editingId)
                    <button type="button" wire:click="resetForm" class="px-4 py-2 rounded-xl bg-gray-100">Batal</button>
                @endif
                @if (session('ok')) <span class="text-sm text-emerald-600">{{ session('ok') }}</span> @endif
            </div>
        </form>
    </div>

    {{-- Constraint Examples --}}
    <div class="mt-6 bg-gray-50 border rounded-2xl p-4">
        <h3 class="text-sm font-semibold mb-2">Constraint Examples:</h3>
        <div class="space-y-2 text-xs">
            <div class="bg-white p-2 rounded border">
                <div class="font-semibold">CardioHigh_vs_Slope:</div>
                <div class="text-gray-600">If user cardio level is high, exclude routes with slope class > 3</div>
            </div>
            <div class="bg-white p-2 rounded border">
                <div class="font-semibold">Closed_Mountain:</div>
                <div class="text-gray-600">Exclude routes from mountains with status 'closed'</div>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white border rounded-2xl overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Action</th>
                    <th class="px-4 py-2">Expression</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($constraints as $c)
                    <tr class="border-t">
                        <td class="px-4 py-2 font-medium">{{ $c->name }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-0.5 rounded-full text-xs
                                @class([
                                    'bg-white text-neutral-text' => $c->action === 'exclude_alternative',
                                    'bg-white text-neutral-text' => $c->action === 'warn_only',
                                ])">{{ $c->action }}</span>
                        </td>
                        <td class="px-4 py-2">
                            <div class="text-xs font-mono bg-gray-100 p-1 rounded max-w-xs truncate">
                                {{ json_encode($c->expr_json) }}
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-0.5 rounded-full text-xs
                                @class([
                                    'bg-white text-neutral-text' => $c->active,
                                    'bg-gray-50 text-gray-700' => !$c->active,
                                ])">{{ $c->active ? 'Active' : 'Inactive' }}</span>
                        </td>
                        <td class="px-4 py-2">
                            <button wire:click="edit({{ $c->id }})" class="px-3 py-1.5 rounded bg-gray-100 text-xs">Edit</button>
                            <button wire:click="delete({{ $c->id }})" class="px-3 py-1.5 rounded bg-white text-neutral-text text-xs">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $constraints->links() }}</div>
    </div>
</div>