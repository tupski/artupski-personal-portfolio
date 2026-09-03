@props([
    'variant' => 'default',
])

@php
$variants = [
    'default' => 'bg-bg-muted text-fg-muted',
    'draft' => 'bg-warn-soft text-warn',
    'scheduled' => 'bg-warn-soft text-warn',
    'published' => 'bg-success-soft text-success',
    'archived' => 'bg-bg-muted text-fg-subtle',
    'featured' => 'bg-accent-soft text-accent',
    'case-study' => 'bg-accent-soft text-accent',
];

$classes = 'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 font-mono text-xs uppercase tracking-wide ' . ($variants[$variant] ?? $variants['default']);
@endphp

<span @class($classes) {{ $attributes }}>
    {{ $slot }}
</span>
