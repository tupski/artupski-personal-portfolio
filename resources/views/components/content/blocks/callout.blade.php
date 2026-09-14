@props([
    'data' => [],
])

@php
$type = $data['type'] ?? 'info';
$content = $data['content'] ?? '';

// Editor callout types mapped onto the contract's alert tokens (§2.6).
// Flux's own theming variables (--callout-*) are the documented hook; they are set
// inline rather than through arbitrary-property utilities because Flux already emits
// the same properties as classes, and inline declarations are order-independent.
// Each variant pairs colour with an icon, so state never rests on colour alone (§9.7).
$variants = [
    'info' => [
        'icon' => 'information-circle',
        'vars' => '--callout-background:var(--bg-subtle);--callout-border:var(--line);--callout-text:var(--fg-muted);--callout-heading:var(--fg)',
    ],
    'note' => [
        'icon' => 'information-circle',
        'vars' => '--callout-background:var(--bg-subtle);--callout-border:var(--line);--callout-text:var(--fg-muted);--callout-heading:var(--fg)',
    ],
    'warning' => [
        'icon' => 'exclamation-triangle',
        'vars' => '--callout-background:var(--warn-soft);--callout-border:var(--warn);--callout-text:var(--warn);--callout-heading:var(--warn)',
    ],
    'tip' => [
        'icon' => 'check-circle',
        'vars' => '--callout-background:var(--success-soft);--callout-border:var(--success);--callout-text:var(--success);--callout-heading:var(--success)',
    ],
    'danger' => [
        'icon' => 'x-circle',
        'vars' => '--callout-background:var(--danger-soft);--callout-border:var(--danger);--callout-text:var(--danger);--callout-heading:var(--danger)',
    ],
];

$variant = $variants[$type] ?? $variants['info'];
@endphp

@if($content)
    <flux:callout
        icon="{{ $variant['icon'] }}"
        class="my-6"
        style="{{ $variant['vars'] }}"
    >
        {{ $content }}
    </flux:callout>
@endif
