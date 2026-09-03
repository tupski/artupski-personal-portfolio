@props([
    'data' => [],
])

@php
$src = $data['src'] ?? $data['url'] ?? '';
$alt = $data['alt'] ?? '';
$caption = $data['caption'] ?? null;
@endphp

@if($src)
    <x-media.figure
        :src="$src"
        :alt="$alt"
        :caption="$caption"
        :loading="'lazy'"
    />
@endif
