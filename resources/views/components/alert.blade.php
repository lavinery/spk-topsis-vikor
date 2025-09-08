{{-- resources/views/components/alert.blade.php --}}
@props(['type' => 'success'])
@php 
$map = [
    'success' => 'bg-white border border-neutral-line border border-neutral-line-neutral-line text-neutral-sub border border-neutral-line-green-200',
    'error' => 'bg-white border border-neutral-line border border-neutral-line-neutral-line text-neutral-sub border border-neutral-line-rose-200',
    'info' => 'bg-white border border-neutral-line border border-neutral-line-neutral-line text-neutral-sub border border-neutral-line-blue-200'
]; 
@endphp
<div class="border border-neutral-line rounded border border-neutral-line-xl px-4 py-3 {{ $map[$type] ?? $map['info'] }}">
    {{ $slot }}
</div>
