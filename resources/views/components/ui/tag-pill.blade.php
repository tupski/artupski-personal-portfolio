@props([
    'href' => null,
    'active' => false,
    'disabled' => false,
])

@php
$base = 'inline-flex items-center font-mono text-xs rounded-full transition-colors duration-120 overflow-wrap-anywhere';
$interactive = !$disabled && $href;

$states = $active
    ? 'bg-accent-soft text-accent border border-accent'
    : ($interactive
        ? 'bg-transparent text-fg-subtle border border-line hover:bg-accent-soft hover:text-accent-strong hover:border-accent-soft'
        : 'bg-transparent text-fg-subtle border border-line cursor-default');

$padding = 'px-3 py-1';
$classes = $base . ' ' . $states . ' ' . $padding;
@endphp

@if($href && !$disabled)
    <a
        href="{{ $href }}"
        @if($active) aria-current="page" @endif
        @class($classes)
        {{ $attributes }}
    >{{ $slot }}</a>
@else
    <span @class($classes) {{ $attributes }}>{{ $slot }}</span>
@endif
