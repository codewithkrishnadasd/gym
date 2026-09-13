@props(['label' => null])

<label class="relative shrink-0">
    @if ($label)<span class="sr-only">{{ $label }}</span>@endif

    <select {{ $attributes->class('min-h-[40px] w-full appearance-none rounded-lg border border-hairline-strong bg-surface py-2 pl-3 pr-8 text-sm text-ink-soft transition focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25 max-lg:min-h-[44px] sm:w-auto') }}>
        {{ $slot }}
    </select>

    <x-heroicon-o-chevron-down class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-ink-muted" />
</label>
