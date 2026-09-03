@props([
    'variant' => 'primary',
    'size' => 'md',
    'tag' => 'a',
    'disabled' => false,
    'loading' => false,
    'href' => null,
])

@php
$base = 'inline-flex items-center justify-center font-semibold transition-colors duration-120 rounded-[var(--radius-md)] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent disabled:cursor-not-allowed';

$variants = [
    'primary' => 'bg-fg text-bg hover:bg-fg-muted active:translate-y-px',
    'secondary' => 'bg-transparent text-fg border border-line-strong hover:bg-bg-muted active:translate-y-px',
    'ghost' => 'bg-transparent text-fg hover:bg-bg-muted active:translate-y-px',
    'danger' => 'bg-danger text-white hover:opacity-90 active:translate-y-px',
];

$sizes = [
    'sm' => 'h-8 px-3 text-sm gap-1.5',
    'md' => 'h-10 px-4 text-sm gap-2',
    'lg' => 'h-12 px-6 text-base gap-2',
];

$classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);

if ($disabled) {
    $classes .= ' bg-bg-muted text-fg-subtle pointer-events-none aria-disabled="true"';
}
@endphp

@if($tag === 'a' && $href)
    <a
        href="{{ $href }}"
        {!! $disabled ? 'aria-disabled="true"' : '' !!}
        @class($classes)
        {{ $attributes->merge(['class' => '']) }}
    >
        @if($loading)
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        @endif
        <span>{{ $slot }}</span>
    </a>
@else
    <button
        type="{{ $attributes->get('type', 'button') }}"
        @if($disabled) disabled aria-disabled="true" @endif
        @if($loading) aria-busy="true" @endif
        @class($classes)
        {{ $attributes->merge(['class' => '']) }}
    >
        @if($loading)
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        @endif
        <span>{{ $slot }}</span>
    </button>
@endif
