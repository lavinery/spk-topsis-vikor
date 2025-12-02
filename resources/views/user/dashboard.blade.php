<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard User - SPK TOPSIS</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-neutral-text">
    <div class="min-h-screen">
        <!-- Navbar -->
        <nav class="bg-white shadow-sm border-b border-neutral-line">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-brand">SPK TOPSIS</h1>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-neutral-sub">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-neutral-text">Dashboard User</h2>
                <p class="text-neutral-sub mt-1">Selamat datang, {{ auth()->user()->name }}!</p>
            </div>

            <!-- Action Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Start Assessment Card -->
                <a href="{{ route('landing') }}" class="block p-6 bg-white rounded-xl border border-neutral-line shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-brand/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-neutral-text">Mulai Assessment Baru</h3>
                            <p class="text-sm text-neutral-sub mt-1">Mulai penilaian jalur pendakian baru berdasarkan profil Anda</p>
                        </div>
                    </div>
                </a>

                <!-- History Card -->
                <a href="{{ route('user.history') }}" class="block p-6 bg-white rounded-xl border border-neutral-line shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-neutral-text">Riwayat Assessment</h3>
                            <p class="text-sm text-neutral-sub mt-1">Lihat hasil assessment yang pernah Anda lakukan</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Recent Assessments -->
            <div class="bg-white rounded-xl border border-neutral-line shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-neutral-text">Assessment Terbaru</h3>
                    <a href="{{ route('user.history') }}" class="text-sm text-brand hover:underline">Lihat Semua</a>
                </div>

                @php
                    $recentAssessments = \App\Models\Assessment::where('user_id', auth()->id())
                        ->latest()
                        ->limit(5)
                        ->get();
                @endphp

                @if($recentAssessments->isEmpty())
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-neutral-sub">Belum ada assessment</p>
                        <a href="{{ route('landing') }}" class="inline-block mt-3 px-4 py-2 rounded-lg bg-brand text-white text-sm hover:bg-indigo-700 transition-colors">
                            Mulai Assessment Pertama
                        </a>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($recentAssessments as $assessment)
                            <div class="flex items-center justify-between p-4 border border-neutral-line rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex-1">
                                    <h4 class="font-medium text-neutral-text">{{ $assessment->title }}</h4>
                                    <p class="text-xs text-neutral-sub mt-1">
                                        {{ $assessment->created_at->format('d M Y, H:i') }}
                                        <span class="mx-2">•</span>
                                        <span class="px-2 py-0.5 rounded-full text-xs
                                            @if($assessment->status === 'done') bg-green-100 text-green-700
                                            @elseif($assessment->status === 'running') bg-blue-100 text-blue-700
                                            @elseif($assessment->status === 'failed') bg-red-100 text-red-700
                                            @else bg-gray-100 text-gray-700
                                            @endif">
                                            {{ ucfirst($assessment->status) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    @if($assessment->status === 'done')
                                        <a href="{{ route('assess.result', $assessment->id) }}" class="px-3 py-1.5 rounded-lg bg-brand text-white text-xs hover:bg-indigo-700 transition-colors">
                                            Lihat Hasil
                                        </a>
                                        <a href="{{ route('user.assessment.detail', $assessment->id) }}" class="px-3 py-1.5 rounded-lg bg-gray-100 text-xs hover:bg-gray-200 transition-colors">
                                            Detail
                                        </a>
                                    @elseif($assessment->status === 'draft')
                                        <a href="{{ route('assess.wizard', $assessment->id) }}" class="px-3 py-1.5 rounded-lg bg-brand text-white text-xs hover:bg-indigo-700 transition-colors">
                                            Lanjutkan
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
