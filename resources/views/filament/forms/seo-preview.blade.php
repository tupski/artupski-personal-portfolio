{{-- SEO preview for Filament forms.

     Rewritten from a version that used an emoji as placeholder art, a
     `bg-gradient-to-br from-amber-100 to-orange-100` fill and hardcoded
     `text-blue-700` / `text-green-700` / `gray-*`. All three breached the project
     contract: §1 rejects gradient fills and decorative art on sight, §2.6 forbids
     hardcoded neutrals, and antislop R-04 rejects an emoji standing in for an icon.

     The two panels now use Filament's own tokens (`fi-*` custom properties), so they
     follow the panel's light/dark mode and the ember palette automatically. --}}
@php
    $record = $getRecord();
    $title = $record?->seo_title ?: $record?->title ?: 'Untitled';
    $description = $record?->seo_description ?: $record?->excerpt ?: $record?->short_description ?: '';
    $slug = $record?->slug ?: 'page-slug';
    $url = request()->getSchemeAndHttpHost() . '/' . $slug;
    $ogImage = $record?->getFirstMediaUrl('og');
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    {{-- Google result preview ------------------------------------------------ --}}
    <div class="rounded-lg border border-gray-200 p-4 dark:border-white/10">
        <p class="mb-2 font-mono text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">
            Search result
        </p>

        <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $url }}</p>

        {{-- Google renders the title in a link colour; this is a static preview, so
             it is prose, not an anchor. Real link colour would promise a destination. --}}
        <p class="mt-1 text-base font-medium leading-snug text-primary-600 dark:text-primary-400">
            {{ Str::limit($title, 60) }}
        </p>

        @if ($description)
            <p class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-gray-300">
                {{ Str::limit($description, 155) }}
            </p>
        @else
            {{-- Empty state names the cause and the fix (R-27) --}}
            <p class="mt-1 text-sm italic leading-relaxed text-gray-500 dark:text-gray-400">
                No description yet. Add one above to control the snippet.
            </p>
        @endif

        <p class="mt-2 font-mono text-xs text-gray-500 dark:text-gray-400">
            {{ Str::length((string) $title) }}/60 title ·
            {{ Str::length((string) $description) }}/155 description
        </p>
    </div>

    {{-- Social card preview -------------------------------------------------- --}}
    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-white/10">
        <p class="border-b border-gray-200 px-4 py-2 font-mono text-xs uppercase tracking-wider text-gray-500 dark:border-white/10 dark:text-gray-400">
            Social card
        </p>

        <div class="flex aspect-[1.91/1] items-center justify-center bg-gray-50 dark:bg-white/5">
            @if ($ogImage)
                <img src="{{ $ogImage }}" alt="" class="h-full w-full object-cover">
            @else
                {{-- No image is a real state, not a gap to fill with decoration.
                     It says what will actually be shared instead. --}}
                <span class="px-4 text-center text-xs text-gray-500 dark:text-gray-400">
                    No card image set — the title and description below will be shared as text.
                </span>
            @endif
        </div>

        <div class="border-t border-gray-200 p-4 dark:border-white/10">
            <p class="font-mono text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">
                {{ parse_url($url, PHP_URL_HOST) }}
            </p>
            <p class="mt-1 truncate text-sm font-medium text-gray-900 dark:text-white">
                {{ Str::limit($title, 70) }}
            </p>
            <p class="mt-0.5 line-clamp-2 text-sm text-gray-600 dark:text-gray-300">
                {{ Str::limit($description, 120) ?: 'No description set.' }}
            </p>
        </div>
    </div>
</div>
