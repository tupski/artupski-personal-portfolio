@props([
    'src' => null,
    'alt' => '',
    'width' => null,
    'height' => null,
    'caption' => null,
    'loading' => 'lazy',
    'aspectRatio' => null,
])

@php
$dimensions = '';
if ($width && $height) {
    $dimensions = "width=\"{$width}\" height=\"{$height}\"";
} elseif ($aspectRatio) {
    $dimensions = "style=\"aspect-ratio: {$aspectRatio}\"";
}
@endphp

<figure {{ $attributes->class(['my-6']) }}>
    @if($src)
        <img
            src="{{ $src }}"
            alt="{{ $alt }}"
            {!! $dimensions !!}
            loading="{{ $loading }}"
            decoding="async"
            @if($loading !== 'lazy') fetchpriority="high" @endif
            class="rounded-[var(--radius-lg)] max-w-full h-auto"
        >
    @else
        {{-- Error/missing state: caption-only fallback (§4) --}}
        @if($alt)
            <div class="w-full bg-bg-muted rounded-[var(--radius-lg)] flex items-center justify-center p-8" @if($aspectRatio) style="aspect-ratio: {{ $aspectRatio }}" @endif>
                <span class="text-sm text-fg-subtle text-center">{{ $alt }}</span>
            </div>
        @endif
    @endif

    @if($caption)
        <figcaption class="text-xs text-fg-subtle text-center mt-2">{{ $caption }}</figcaption>
    @endif
</figure>
