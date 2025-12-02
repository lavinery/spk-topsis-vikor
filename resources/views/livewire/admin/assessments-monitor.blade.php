{{-- resources/views/livewire/admin/assessments-monitor.blade.php --}}
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button href="{{ route('admin.dashboard') }}" text="Kembali ke Dashboard" />
    </div>
    

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Monitoring Assessments</h1>
        <input type="text" wire:model.live="q" placeholder="Cari judul..." class="w-72 px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent">
    </div>

    <div class="mt-6 bg-white rounded-xl border border-neutral-line shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Judul</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Top-5</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $i=>$row)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $list->firstItem() + $i }}</td>
                        <td class="px-4 py-2">{{ $row['a']->title ?? '—' }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-0.5 rounded-full text-xs
                                @class([
                                    'bg-white text-neutral-text'=> $row['a']->status==='done',
                                    'bg-white text-neutral-text'=> $row['a']->status==='running',
                                    'bg-white text-neutral-text'=> $row['a']->status==='failed',
                                    'bg-gray-50 text-gray-700'=> $row['a']->status==='draft',
                                ])">{{ $row['a']->status }}</span>
                        </td>
                        <td class="px-4 py-2">
                            <ul class="text-xs text-gray-700 space-y-1">
                                @foreach ($row['top5'] as $t)
                                    <li class="flex items-center justify-between gap-3">
                                        <span class="truncate">{{ $t['name'] }}</span>
                                        <span class="font-semibold tabular-nums">{{ number_format($t['cc'],4) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex gap-2">
                                <a href="{{ route('assess.result', $row['a']->id) }}" class="px-3 py-1.5 rounded-lg bg-brand text-white text-xs">Lihat Hasil</a>
                                <a href="{{ route('assess.steps', $row['a']->id) }}" class="px-3 py-1.5 rounded-lg bg-gray-100 text-xs">Tahapan</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $list->links() }}</div>
</div>