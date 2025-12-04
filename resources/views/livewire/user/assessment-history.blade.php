<div class="min-h-screen bg-gray-50" wire:poll.5s>
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header with Back Button -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('landing') }}" class="text-neutral-sub hover:text-neutral-text">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-neutral-text">Riwayat Assessment</h1>
                </div>
                <button wire:click="$refresh" class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-sub hover:text-neutral-text border border-neutral-line rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
            <!-- Debug Info -->
            @if(config('app.debug'))
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                <div class="text-xs font-mono">
                    <div><strong>Debug Info:</strong></div>
                    <div>User ID: {{ auth()->id() }}</div>
                    <div>Total Records: {{ $assessments->total() }}</div>
                    <div>Current Page: {{ $assessments->currentPage() }}</div>
                    <div>Per Page: {{ $assessments->perPage() }}</div>
                    <div>Status Filter: {{ $statusFilter }}</div>
                    <div>Search: {{ $search ?: '(none)' }}</div>
                    <div>isEmpty: {{ $assessments->isEmpty() ? 'YES' : 'NO' }}</div>
                    <div>Count: {{ $assessments->count() }}</div>
                </div>
            </div>
            @endif

            <!-- Filters -->
            <div class="bg-white rounded-xl border border-neutral-line shadow-sm p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <input
                            type="text"
                            wire:model.live="search"
                            placeholder="Cari judul assessment..."
                            class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent"
                        >
                    </div>
                    <div>
                        <select
                            wire:model.live="statusFilter"
                            class="w-full px-4 py-2 border border-neutral-line rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent"
                        >
                            <option value="all">Semua Status</option>
                            <option value="done">Done</option>
                            <option value="draft">Draft</option>
                            <option value="running">Running</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Assessments List -->
            <div class="bg-white rounded-xl border border-neutral-line shadow-sm overflow-hidden">
                @if($assessments->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-neutral-sub">Tidak ada assessment ditemukan</p>
                    </div>
                @else
                    <!-- Wrapper untuk horizontal scroll di mobile -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-line">
                        <thead class="bg-gray-50">
                            <tr>
                                @if($isAdmin)
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">User</th>
                                @endif
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Judul</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Alternatif</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-line">
                            @foreach($assessments as $assessment)
                                <tr class="hover:bg-gray-50">
                                    @if($isAdmin)
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm">
                                            <div class="font-medium text-neutral-text">{{ $assessment->user->name ?? '—' }}</div>
                                            <div class="text-xs text-neutral-sub">{{ $assessment->user->email ?? '—' }}</div>
                                        </div>
                                    </td>
                                    @endif
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-neutral-text max-w-xs">{{ $assessment->title }}</div>
                                        <div class="text-xs text-neutral-sub">{{ $assessment->n_criteria }} kriteria</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-neutral-sub whitespace-nowrap">
                                        {{ $assessment->created_at->format('d M Y') }}<br>
                                        <span class="text-xs">{{ $assessment->created_at->format('H:i') }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($assessment->status === 'done') bg-green-100 text-green-700
                                            @elseif($assessment->status === 'running') bg-blue-100 text-blue-700
                                            @elseif($assessment->status === 'failed') bg-red-100 text-red-700
                                            @else bg-gray-100 text-gray-700
                                            @endif">
                                            {{ ucfirst($assessment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-neutral-text whitespace-nowrap">
                                        {{ $assessment->n_alternatives }} jalur
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm font-medium whitespace-nowrap">
                                        <div class="flex justify-end gap-2">
                                            @if($assessment->status === 'done')
                                                <a href="{{ route('assess.result', $assessment->id) }}" class="px-3 py-1.5 rounded-lg bg-brand text-white text-xs hover:bg-indigo-700 transition-colors">
                                                    Hasil
                                                </a>
                                                <a href="{{ route('user.assessment.detail', $assessment->id) }}" class="px-3 py-1.5 rounded-lg bg-gray-100 text-xs hover:bg-gray-200 transition-colors">
                                                    Detail
                                                </a>
                                            @elseif($assessment->status === 'draft')
                                                <a href="{{ route('assess.wizard', $assessment->id) }}" class="px-3 py-1.5 rounded-lg bg-brand text-white text-xs hover:bg-indigo-700 transition-colors">
                                                    Lanjutkan
                                                </a>
                                            @else
                                                <span class="text-xs text-neutral-sub">-</span>
                                            @endif
                                        </div>
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
