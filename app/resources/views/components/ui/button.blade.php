@props([
    'variant' => 'secondary',
    'size' => 'md',
    'href' => null,
    'icon' => null,
    'iconRight' => null,
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center gap-1.5 rounded-lg font-medium whitespace-nowrap transition disabled:cursor-not-allowed disabled:opacity-50';

    $variants = [
        'primary' => 'bg-accent text-on-accent hover:opacity-90 elevate',
        'secondary' => 'border border-button-secondary-border bg-button-secondary text-button-secondary-ink hover:brightness-95',
        'ghost' => 'text-ink-soft hover:bg-sunken hover:text-ink',
        'danger' => 'border border-critical/30 bg-critical-soft text-critical hover:border-critical/60',
        'positive' => 'bg-positive text-white hover:opacity-90 elevate',
    ];

    // Every size keeps a 44px effective tap target on touch screens (MEP 9.3).
    $sizes = [
        'sm' => 'min-h-[36px] px-2.5 py-1.5 text-[13px] max-lg:min-h-[44px]',
        'md' => 'min-h-[38px] px-3.5 py-2 text-sm max-lg:min-h-[44px]',
        'lg' => 'min-h-[44px] px-5 py-2.5 text-sm',
        'icon' => 'h-9 w-9 max-lg:h-11 max-lg:w-11',
    ];

    $classes = trim($base.' '.($variants[$variant] ?? $variants['secondary']).' '.($sizes[$size] ?? $sizes['md']));
    $iconSize = $size === 'lg' ? 'h-[18px] w-[18px]' : 'h-4 w-4';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        @if ($icon)<x-dynamic-component :component="'heroicon-o-'.$icon" :class="$iconSize.' shrink-0'" />@endif
        {{ $slot }}
        @if ($iconRight)<x-dynamic-component :component="'heroicon-o-'.$iconRight" :class="$iconSize.' shrink-0'" />@endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        @if ($icon)<x-dynamic-component :component="'heroicon-o-'.$icon" :class="$iconSize.' shrink-0'" />@endif
        {{ $slot }}
        @if ($iconRight)<x-dynamic-component :component="'heroicon-o-'.$iconRight" :class="$iconSize.' shrink-0'" />@endif
    </button>
@endif
