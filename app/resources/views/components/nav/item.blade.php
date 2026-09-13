@props(['item', 'mobile' => false])

@php $active = request()->routeIs($item['active']); @endphp

<a href="{{ route($item['route']) }}"
    @if ($active) aria-current="page" @endif
    @class([
        'group flex items-center gap-2.5 rounded-lg px-3 text-sm font-medium transition',
        'min-h-[44px] py-2.5' => $mobile,
        'py-2' => ! $mobile,
        'bg-accent-soft text-accent-ink' => $active,
        'text-ink-soft hover:bg-sunken hover:text-ink' => ! $active,
    ])>
    <x-dynamic-component :component="'heroicon-o-'.$item['icon']" @class([
        'h-[18px] w-[18px] shrink-0',
        'text-accent' => $active,
        'text-ink-muted group-hover:text-ink-soft' => ! $active,
    ]) />
    <span class="truncate">{{ $item['label'] }}</span>
</a>
