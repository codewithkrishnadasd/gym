{{-- A task or item status in its own colours. Text colour is derived from the
     background so every status stays readable whatever colour was picked. --}}
@props(['status', 'size' => 'sm'])

@php
    $sizes = ['xs' => 'px-1.5 py-0.5 text-[11px]', 'sm' => 'px-2 py-0.5 text-xs', 'md' => 'px-2.5 py-1 text-sm'];
@endphp

@if ($status)
    <span {{ $attributes->class(['inline-flex items-center gap-1 rounded-full font-medium leading-4 whitespace-nowrap', $sizes[$size] ?? $sizes['sm']]) }}
        style="{{ $status->style() }}">{{ $status->name }}</span>
@else
    <span {{ $attributes->class(['inline-flex items-center rounded-full bg-sunken font-medium leading-4 text-ink-muted', $sizes[$size] ?? $sizes['sm']]) }}>No status</span>
@endif
