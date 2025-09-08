{{-- Status Pill Component - Inspired by muncak.id --}}
@props([
    'status' => 'open',
    'size' => 'sm',
    'showIcon' => true
])

@php
    $statusConfig = [
        'open' => [
            'label' => 'DIBUKA',
            'class' => 'bg-white border border-neutral-line border border-neutral-line-neutral-line text-neutral-sub border border-neutral-line-emerald-200',
            'icon' => 'check-circle'
        ],
        'limited' => [
            'label' => 'TERBATAS',
            'class' => 'bg-white border border-neutral-line border border-neutral-line-neutral-line text-neutral-sub border border-neutral-line-amber-200',
            'icon' => 'exclamation-triangle'
        ],
        'closed' => [
            'label' => 'DITUTUP',
            'class' => 'bg-white border border-neutral-line border border-neutral-line-neutral-line text-neutral-sub border border-neutral-line-rose-200',
            'icon' => 'x-circle'
        ]
    ];
    
    $config = $statusConfig[$status] ?? $statusConfig['open'];
    $sizeClass = $size === 'lg' ? 'px-3 py-1.5 text-sm' : 'px-2 py-1 text-xs';
@endphp

<span class="inline-flex items-center gap-1 {{ $sizeClass }} rounded border border-neutral-line-full font-medium border border-neutral-line {{ $config['class'] }}">
    @if($showIcon)
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            @if($config['icon'] === 'check-circle')
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            @elseif($config['icon'] === 'exclamation-triangle')
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
            @else
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            @endif
        </svg>
    @endif
    {{ $config['label'] }}
</span>
