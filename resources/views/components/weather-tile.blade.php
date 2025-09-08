{{-- Weather Tile Component - Inspired by muncak.id --}}
@props([
    'forecast' => [],
    'location' => 'Gunung',
    'compact' => false
])

@php
    // Mock data - will be replaced with Open-Meteo API
    $forecast = $forecast ?: [
        [
            'label' => 'Hari ini',
            'temp' => 22,
            'summary' => 'Cerah',
            'icon' => 'sun',
            'precip' => 10
        ],
        [
            'label' => 'Besok',
            'temp' => 20,
            'summary' => 'Berawan',
            'icon' => 'cloud',
            'precip' => 30
        ],
        [
            'label' => 'Lusa',
            'temp' => 18,
            'summary' => 'Hujan ringan',
            'icon' => 'rain',
            'precip' => 60
        ]
    ];
@endphp

<div class="rounded-xl border border-neutral-line bg-white p-4 shadow-soft">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-neutral-text flex items-center gap-2">
            <svg class="w-4 h-4 text-neutral-sub0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
            </svg>
            Prakiraan Cuaca
        </h3>
        <span class="text-xs text-neutral-sub">{{ $location }}</span>
    </div>
    
    <div class="grid grid-cols-3 gap-3">
        @foreach($forecast as $day)
            <div class="text-center p-2 rounded-lg bg-white border border-neutral-line">
                <div class="text-xs text-neutral-sub mb-1 font-medium">{{ $day['label'] }}</div>
                <div class="text-lg font-bold text-neutral-text tabular-nums">{{ $day['temp'] }}°C</div>
                <div class="text-xs text-neutral-sub mb-1">{{ $day['summary'] }}</div>
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-3 h-3 text-neutral-sub0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                    </svg>
                    <span class="text-xs text-neutral-sub">{{ $day['precip'] }}%</span>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="mt-3 pt-3 border-t border-neutral-line">
        <div class="flex items-center justify-between text-xs text-neutral-sub">
            <span>Sumber: Open-Meteo</span>
            <span class="text-neutral-sub hover:text-neutral-sub cursor-pointer">Lihat detail</span>
        </div>
    </div>
</div>
