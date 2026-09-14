@props(['presets' => [], 'range' => 'month', 'clubs' => null, 'clubLabel' => 'Clubs'])

{{-- Shared date-range + club scope control for the dashboard and reports
     (MEP.md 6.2). Presets cover the common cases; the date inputs stay
     available for anything else. --}}
<div {{ $attributes->class('mb-4 flex flex-col gap-2 rounded-xl border border-hairline bg-surface p-2 elevate sm:flex-row sm:flex-wrap sm:items-center') }}>
    {{-- Wraps rather than scrolls. As a scroller with `scrollbar-none` the
         later presets were off-screen on a phone with nothing to suggest they
         existed — a handful of fixed options should all be visible. --}}
    <div class="flex flex-wrap gap-1" role="group" aria-label="Date range preset">
        @foreach ($presets as $key => $preset)
            <button type="button" wire:click="applyPreset('{{ $key }}')" @class([
                'min-h-[36px] whitespace-nowrap rounded-lg px-2.5 text-[13px] font-medium transition max-lg:min-h-[44px]',
                'bg-accent-soft text-accent-ink' => $range === $key,
                'text-ink-soft hover:bg-sunken hover:text-ink' => $range !== $key,
            ])>{{ $preset['label'] }}</button>
        @endforeach
    </div>

    <div class="hidden h-5 w-px bg-hairline sm:block"></div>

    <div class="flex min-w-0 flex-1 items-center gap-1.5 sm:flex-none">
        <x-ui.date-input bare size="sm" wire:model.live="from" aria-label="From date" class="min-w-0 flex-1 sm:w-36 sm:flex-none" />
        <span class="shrink-0 text-xs text-ink-muted">to</span>
        <x-ui.date-input bare size="sm" wire:model.live="to" aria-label="To date" class="min-w-0 flex-1 sm:w-36 sm:flex-none" />
    </div>

    @if ($clubs && $clubs->count() > 1)
        <div class="sm:ml-auto">
            <x-ui.filter-select wire:model.live="club" :label="$clubLabel">
                <option value="">All {{ strtolower($clubLabel) }}</option>
                @foreach ($clubs as $club)
                    <option value="{{ $club->id }}">{{ $club->name }}</option>
                @endforeach
            </x-ui.filter-select>
        </div>
    @endif

    <div wire:loading.delay class="text-ink-muted sm:ml-2"><x-ui.spinner size="xs" /></div>
</div>
