@props([
    'size' => 'default',
])

@php
$maxWidths = [
    'prose' => 'max-w-[72ch]',
    'narrow' => 'max-w-[40rem]',
    'default' => 'max-w-[72rem]',
    'wide' => 'max-w-[84rem]',
];
$classes = 'mx-auto w-full px-4 md:px-6 lg:px-8 ' . ($maxWidths[$size] ?? $maxWidths['default']);
@endphp

<div @class($classes) {{ $attributes }}>
    {{ $slot }}
</div>
