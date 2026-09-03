@props([
    'content' => '',
    'blocks' => null,
])

{{-- The ONLY place raw {!! !!} is permitted (§71, BUILD-PLAN §7) --}}
{{-- Content is already sanitized on save via SanitizesHtml trait --}}
<x-ui.prose class="prose-custom">
    {!! $content !!}
</x-ui.prose>

{{-- Custom blocks from content_blocks JSON — each rendered via Blade with escaped {{ }} --}}
@if($blocks && is_array($blocks))
    @foreach($blocks as $block)
        @php
            $blockType = $block['type'] ?? $block['block'] ?? null;
            $blockData = $block['data'] ?? $block['attrs'] ?? $block;
        @endphp

        @if($blockType === 'image')
            <x-content.blocks.image :data="$blockData" />
        @elseif($blockType === 'callout')
            <x-content.blocks.callout :data="$blockData" />
        @elseif($blockType === 'cta')
            <x-content.blocks.cta :data="$blockData" />
        @elseif($blockType === 'code')
            <x-content.blocks.code :data="$blockData" />
        @elseif($blockType === 'project-highlight')
            <x-content.blocks.project-highlight :data="$blockData" />
        @endif
    @endforeach
@endif
