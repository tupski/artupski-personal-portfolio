{{-- SEO Preview Component for Filament Forms --}}
@php
    $record = $getRecord();
    $title = $record?->seo_title ?: $record?->title ?: 'Page Title';
    $description = $record?->seo_description ?: $record?->excerpt ?: $record?->short_description ?: 'Page description will appear here.';
    $url = request()->getSchemeAndHttpHost() . '/' . ($record?->slug ?: 'page-slug');
@endphp

<div class="space-y-3">
    {{-- Google-style preview --}}
    <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 bg-white dark:bg-gray-800">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Google Search Preview</p>
        <h4 class="text-blue-700 dark:text-blue-400 text-lg font-medium leading-tight hover:underline cursor-pointer">
            {{ Str::limit($title, 60) }}
        </h4>
        <p class="text-green-700 dark:text-green-400 text-sm mb-1">{{ Str::limit($url, 50) }}</p>
        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
            {{ Str::limit($description, 155) }}
        </p>
    </div>

    {{-- Open Graph preview --}}
    <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden bg-white dark:bg-gray-800">
        <div class="h-32 bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 flex items-center justify-center">
            @if($record?->getFirstMediaUrl('og'))
                <img src="{{ $record->getFirstMediaUrl('og') }}" alt="OG Image" class="w-full h-full object-cover">
            @else
                <span class="text-3xl">🖼️</span>
            @endif
        </div>
        <div class="p-3">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Open Graph Preview</p>
            <p class="font-semibold text-gray-900 dark:text-white text-sm leading-tight">
                {{ Str::limit($title, 70) }}
            </p>
            <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                {{ Str::limit($description, 100) }}
            </p>
        </div>
    </div>
</div>
