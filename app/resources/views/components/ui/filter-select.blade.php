@props(['label' => null, 'default' => ''])

{{-- A filter control. The label is read by the filter bar for its chips and
     shown above the select inside the filter panel. `default` is the value
     that means "no filter" when it is not the empty string.

     The select is hidden behind a combobox (type to search, five options at
     a time) that writes the choice back into it — resources/js/combobox.js.
     The filter bar still reads and stages the select itself. --}}
<label class="relative block shrink-0">
    @if ($label)<span class="mb-1 block text-xs font-medium text-ink-soft">{{ $label }}</span>@endif

    <span x-data="combobox" x-on:combobox:sync.window="sync()" class="relative block">
        <select @if ($label) data-filter-label="{{ $label }}" @endif data-filter-default="{{ $default }}"
            x-on:change="sync()" tabindex="-1" aria-hidden="true"
            {{ $attributes->class('pointer-events-none absolute inset-0 h-full w-full opacity-0') }}>
            {{ $slot }}
        </select>

        <x-ui.combobox class="border-hairline-strong" />
    </span>
</label>
