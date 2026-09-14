@props([
    'data' => [],
])

@php
$heading = $data['heading'] ?? '';
$text = $data['text'] ?? null;
$buttonText = $data['button_text'] ?? '';
$buttonUrl = $data['button_url'] ?? null;
@endphp

@if($heading)
    <div class="my-8 p-6 border border-line rounded-[var(--radius-lg)] bg-bg-subtle text-center">
        <h3 class="text-[var(--text-xl)] font-semibold text-fg">{{ $heading }}</h3>
        @if($text)
            <p class="mt-2 text-sm text-fg-muted">{{ $text }}</p>
        @endif

        {{-- A CTA button with no destination would be a dead control (§R-26), so it is
             only rendered when the editor supplied a URL. --}}
        @if($buttonText && $buttonUrl)
            <div class="mt-4">
                <flux:button href="{{ $buttonUrl }}" variant="primary">
                    {{ $buttonText }}
                </flux:button>
            </div>
        @endif
    </div>
@endif
