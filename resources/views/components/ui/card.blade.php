@props([
    'href' => null,
    'hoverable' => true,
])

@php
$base = 'group block border border-line rounded-[var(--radius-lg)] bg-bg min-w-0 overflow-wrap-anywhere';
$hover = $hoverable ? ' hover:bg-bg-muted hover:border-line-strong transition-colors duration-120' : '';
$focus = ' focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-accent';
$classes = $base . $hover . $focus;
@endphp

@if($href)
    <a href="{{ $href }}" @class($classes) {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <div @class($classes) {{ $attributes }}>
        {{ $slot }}
    </div>
@endif
