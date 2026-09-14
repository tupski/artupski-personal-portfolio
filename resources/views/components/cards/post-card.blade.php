@props([
    'post' => null,
    'href' => null,
])

@php
$postUrl = $href ?? ($post ? '/blog/' . $post->slug : '#');
$media = $post?->getFirstMedia('featured');
@endphp

{{-- One row of the blog index list (§3.4). Surface is flux:card with token overrides;
     hover shifts the row to --bg-subtle and the title to --accent (§4). --}}
<flux:card
    class="group relative flex gap-4 items-start p-4 bg-bg border-line
           hover:bg-bg-subtle hover:border-line-strong
           focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-accent
           transition-colors duration-120"
>
    {{-- Thumbnail — hidden below sm, and absent entirely when there is no image (§4, §6.1) --}}
    @if($media)
        <img
            src="{{ $media->getUrl('thumb') }}"
            alt="{{ $media->getCustomProperty('alt', $post?->title ?? '') }}"
            width="96"
            height="96"
            loading="lazy"
            decoding="async"
            class="hidden sm:block w-24 h-24 rounded-[var(--radius-md)] object-cover flex-shrink-0"
        >
    @endif

    <div class="flex-1 min-w-0">
        <h3 class="text-[var(--text-lg)] font-semibold text-fg leading-snug line-clamp-2 wrap-anywhere group-hover:text-accent transition-colors duration-120">
            <a href="{{ $postUrl }}" class="after:absolute after:inset-0">{{ $post?->title ?? $slot }}</a>
        </h3>

        @if($post?->excerpt)
            <p class="mt-1 text-sm text-fg-muted line-clamp-2 wrap-anywhere">
                {{ $post->excerpt }}
            </p>
        @endif

        {{-- Mono meta row (§4) — wraps rather than truncating --}}
        <div class="mt-2 flex flex-wrap gap-x-3 gap-y-1 font-mono text-xs text-fg-subtle">
            @if($post?->published_at)
                <time datetime="{{ $post->published_at->format('Y-m-d') }}">
                    {{ $post->published_at->format('M j, Y') }}
                </time>
            @endif
            @if($post?->reading_time)
                <span>{{ $post->reading_time }} min read</span>
            @endif
            @if($post?->category)
                <span>{{ $post->category->name }}</span>
            @endif
        </div>
    </div>
</flux:card>
