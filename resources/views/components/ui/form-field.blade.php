@props([
    'name' => null,
    'label' => null,
    'type' => 'text',
    'hint' => null,
    'error' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'value' => null,
    'placeholder' => null,
    'rows' => null,
    'id' => null,
])

@php
$inputId = $id ?? $name;
$errorId = $inputId . '-error';
$hintId = $inputId . '-hint';
$hasError = !empty($error);

$labelClasses = 'block text-sm font-medium text-fg mb-1.5';
$inputBase = 'block w-full rounded-[var(--radius-md)] border text-base font-sans transition-colors duration-120 bg-bg placeholder:text-fg-subtle';
$inputDefault = 'border-line-control focus:border-accent focus:ring-2 focus:ring-accent/20';
$inputError = 'border-danger bg-danger-soft focus:border-danger focus:ring-2 focus:ring-danger/20';
$inputDisabled = 'bg-bg-muted text-fg-subtle cursor-not-allowed';
$inputClasses = $inputBase . ' ' . ($hasError ? $inputError : $inputDefault) . ' ' . ($disabled ? $inputDisabled : '');
@endphp

<div class="space-y-1">
    @if($label)
        <label for="{{ $inputId }}" class="{{ $labelClasses }}">
            {{ $label }}
            @if($required)
                <span class="text-fg-subtle font-normal">(required)</span>
            @endif
        </label>
    @endif

    @if($hint && !$hasError)
        <p id="{{ $hintId }}" class="text-xs text-fg-subtle">{{ $hint }}</p>
    @endif

    @if($type === 'textarea')
        <textarea
            id="{{ $inputId }}"
            name="{{ $name }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            @if($hasError) aria-invalid="true" aria-describedby="{{ $errorId }}" @endif
            @if($hint && !$hasError) aria-describedby="{{ $hintId }}" @endif
            @if($rows) rows="{{ $rows }}" @endif
            style="min-height: 8rem; resize: vertical;"
            class="{{ $inputClasses }}"
        >{{ $value ?? '' }}</textarea>
    @elseif($type === 'select')
        <select
            id="{{ $inputId }}"
            name="{{ $name }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($hasError) aria-invalid="true" aria-describedby="{{ $errorId }}" @endif
            class="{{ $inputClasses }}"
        >
            {{ $slot }}
        </select>
    @else
        <input
            type="{{ $type }}"
            id="{{ $inputId }}"
            name="{{ $name }}"
            @if($value) value="{{ $value }}" @endif
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            @if($hasError) aria-invalid="true" aria-describedby="{{ $errorId }}" @endif
            @if($hint && !$hasError) aria-describedby="{{ $hintId }}" @endif
            class="{{ $inputClasses }}"
        >
    @endif

    @if($hasError)
        <p id="{{ $errorId }}" class="text-sm text-danger" role="alert">{{ $error }}</p>
    @endif
</div>
