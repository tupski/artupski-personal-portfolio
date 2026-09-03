@props([
    'items' => collect(),
])

@if($items->count() > 0)
    <ul class="hidden md:flex items-center gap-6">
        @foreach($items as $item)
            @php
                $isActive = request()->is(ltrim($item->url, '/') . '*') || request()->is(ltrim($item->url, '/'));
            @endphp
            <li>
                <a
                    href="{{ $item->url }}"
                    @if($item->target_blank) target="_blank" rel="noopener" @endif
                    @if($isActive) aria-current="page" @endif
                    class="relative text-sm font-medium transition-colors duration-120
                        {{ $isActive
                            ? 'text-accent border-b-2 border-accent pb-0.5'
                            : 'text-fg hover:text-fg pb-0.5 border-b-2 border-transparent'
                        }}"
                >
                    {{ $item->label }}
                </a>
            </li>
        @endforeach
    </ul>
@endif
