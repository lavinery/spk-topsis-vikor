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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        // Primary Brand Color - Modern Indigo
                        primary: {
                            50:  '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',  // Main brand color
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        // Success - Soft Green
                        success: {
                            50:  '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                        },
                        // Warning - Warm Amber
                        warning: {
                            50:  '#fffbeb',
                            100: '#fef3c7',
                            500: '#f59e0b',
                            600: '#d97706',
                        },
                        // Danger - Soft Red
                        danger: {
                            50:  '#fef2f2',
                            100: '#fee2e2',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                        },
                        // Neutral Gray Scale
                        neutral: {
                            50:  '#fafafa',
                            100: '#f5f5f5',
                            200: '#e5e5e5',
                            300: '#d4d4d4',
                            400: '#a3a3a3',
                            500: '#737373',
                            600: '#525252',
                            700: '#404040',
                            800: '#262626',
                            900: '#171717',
                        }
                    },
                    boxShadow: {
                        'soft': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                        'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                        'hover': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-in-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideIn: {
                            '0%': { transform: 'translateX(100%)' },
                            '100%': { transform: 'translateX(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Livewire Styles -->
    @livewireStyles

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f5f5f5;
        }
        ::-webkit-scrollbar-thumb {
            background: #d4d4d4;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a3a3a3;
        }
    </style>
</head>
<body class="font-sans antialiased bg-neutral-50 text-neutral-800">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white border-b border-neutral-200 sticky top-0 z-40 shadow-soft">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo/Brand -->
                    <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                        <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l7 7-7 7M12 3l7 7-7 7"/>
                            </svg>
                        </div>
                        <span class="font-bold text-lg text-neutral-800 group-hover:text-primary-600 transition-colors">
                            SPK Pendakian Gunung
                        </span>
                    </a>

                    <!-- Navigation Links -->
                    <div class="flex items-center gap-6">
                        @auth
                            <div class="flex items-center gap-4">
                                <!-- User Info -->
                                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-neutral-50 rounded-lg">
                                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-semibold text-primary-700">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <span class="text-sm font-medium text-neutral-700">{{ auth()->user()->name }}</span>
                                </div>

                                <!-- Quick Links -->
                                @if(auth()->user()->hasRole(['admin', 'editor']))
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="text-sm font-medium text-neutral-600 hover:text-primary-600 transition-colors">
                                        Dashboard Admin
                                    </a>
                                @else
                                    <a href="{{ route('user.history') }}"
                                       class="text-sm font-medium text-neutral-600 hover:text-primary-600 transition-colors">
                                        Riwayat Penilaian
                                    </a>
                                @endif

                                <!-- Logout Button -->
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="px-4 py-2 text-sm font-medium text-neutral-700 hover:text-danger-600 hover:bg-danger-50 rounded-lg transition-colors">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                               class="px-6 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg shadow-sm hover:shadow-md transition-all">
                                Masuk
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        @if(!request()->is('/'))
        <footer class="bg-white border-t border-neutral-200 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <!-- Copyright -->
                    <div class="text-sm text-neutral-500">
                        <p>&copy; {{ date('Y') }} <span class="font-semibold text-neutral-700">SPK Pendakian Gunung</span></p>
                        <p class="mt-1">Sistem Pendukung Keputusan menggunakan Metode TOPSIS</p>
                    </div>

                    <!-- Tech Stack Badge -->
                    <div class="flex items-center gap-2 text-xs text-neutral-400">
                        <span class="px-2 py-1 bg-neutral-100 rounded">Laravel</span>
                        <span class="px-2 py-1 bg-neutral-100 rounded">Livewire</span>
                        <span class="px-2 py-1 bg-neutral-100 rounded">Tailwind CSS</span>
                    </div>
                </div>
            </div>
        </footer>
        @endif
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Toast Notifications -->
    <div id="toast-container" class="fixed top-6 right-6 z-50 space-y-3 max-w-md">
        <!-- Success Toast -->
        @if (session('success'))
            <div class="animate-slide-in bg-white rounded-lg shadow-hover border-l-4 border-success-500 p-4" x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-success-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-neutral-800">Berhasil!</p>
                        <p class="text-sm text-neutral-600 mt-0.5">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <!-- Error Toast -->
        @if (session('error'))
            <div class="animate-slide-in bg-white rounded-lg shadow-hover border-l-4 border-danger-500 p-4" x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-danger-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-danger-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-neutral-800">Terjadi Kesalahan</p>
                        <p class="text-sm text-neutral-600 mt-0.5">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <!-- Info Toast -->
        @if (session('ok') || session('info'))
            <div class="animate-slide-in bg-white rounded-lg shadow-hover border-l-4 border-primary-500 p-4" x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-neutral-800">Informasi</p>
                        <p class="text-sm text-neutral-600 mt-0.5">{{ session('ok') ?? session('info') }}</p>
                    </div>
                    <button @click="show = false" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Auto-hide Toast Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toastContainer = document.getElementById('toast-container');
            if (toastContainer && toastContainer.children.length > 0) {
                setTimeout(() => {
                    Array.from(toastContainer.children).forEach(toast => {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateX(100%)';
                        toast.style.transition = 'all 0.3s ease-out';
                        setTimeout(() => toast.remove(), 300);
                    });
                }, 5000);
            }
        });
    </script>
</body>
</html>
