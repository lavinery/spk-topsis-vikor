<div class="max-w-6xl mx-auto p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button href="{{ route('admin.dashboard') }}" text="Kembali ke Dashboard" />
    </div>
    
    <h1 class="text-2xl font-bold mb-4">Category Maps Management</h1>
    
    <div class="ui-card">
        <form wire:submit.prevent="save" class="grid md:grid-cols-4 gap-4">
            <div class="ui-field">
                <label class="ui-label">Criterion</label>
                <select wire:model="criterion_id" class="w-full">
                    <option value="">-- pilih kriteria kategorikal --</option>
                    @foreach($criteria as $c)
                        <option value="{{ $c->id }}">{{ $c->code }} — {{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ui-field">
                <label class="ui-label">Key</label>
                <input wire:model="key" class="w-full" placeholder="hutan-lebat">
            </div>
            <div class="ui-field">
                <label class="ui-label">Score (0..1)</label>
                <input type="number" min="0" max="1" step="0.01" wire:model="score" class="w-full">
            </div>
            <div class="ui-field">
                <label class="ui-label">Label</label>
                <input wire:model="label" class="w-full" placeholder="Hutan lebat">
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
        <div class="p-3 flex items-center gap-3">
            <input type="text" wire:model.live="q" placeholder="Cari key..." class="w-64 px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent">
            <select wire:model.live="criterion_id" class="px-3 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent">
                <option value="">Semua kriteria</option>
                @foreach($criteria as $c)
                    <option value="{{ $c->id }}">{{ $c->code }} — {{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Criterion</th>
                    <th class="px-4 py-2">Key</th>
                    <th class="px-4 py-2">Score</th>
                    <th class="px-4 py-2">Label</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $i => $r)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $list->firstItem() + $i }}</td>
                        <td class="px-4 py-2">
                            <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $r->criterion?->code }}</span>
                            <div class="text-xs text-gray-600">{{ $r->criterion?->name }}</div>
                        </td>
                        <td class="px-4 py-2 font-mono">{{ $r->key }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs {{ $r->score >= 0.7 ? 'bg-green-100 text-green-800' : ($r->score >= 0.4 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ number_format($r->score, 3) }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $r->label ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <button wire:click="edit({{ $r->id }})" class="px-3 py-1.5 rounded bg-gray-100 text-xs hover:bg-gray-200">Edit</button>
                            <button wire:click="delete({{ $r->id }})" class="px-3 py-1.5 rounded bg-red-50 text-red-700 text-xs hover:bg-red-100"
                                    onclick="return confirm('Yakin ingin menghapus category map \'{{ $r->key }}\' dengan skor {{ $r->score }}?')">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($list->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <p>Tidak ada data category maps.</p>
                <p class="text-sm">Tambahkan mapping untuk kriteria kategorikal.</p>
            </div>
        @endif
        
        <div class="p-3">{{ $list->links() }}</div>
    </div>
</div>