@props([
    'data' => [],
])

@php
$heading = $data['heading'] ?? '';
$text = $data['text'] ?? null;
$buttonText = $data['button_text'] ?? '';
$buttonUrl = $data['button_url'] ?? '#';
@endphp

@if($heading)
    <div class="my-8 p-6 border border-line rounded-[var(--radius-lg)] bg-bg-subtle text-center">
        <h3 class="text-[var(--text-xl)] font-semibold text-fg">{{ $heading }}</h3>
        @if($text)
            <p class="mt-2 text-sm text-fg-muted">{{ $text }}</p>
        @endif
        @if($buttonText)
            <div class="mt-4">
                <x-ui.button href="{{ $buttonUrl }}" variant="primary">
                    {{ $buttonText }}
                </x-ui.button>
            </div>
        @endif
    </div>
@endif
