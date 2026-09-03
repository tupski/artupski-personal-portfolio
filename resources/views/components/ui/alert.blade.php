@props([
    'type' => 'info',
    'dismissible' => false,
])

@php
$types = [
    'success' => [
        'role' => 'status',
        'border' => 'border-l-success',
        'bg' => 'bg-success-soft',
        'text' => 'text-success',
        'icon' => '<circle cx="12" cy="12" r="10"></circle><path d="M9 12l2 2 4-4"></path>',
    ],
    'error' => [
        'role' => 'alert',
        'border' => 'border-l-danger',
        'bg' => 'bg-danger-soft',
        'text' => 'text-danger',
        'icon' => '<circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line>',
    ],
    'warning' => [
        'role' => 'status',
        'border' => 'border-l-warn',
        'bg' => 'bg-warn-soft',
        'text' => 'text-warn',
        'icon' => '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line>',
    ],
    'info' => [
        'role' => 'status',
        'border' => 'border-l-accent',
        'bg' => 'bg-bg-subtle',
        'text' => 'text-fg',
        'icon' => '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line>',
    ],
];

$config = $types[$type] ?? $types['info'];
@endphp

<div
    role="{{ $config['role'] }}"
    class="flex gap-3 p-4 rounded-[var(--radius-md)] border-l-3 {{ $config['border'] }} {{ $config['bg'] }}"
    {{ $attributes }}
>
    {{-- Icon --}}
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $config['text'] }} flex-shrink-0 mt-0.5" aria-hidden="true">
        {!! $config['icon'] !!}
    </svg>

    <div class="flex-1 min-w-0 text-sm {{ $config['text'] }}">
        {{ $slot }}
    </div>

    @if($dismissible)
        <button
            type="button"
            onclick="this.closest('[role]').remove()"
            aria-label="Dismiss"
            class="flex-shrink-0 {{ $config['text'] }} hover:opacity-70 transition-opacity duration-120"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    @endif
</div>
