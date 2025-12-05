<div class="max-w-7xl mx-auto p-6">
    <div class="mb-6">
        <x-back-button href="{{ route('admin.dashboard') }}" text="Kembali ke Dashboard" />
    </div>

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Fuzzy Terms</h1>
        <input type="text" wire:model.live="q" placeholder="Cari code..." class="w-72 px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent">
    </div>

    <div class="ui-card">
        <form wire:submit.prevent="save" class="grid md:grid-cols-4 gap-4">
            <div class="ui-field">
                <label class="ui-label">Kriteria (USER)</label>
                <select wire:model="criterion_id" class="w-full">
                    <option value="">-- pilih kriteria --</option>
                    @foreach($criteria as $c)
                        <option value="{{ $c->id }}">{{ $c->code }} — {{ $c->name }}</option>
                    @endforeach
                </select>
                @error('criterion_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="ui-field">
                <label class="ui-label">Code</label>
                <input wire:model="code" class="w-full" placeholder="RENDAH">
                @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="ui-field">
                <label class="ui-label">Label</label>
                <input wire:model="label" class="w-full" placeholder="Rendah">
            </div>
            <div class="ui-field">
                <label class="ui-label">Bentuk</label>
                <select wire:model="shape" class="w-full">
                    <option value="triangular">triangular</option>
                    <option value="trapezoidal">trapezoidal</option>
                </select>
            </div>
            <div class="md:col-span-4 ui-field">
                <label class="ui-label">Params JSON</label>
                <input wire:model="params_json" class="w-full font-mono" placeholder='[1,2,3] atau [1,2,4,5]'>
                <div class="ui-hint">Isi array angka sesuai bentuk. Contoh: triangular [a,b,c], trapezoidal [a,b,c,d]</div>
                @error('params_json') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
                    <th class="px-4 py-2">Kriteria</th>
                    <th class="px-4 py-2">Code</th>
                    <th class="px-4 py-2">Label</th>
                    <th class="px-4 py-2">Shape</th>
                    <th class="px-4 py-2">Params</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $r)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ optional($r->criterion)->code }}</td>
                        <td class="px-4 py-2 font-mono">{{ $r->code }}</td>
                        <td class="px-4 py-2">{{ $r->label }}</td>
                        <td class="px-4 py-2">{{ $r->shape }}</td>
                        <td class="px-4 py-2 font-mono">{{ is_array($r->params_json) ? json_encode($r->params_json) : $r->params_json }}</td>
                        <td class="px-4 py-2">
                            <button wire:click="edit({{ $r->id }})" class="px-3 py-1.5 rounded bg-gray-100 text-xs hover:bg-gray-200">Edit</button>
                            <button wire:click="delete({{ $r->id }})" onclick="return confirm('Yakin ingin menghapus fuzzy term \'{{ $r->term }}\' untuk kriteria ini?')" class="px-3 py-1.5 rounded bg-red-50 text-red-700 text-xs hover:bg-red-100">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $list->links() }}</div>
    </div>
</div>


