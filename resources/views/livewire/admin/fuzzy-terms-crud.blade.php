<div class="max-w-7xl mx-auto p-6">
    <div class="mb-6">
        <x-back-button href="{{ route('admin.dashboard') }}" text="Kembali ke Dashboard" />
    </div>

    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text">Fuzzy Terms</h1>
                <p class="text-sm text-neutral-sub">Kelola term fuzzy untuk kriteria kategorikal</p>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="create" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-brand text-white font-medium hover:bg-indigo-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Term
                </button>
                <button @click="$dispatch('open-import-modal')" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Impor
                </button>
            </div>
        </div>
    </div>

    @if (session('ok'))
        <div class="mb-6 flex items-center gap-2 text-sm text-green-700 bg-green-50 px-4 py-3 rounded-lg border border-green-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('ok') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-neutral-line p-4 mb-6">
        <input type="text" wire:model.live="q" placeholder="Cari code..." class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent">
    </div>

    <div class="bg-white rounded-xl border border-neutral-line overflow-hidden">
        <div class="overflow-x-auto">
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
                @forelse($list as $r)
                    <tr class="border-t hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-2">
                            <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ optional($r->criterion)->code }}</span>
                        </td>
                        <td class="px-4 py-2 font-mono font-medium">{{ $r->code }}</td>
                        <td class="px-4 py-2">{{ $r->label }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">{{ $r->shape }}</span>
                        </td>
                        <td class="px-4 py-2 font-mono text-xs">{{ is_array($r->params_json) ? json_encode($r->params_json) : $r->params_json }}</td>
                        <td class="px-4 py-2">
                            <div class="flex gap-2">
                                <button wire:click="edit({{ $r->id }})" class="px-3 py-1.5 rounded-lg bg-brand text-white text-xs hover:bg-indigo-700 transition-colors">
                                    Ubah
                                </button>
                                <button
                                    @click="$dispatch('open-delete-modal', {
                                        id: {{ $r->id }},
                                        name: '{{ $r->code }}',
                                        details: 'Kriteria: {{ optional($r->criterion)->code }}\nLabel: {{ $r->label }}\nShape: {{ $r->shape }}\nParams: {{ is_array($r->params_json) ? json_encode($r->params_json) : $r->params_json }}'
                                    })"
                                    class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 text-xs hover:bg-red-100 transition-colors">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>Tidak ada data fuzzy terms</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
        <div class="p-3 border-t border-neutral-line">{{ $list->links() }}</div>
    </div>

    {{-- Form Modal --}}
    <x-form-modal title="{{ $editingId ? 'Edit Fuzzy Term' : 'Tambah Fuzzy Term' }}">
        <form wire:submit.prevent="save">
            <div class="grid gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Kriteria (USER) <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="criterion_id" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all">
                        <option value="">-- Pilih Kriteria --</option>
                        @foreach($criteria as $c)
                            <option value="{{ $c->id }}">{{ $c->code }} — {{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('criterion_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Code <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="code" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="RENDAH">
                    @error('code') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">Label</label>
                    <input wire:model="label" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all" placeholder="Rendah">
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Bentuk <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="shape" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all">
                        <option value="triangular">Triangular</option>
                        <option value="trapezoidal">Trapezoidal</option>
                    </select>
                    @error('shape') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-text mb-2">
                        Params JSON <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="params_json" class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition-all font-mono" placeholder='[1,2,3] atau [1,2,4,5]'>
                    <p class="text-xs text-neutral-sub mt-1">Isi array angka sesuai bentuk. Triangular [a,b,c], Trapezoidal [a,b,c,d]</p>
                    @error('params_json') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-neutral-line">
                <button type="button" @click="$dispatch('close-form-modal')" class="px-5 py-2.5 rounded-lg bg-gray-100 text-neutral-text font-medium hover:bg-gray-200 transition-colors">
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-brand text-white font-medium hover:bg-indigo-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ $editingId ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </form>
    </x-form-modal>

    {{-- Delete Confirmation Modal --}}
    <x-delete-modal title="Hapus Fuzzy Term" onConfirm="delete" />

    {{-- Import Modal --}}
    <x-import-modal
        title="Impor Fuzzy Terms"
        route="{{ route('admin.fuzzy-terms.import') }}"
        templateFile="templates/fuzzy_terms_import_template.csv"
        requiredColumns="criterion_code, code, label, shape, params_json"
        description="Upload file Excel atau CSV untuk mengimport banyak fuzzy terms sekaligus"
    />
</div>
