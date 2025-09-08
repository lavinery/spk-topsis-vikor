@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-neutral-bg">
    <div class="w-full max-w-md mx-4">
        <!-- Back Button -->
        <div class="mb-6">
            <x-back-button href="{{ route('landing') }}" text="Kembali ke Beranda" />
        </div>
        
        <div class="bg-white border border-neutral-line rounded-2xl p-8 shadow-soft">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-16 bg-brand rounded-2xl flex items-center justify-center text-2xl text-neutral-text">
                        🏔️
                    </div>
                </div>
                
                <h1 class="text-3xl font-semibold text-neutral-text mb-2">
                    Welcome Back!
                </h1>
                <p class="text-neutral-sub">
                    Ready for your next adventure? 🚀
                </p>
            </div>
            
            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-semibold text-neutral-text mb-2">
                        📧 Email Address
                    </label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-3 rounded-lg border border-neutral-line text-neutral-text placeholder-neutral-sub focus:border-brand focus:ring-2 focus:ring-brand/20 transition-all duration-200"
                           placeholder="your@email.com"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="text-neutral-sub text-sm mt-2 flex items-center">
                            <span class="mr-2">⚠️</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-semibold text-neutral-text mb-2">
                        🔒 Password
                    </label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 rounded-lg border border-neutral-line text-neutral-text placeholder-neutral-sub focus:border-brand focus:ring-2 focus:ring-brand/20 transition-all duration-200"
                           placeholder="Enter your password">
                    @error('password')
                        <p class="text-neutral-sub text-sm mt-2 flex items-center">
                            <span class="mr-2">⚠️</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border border-neutral-line text-brand focus:ring-brand/20">
                        <span class="ml-2 text-sm text-neutral-sub">Remember me</span>
                    </label>
                    
                    <a href="#" class="text-sm text-brand hover:opacity-80">
                        Forgot password?
                    </a>
                </div>
                
                <button type="submit" class="w-full bg-brand text-neutral-text py-3 rounded-lg font-semibold hover:opacity-90 transition-all duration-200">
                    🚀 Login to Adventure
                </button>
            </form>
            
            <!-- Register Link -->
            <div class="mt-8 text-center">
                <p class="text-neutral-sub text-sm">
                    Don't have an account? 
                    <a href="#" class="text-brand hover:opacity-80 font-semibold">
                        Contact admin
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection