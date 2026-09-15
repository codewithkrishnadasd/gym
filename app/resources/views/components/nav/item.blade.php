@props(['item', 'mobile' => false])

@php $active = request()->routeIs($item['active']); @endphp

{{-- wire:navigate keeps the shell and swaps only the page; `.hover` starts
     fetching the destination as soon as the pointer rests on the link, so the
     click itself feels immediate. While a navigation is in flight the icon
     becomes a spinner, on this item only. --}}
<a href="{{ route($item['route']) }}" wire:navigate.hover
    x-data="{ busy: false }" x-on:click="busy = true"
    x-on:livewire:navigated.window="busy = false" x-on:livewire:navigate-failed.window="busy = false"
    @if ($active) aria-current="page" @endif
    @class([
        'group flex items-center gap-2.5 rounded-lg px-3 text-sm font-medium transition',
        'min-h-[44px] py-2.5' => $mobile,
        'py-2' => ! $mobile,
        'bg-accent-soft text-accent-ink' => $active,
        'text-ink-soft hover:bg-sunken hover:text-ink' => ! $active,
    ])>
    <span class="relative h-[18px] w-[18px] shrink-0">
        <x-dynamic-component :component="'heroicon-o-'.$item['icon']" x-show="! busy" @class([
            'h-[18px] w-[18px]',
            'text-accent' => $active,
            'text-ink-muted group-hover:text-ink-soft' => ! $active,
        ]) />
        <span x-show="busy" x-cloak class="absolute inset-0 grid place-items-center text-accent"><x-ui.spinner size="xs" /></span>
    </span>
    <span class="truncate">{{ $item['label'] }}</span>
</a>
