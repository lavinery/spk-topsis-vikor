@props([
    'href' => null,
    'text' => 'Kembali',
    'icon' => '←',
    'class' => '',
    'onclick' => null
])

@if($href)
    <a href="{{ $href }}" 
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-neutral-line text-sm font-medium text-neutral-text hover:bg-neutral-line/20 hover:border-brand/30 transition-all duration-200 {{ $class }}">
        <span>{{ $icon }}</span>
        <span>{{ $text }}</span>
    </a>
@elseif($onclick)
    <button onclick="{{ $onclick }}" 
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-neutral-line text-sm font-medium text-neutral-text hover:bg-neutral-line/20 hover:border-brand/30 transition-all duration-200 {{ $class }}">
        <span>{{ $icon }}</span>
        <span>{{ $text }}</span>
    </button>
@else
    <button onclick="history.back()" 
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-neutral-line text-sm font-medium text-neutral-text hover:bg-neutral-line/20 hover:border-brand/30 transition-all duration-200 {{ $class }}">
        <span>{{ $icon }}</span>
        <span>{{ $text }}</span>
    </button>
@endif
