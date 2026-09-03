@props([
    'data' => [],
])

@php
$type = $data['type'] ?? 'info';
$content = $data['content'] ?? '';
$calloutTypes = [
    'info' => 'info',
    'warning' => 'warning',
    'tip' => 'success',
    'note' => 'info',
];
@endphp

@if($content)
    <x-ui.alert :type="$calloutTypes[$type] ?? 'info'" class="my-6">
        {{ $content }}
    </x-ui.alert>
@endif
