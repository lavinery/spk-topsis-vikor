<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('landing') }}" class="text-neutral-sub hover:text-neutral-text">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-neutral-text">Riwayat Assessment</h1>
            </div>
            <button wire:click="$refresh" class="px-4 py-2 text-sm border border-neutral-line rounded-lg hover:bg-gray-50">
                Refresh
            </button>
        </div>

        <!-- Error Message -->
        @if(isset($error))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex items-center gap-2 text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Error:</span>
                    <span>{{ $error }}</span>
                </div>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-neutral-line shadow-sm p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" wire:model.live="search" placeholder="Cari judul..."
                       class="w-full px-4 py-2 border border-neutral-line rounded-lg">
                <select wire:model.live="statusFilter"
                        class="w-full px-4 py-2 border border-neutral-line rounded-lg">
                    <option value="all">Semua Status</option>
                    <option value="done">Done</option>
                    <option value="draft">Draft</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
        </div>

        <!-- Assessments List -->
        <div class="bg-white rounded-xl border border-neutral-line shadow-sm overflow-hidden">
            @if($assessments->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-500">Tidak ada assessment ditemukan</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @if(isset($isAdmin) && $isAdmin)
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                @endif
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($assessments as $assessment)
                                <tr class="hover:bg-gray-50">
                                    @if(isset($isAdmin) && $isAdmin)
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium">{{ $assessment->user->name ?? '—' }}</div>
                                        <div class="text-xs text-gray-500">{{ $assessment->user->email ?? '—' }}</div>
                                    </td>
                                    @endif
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium">{{ $assessment->title }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $assessment->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            {{ $assessment->status === 'done' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $assessment->status === 'draft' ? 'bg-gray-100 text-gray-700' : '' }}
                                            {{ $assessment->status === 'failed' ? 'bg-red-100 text-red-700' : '' }}">
                                            {{ ucfirst($assessment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if($assessment->status === 'done')
                                            <a href="{{ route('assess.result', $assessment->id) }}"
                                               class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs hover:bg-indigo-700">
                                                Hasil
                                            </a>
                                        @elseif($assessment->status === 'draft')
                                            <a href="{{ route('assess.wizard', $assessment->id) }}"
                                               class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs hover:bg-indigo-700">
                                                Lanjutkan
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $assessments->links() }}
        </div>
    </div>
</div>
