@props(['size' => 'sm'])

@php $dimension = ['xs' => 'h-3 w-3', 'sm' => 'h-4 w-4', 'md' => 'h-5 w-5', 'lg' => 'h-8 w-8'][$size] ?? 'h-4 w-4'; @endphp

<svg {{ $attributes->class("animate-spin $dimension") }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-20" />
    <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
</svg>
