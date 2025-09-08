{{-- Level Badge Component - Inspired by muncak.id --}}
@props([
    'level' => 'beginner',
    'size' => 'sm',
    'showIcon' => true
])

@php
    $levelConfig = [
        'beginner' => [
            'label' => 'Pemula',
            'class' => 'bg-white border border-neutral-line border border-neutral-line-neutral-line text-neutral-sub border border-neutral-line-emerald-200',
            'icon' => 'leaf'
        ],
        'intermediate' => [
            'label' => 'Menengah',
            'class' => 'bg-white border border-neutral-line border border-neutral-line-neutral-line text-neutral-sub border border-neutral-line-amber-200',
            'icon' => 'mountain'
        ],
        'advanced' => [
            'label' => 'Ahli',
            'class' => 'bg-white border border-neutral-line border border-neutral-line-neutral-line text-neutral-sub border border-neutral-line-rose-200',
            'icon' => 'fire'
        ]
    ];
    
    $config = $levelConfig[$level] ?? $levelConfig['beginner'];
    $sizeClass = $size === 'lg' ? 'px-3 py-1.5 text-sm' : 'px-2 py-1 text-xs';
@endphp

<span class="inline-flex items-center gap-1 {{ $sizeClass }} rounded border border-neutral-line-full font-medium border border-neutral-line {{ $config['class'] }}">
    @if($showIcon)
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            @if($config['icon'] === 'leaf')
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            @elseif($config['icon'] === 'mountain')
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            @else
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
            @endif
        </svg>
    @endif
    {{ $config['label'] }}
</span>
