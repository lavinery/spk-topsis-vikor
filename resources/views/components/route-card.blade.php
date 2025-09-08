{{-- Route Card Component - Monokrom Design --}}
@props([
    'route' => null,
    'mountain' => null,
    'url' => '#',
    'showStatus' => true,
    'showRating' => true,
    'compact' => false
])

@php
    $route = $route ?? $mountain->routes->first();
    $mountain = $mountain ?? $route->mountain;
    $name = $route->name ?? $mountain->name;
    $location = $mountain->province ?? 'Indonesia';
    $distance = $route->distance_km ?? 0;
    $elevationGain = $route->elevation_gain_m ?? 0;
    $hours = $distance > 0 ? round($distance / 3, 1) : 0; // Estimate 3km/h
    $rating = 4.5; // Placeholder - will be from reviews
    $level = match(true) {
        $elevationGain < 500 => 'Pemula',
        $elevationGain < 1000 => 'Menengah',
        default => 'Ahli'
    };
    $status = 'DIBUKA'; // Placeholder - will be from route status
    $updatedAt = $route->updated_at ?? now();
@endphp

<div class="rounded border border-neutral-line-2xl border border-neutral-line border border-neutral-line-neutral-line bg-neutral-card p-4 shadow-soft {{ $compact ? 'p-3' : '' }}">
    <div class="flex items-start justify-between gap-3">
        <div>
            <a href="{{ $url }}" class="text-base font-semibold text-neutral-text hover:text-brand">{{ $name }}</a>
            <div class="text-xs text-neutral-sub">{{ $location }}</div>
        </div>
        {{-- Level tanpa warna-warni --}}
        <span class="px-2 py-0.5 rounded border border-neutral-line-full border border-neutral-line border border-neutral-line-neutral-line text-[11px] text-neutral-sub">
            {{ $level }}
        </span>
    </div>

    <div class="mt-3 flex flex-wrap gap-4 text-xs text-neutral-text">
        <span>📏 {{ number_format($distance, 1) }} km</span>
        <span>⏱️ {{ $hours }} jam</span>
        <span>⛰️ {{ number_format($elevationGain) }} m</span>
        @if($showRating)
            <span>⭐ {{ $rating }}</span>
        @endif
    </div>

    @if($showStatus)
        <div class="mt-3 text-xs">
            {{-- Status: hanya 2 warna fungsional, selebihnya netral --}}
            @if($status === 'DIBUKA')
                <span class="px-2 py-0.5 rounded border border-neutral-line-full bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-ok/40 text-ok">DIBUKA</span>
            @elseif($status === 'DITUTUP')
                <span class="px-2 py-0.5 rounded border border-neutral-line-full bg-white border border-neutral-line border border-neutral-line-neutral-line border border-neutral-line border border-neutral-line-danger/40 text-danger">DITUTUP</span>
            @else
                <span class="px-2 py-0.5 rounded border border-neutral-line-full border border-neutral-line border border-neutral-line-neutral-line text-neutral-sub">{{ $status }}</span>
            @endif
            <span class="text-neutral-sub ml-2">· diperbarui {{ $updatedAt->diffForHumans() }}</span>
        </div>
    @endif
</div>