@props(['items' => []])

{{-- @param array<int, array{label: string, route?: string, url?: string, active?: bool, count?: int|string}> $items --}}
<div {{ $attributes->class('scrollbar-none -mx-1 mb-4 flex gap-1 overflow-x-auto border-b border-hairline px-1') }}>
    @foreach ($items as $item)
        @php
            $url = $item['url'] ?? (isset($item['route']) ? route($item['route']) : '#');
            $active = $item['active'] ?? (isset($item['route']) && request()->routeIs($item['route']));
        @endphp

        <a href="{{ $url }}" @if ($active) aria-current="page" @endif @class([
            'flex min-h-[44px] items-center gap-1.5 whitespace-nowrap border-b-2 px-3 text-sm font-medium transition',
            'border-accent text-ink' => $active,
            'border-transparent text-ink-muted hover:border-hairline-strong hover:text-ink' => ! $active,
        ])>
            {{ $item['label'] }}

            @isset($item['count'])
                <span @class([
                    'rounded px-1.5 py-0.5 text-[11px] font-semibold',
                    'bg-accent-soft text-accent-ink' => $active,
                    'bg-sunken text-ink-muted' => ! $active,
                ])>{{ $item['count'] }}</span>
            @endisset
        </a>
    @endforeach
</div>
