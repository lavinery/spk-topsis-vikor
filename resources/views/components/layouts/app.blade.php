<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SPK-TOPSIS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: { 
                            DEFAULT: '#4f46e5'          // SATU aksen (indigo 600)
                        },
                        neutral: {
                            bg:   '#ffffff',                       // page bg
                            card: '#ffffff',                       // card bg
                            line: '#e5e7eb',                       // border border-neutral-lines
                            text: '#111827',                       // primary text
                            sub:  '#6b7280'                        // secondary text
                        },
                        ok:     { 
                            DEFAULT:'#059669' 
                        },           // dipakai hanya utk "DIBUKA"
                        danger: { 
                            DEFAULT:'#dc2626' 
                        }            // dipakai hanya utk "DITUTUP"
                    },
                    boxShadow: { 
                        soft: '0 4px 20px rgba(0,0,0,.04)' 
                    }
                }
            }
        }
    </script>
    
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="font-inter antialiased bg-neutral-bg text-neutral-text">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="border-b border-neutral-line bg-white">
            <div class="max-w-7xl mx-auto px-4 h-14 flex items-center justify-between">
                <a href="{{ route('landing') }}" class="font-semibold text-neutral-text">SPK • Jalur Gunung</a>
                <div class="flex items-center gap-4 text-sm">
                    @auth
                        <div class="flex items-center gap-3">
                            <span class="text-neutral-sub">Halo, {{ auth()->user()->name }}</span>
                            @if(auth()->user()->hasRole(['admin', 'editor']))
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="text-neutral-sub hover:text-neutral-text">Admin</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-neutral-sub hover:text-neutral-text">Logout</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-neutral-sub hover:text-neutral-text">Login</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="border-t border-neutral-line bg-white mt-20">
            <div class="max-w-7xl mx-auto py-10 px-4">
                <div class="text-center">
                    <div class="flex justify-center items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-brand rounded-2xl flex items-center justify-center text-2xl text-white">
                            🏔️
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-neutral-text">SPK-TOPSIS</h3>
                            <p class="text-sm text-neutral-sub">Sistem Pendukung Keputusan</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="p-4 rounded-lg border border-neutral-line">
                            <div class="text-2xl mb-2">🎯</div>
                            <h4 class="font-semibold text-neutral-text mb-1">Smart Recommendations</h4>
                            <p class="text-sm text-neutral-sub">Rekomendasi jalur gunung berdasarkan profil Anda</p>
                        </div>
                        
                        <div class="p-4 rounded-lg border border-neutral-line">
                            <div class="text-2xl mb-2">⚡</div>
                            <h4 class="font-semibold text-neutral-text mb-1">TOPSIS Algorithm</h4>
                            <p class="text-sm text-neutral-sub">Algoritma pengambilan keputusan untuk seleksi jalur optimal</p>
                        </div>
                        
                        <div class="p-4 rounded-lg border border-neutral-line">
                            <div class="text-2xl mb-2">🌟</div>
                            <h4 class="font-semibold text-neutral-text mb-1">Modern Interface</h4>
                            <p class="text-sm text-neutral-sub">Antarmuka modern dan intuitif</p>
                        </div>
                    </div>
                    
                    <div class="border-t border-neutral-line pt-6">
                        <p class="text-sm text-neutral-sub mb-1">
                            &copy; {{ date('Y') }} SPK-TOPSIS - Sistem Pendukung Keputusan Rekomendasi Jalur Gunung
                        </p>
                        <p class="text-xs text-neutral-sub">
                            Powered by Laravel & TOPSIS Algorithm
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="fixed top-6 right-6 z-50 bg-white border border-neutral-line text-neutral-text px-4 py-3 rounded-lg shadow-soft">
            <div class="flex items-center gap-2">
                <span class="text-neutral-sub">✅</span>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-6 right-6 z-50 bg-white border border-neutral-line text-danger px-4 py-3 rounded-lg shadow-soft">
            <div class="flex items-center gap-2">
                <span>❌</span>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if (session('ok'))
        <div class="fixed top-6 right-6 z-50 bg-white border border-neutral-line text-ok px-4 py-3 rounded-lg shadow-soft">
            <div class="flex items-center gap-2">
                <span>ℹ️</span>
                <span class="text-sm">{{ session('ok') }}</span>
            </div>
        </div>
    @endif

    <!-- Auto-hide flash messages -->
    <script>
        setTimeout(function() {
            const messages = document.querySelectorAll('.fixed.top-6.right-6');
            messages.forEach(function(message) {
                message.style.opacity = '0';
                message.style.transform = 'translateX(100%)';
                setTimeout(function() {
                    message.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html>
