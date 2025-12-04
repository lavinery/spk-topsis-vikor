{{-- resources/views/livewire/admin/assessments-monitor.blade.php --}}
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button href="{{ route('admin.dashboard') }}" text="Kembali ke Dashboard" />
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Monitoring Assessments</h1>
        <input type="text" wire:model.live="q" placeholder="Cari judul..." class="w-72 px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent">
    </div>

    <div class="mt-6 bg-white rounded-xl border border-neutral-line shadow-sm overflow-hidden">
        <table class="min-w-full text-sm border-collapse">
            <thead class="bg-gray-50 border-b border-neutral-line">
                <tr class="text-left">
                    <th class="px-4 py-3 border-r border-neutral-line">#</th>
                    <th class="px-4 py-3 border-r border-neutral-line">User</th>
                    <th class="px-4 py-3 border-r border-neutral-line">Judul</th>
                    <th class="px-4 py-3 border-r border-neutral-line">Status</th>
                    <th class="px-4 py-3 border-r border-neutral-line">Top-5</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $i=>$row)
                    <tr class="border-b border-neutral-line hover:bg-gray-50">
                        <td class="px-4 py-3 border-r border-neutral-line">{{ $list->firstItem() + $i }}</td>
                        <td class="px-4 py-3 border-r border-neutral-line">
                            <div class="text-sm">
                                <div class="font-medium text-neutral-text">{{ $row['a']->user->name ?? '—' }}</div>
                                <div class="text-xs text-neutral-sub">ID: {{ $row['a']->user_id }}</div>
                            </div>
                        </td>
                        <td class="px-4 py-3 border-r border-neutral-line">{{ $row['a']->title ?? '—' }}</td>
                        <td class="px-4 py-3 border-r border-neutral-line">
                            <span class="px-2 py-0.5 rounded-full text-xs
                                @if($row['a']->status==='done') bg-green-100 text-green-700
                                @elseif($row['a']->status==='running') bg-blue-100 text-blue-700
                                @elseif($row['a']->status==='failed') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700
                                @endif">{{ $row['a']->status }}</span>
                        </td>
                        <td class="px-4 py-3 border-r border-neutral-line">
                            <ul class="text-xs text-gray-700 space-y-1">
                                @foreach ($row['top5'] as $t)
                                    <li class="flex items-center justify-between gap-3">
                                        <span class="truncate">{{ $t['name'] }}</span>
                                        <span class="font-semibold tabular-nums">{{ number_format($t['cc'],4) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('assess.result', $row['a']->id) }}" class="px-3 py-1.5 rounded-lg bg-brand text-white text-xs hover:bg-indigo-700">Lihat Hasil</a>
                                <a href="{{ route('assess.steps', $row['a']->id) }}" class="px-3 py-1.5 rounded-lg bg-gray-100 text-xs hover:bg-gray-200">Tahapan</a>
                                <button
                                    wire:click="delete({{ $row['a']->id }})"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus assessment ini?')"
                                    class="px-3 py-1.5 rounded-lg bg-red-100 text-red-700 text-xs hover:bg-red-200"
                                >
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $list->links() }}</div>
</div>