{{-- A record's reference ("MEM-42"), styled the same everywhere so the eye
     learns to find it. --}}
@props(['value'])

<span {{ $attributes->class('numeric inline-block whitespace-nowrap rounded bg-sunken px-1.5 py-0.5 font-mono text-[11px] font-medium leading-4 text-ink-soft') }}>{{ $value }}</span>
