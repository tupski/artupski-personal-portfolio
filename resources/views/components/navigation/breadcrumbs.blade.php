@props([
    'items' => [],
])

@if(count($items) > 0)
    <nav aria-label="Breadcrumb" class="overflow-x-auto no-scrollbar">
        <ol class="flex items-center gap-1.5 font-mono text-xs text-fg-subtle whitespace-nowrap">
            @foreach($items as $index => $item)
                @if($index > 0)
                    <li aria-hidden="true" class="text-fg-subtle">/</li>
                @endif
                <li class="flex items-center gap-1.5">
                    @if($loop->last)
                        <span aria-current="page" class="text-fg truncate max-w-[200px]" title="{{ $item['label'] }}">
                            {{ $item['label'] }}
                        </span>
                    @else
                        <a
                            href="{{ $item['url'] }}"
                            class="text-fg-subtle hover:text-accent underline underline-offset-2 transition-colors duration-120"
                        >{{ $item['label'] }}</a>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
