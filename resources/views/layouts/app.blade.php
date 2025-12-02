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

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="font-inter antialiased bg-neutral-bg text-neutral-text">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="border border-neutral-line-b border border-neutral-line-neutral-line bg-white border border-neutral-line border border-neutral-line-neutral-line">
            <div class="max-w-7xl mx-auto px-4 h-14 flex items-center justify-between">
                <a href="{{ route('landing') }}" class="font-semibold text-neutral-text">SPK • Jalur Gunung</a>
                <div class="flex items-center gap-4 text-sm">
                    @auth
                        <div class="flex items-center gap-3">
                            <span class="text-neutral-sub">{{ auth()->user()->name }}</span>
                            @if(auth()->user()->hasRole(['admin', 'editor']))
                                <a href="{{ route('admin.dashboard') }}"
                                   class="text-neutral-sub hover:text-neutral-text">Dashboard Admin</a>
                            @else
                                <a href="{{ route('user.history') }}"
                                   class="text-neutral-sub hover:text-neutral-text">Riwayat</a>
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
            @yield('content')
        </main>

        <!-- Simple Footer for Admin Pages -->
        @if(!request()->is('/'))
        <footer class="bg-gray-50 border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <div class="text-center text-sm text-gray-500">
                    <p>&copy; {{ date('Y') }} SPK Pendakian - Powered by Laravel & TOPSIS Algorithm</p>
                </div>
            </div>
        </footer>
        @endif
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="fixed top-6 right-6 z-50 bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-neutral-line text-neutral-text px-4 py-3 rounded border border-neutral-line-lg shadow-soft">
            <div class="flex items-center gap-2">
                <span class="text-neutral-sub">✅</span>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-6 right-6 z-50 bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-danger text-danger px-4 py-3 rounded border border-neutral-line-lg shadow-soft">
            <div class="flex items-center gap-2">
                <span>❌</span>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if (session('ok'))
        <div class="fixed top-6 right-6 z-50 bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-ok text-ok px-4 py-3 rounded border border-neutral-line-lg shadow-soft">
            <div class="flex items-center gap-2">
                <span>ℹ️</span>
                <span class="text-sm">{{ session('ok') }}</span>
            </div>
        </div>
    @endif

    <!-- Auhide flash messages -->
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