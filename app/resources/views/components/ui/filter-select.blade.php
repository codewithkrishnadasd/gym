@props(['label' => null, 'default' => ''])

{{-- A filter control. The label is read by the filter bar for its chips and
     shown above the select inside the filter panel. `default` is the value
     that means "no filter" when it is not the empty string. --}}
<label class="relative block shrink-0">
    @if ($label)<span class="mb-1 block text-xs font-medium text-ink-soft">{{ $label }}</span>@endif

    <span class="relative block">
        <select @if ($label) data-filter-label="{{ $label }}" @endif data-filter-default="{{ $default }}"
            {{ $attributes->class('min-h-[40px] w-full appearance-none rounded-lg border border-hairline-strong bg-surface py-2 pl-3 pr-8 text-sm text-ink transition focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25 max-lg:min-h-[44px]') }}>
            {{ $slot }}
        </select>

        <x-heroicon-o-chevron-down class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-ink-muted" />
    </span>
</label>
