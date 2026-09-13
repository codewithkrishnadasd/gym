@props(['name' => '?', 'size' => 'md', 'tone' => 'neutral'])

@php
    $dimension = [
        'xs' => 'h-6 w-6 text-[10px]',
        'sm' => 'h-8 w-8 text-xs',
        'md' => 'h-9 w-9 text-sm',
        'lg' => 'h-12 w-12 text-base',
        'xl' => 'h-16 w-16 text-xl',
    ][$size] ?? 'h-9 w-9 text-sm';

    $palette = [
        'neutral' => 'bg-sunken text-ink-soft',
        'accent' => 'bg-accent-soft text-accent-ink',
        'info' => 'bg-info-soft text-info',
    ][$tone] ?? 'bg-sunken text-ink-soft';
@endphp

<span aria-hidden="true"
    {{ $attributes->class("grid shrink-0 place-items-center rounded-full font-semibold $dimension $palette") }}>
    {{ mb_strtoupper(mb_substr(trim($name) ?: '?', 0, 1)) }}
</span>
