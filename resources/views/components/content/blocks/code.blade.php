@props([
    'data' => [],
])

@php
$code = $data['code'] ?? $data['content'] ?? '';
$language = $data['language'] ?? null;
$title = $data['title'] ?? null;
@endphp

@if($code)
    <x-content.code-block
        :code="$code"
        :language="$language"
        :title="$title"
    />
@endif
