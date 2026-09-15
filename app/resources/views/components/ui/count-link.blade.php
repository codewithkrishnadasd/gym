{{-- A count that opens the list it was counted from, already filtered. Every
     figure that summarises other records uses this, so a number is never a
     dead end. Renders as plain text when there is nothing to open (zero). --}}
@props(['value', 'href', 'title' => 'Show them'])

@php $count = (int) $value; @endphp

@if ($count > 0 && $href)
    <a href="{{ $href }}" wire:navigate title="{{ $title }}"
        {{ $attributes->class('numeric inline-flex items-center gap-1 rounded-md px-1.5 py-0.5 font-medium text-accent underline-offset-2 transition hover:bg-accent-soft hover:underline') }}>
        {{ number_format($count) }}
        <x-heroicon-o-arrow-up-right class="h-3 w-3 opacity-60" />
    </a>
@else
    <span {{ $attributes->class('numeric text-ink-muted') }}>{{ number_format($count) }}</span>
@endif
