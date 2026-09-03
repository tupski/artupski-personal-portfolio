@props([
    'level' => 2,
    'eyebrow' => null,
    'id' => null,
])

@php
$tag = 'h' . min(max((int) $level, 1), 6);
$sizes = [
    1 => 'text-[var(--text-4xl)] md:text-[var(--text-5xl)] leading-[1.1] md:leading-[1.05] tracking-tight font-bold text-wrap-balance',
    2 => 'text-[var(--text-2xl)] leading-[1.3] tracking-tight font-semibold text-wrap-balance',
    3 => 'text-[var(--text-xl)] leading-[1.45] tracking-tight font-semibold',
    4 => 'text-[var(--text-lg)] leading-[1.6] font-semibold',
];
$classes = 'text-fg overflow-wrap-anywhere ' . ($sizes[(int) $level] ?? $sizes[2]);
@endphp

<div>
    @if($eyebrow)
        <span class="block font-mono text-[var(--text-2xs)] uppercase tracking-widest text-fg-subtle mb-2">{{ $eyebrow }}</span>
    @endif

    <{{ $tag }}
        @if($id) id="{{ $id }}" @endif
        @class($classes)
        {{ $attributes }}
    >
        {{ $slot }}

        @if($id)
            <a href="#{{ $id }}" class="text-fg-subtle opacity-0 hover:opacity-100 focus-visible:opacity-100 transition-opacity duration-120 ml-2" aria-label="Link to this section">#</a>
        @endif
    </{{ $tag }}>
</div>
