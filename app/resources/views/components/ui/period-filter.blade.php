@props(['presets' => [], 'range' => 'month', 'from' => '', 'to' => '', 'today' => null, 'clubs' => null, 'clubLabel' => 'Clubs'])

{{-- Shared date-range + club scope control for the dashboard and reports
     (MEP.md 6.2). A bare row of chips — no frame of its own — ending in
     "Custom", which opens the range picker straight away; the chosen range
     then reads as plain text beside the chips. --}}
<div {{ $attributes->class('mb-4 flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center') }}>
    {{-- Scrolls sideways on a phone so every preset stays a single tap away;
         the fade at the right edge hints there is more. --}}
    <div class="relative min-w-0 flex-1 sm:flex-none">
        <div class="scrollbar-none -mx-4 flex gap-1.5 overflow-x-auto px-4 pr-8 snap-x sm:mx-0 sm:px-0 sm:pr-6" role="group" aria-label="Date range preset">
            @foreach ($presets as $key => $preset)
                <button type="button" wire:click="applyPreset('{{ $key }}')" @class([
                    'h-8 shrink-0 snap-start whitespace-nowrap rounded-full border px-3 text-xs font-medium transition',
                    'border-accent bg-accent text-on-accent' => $range === $key,
                    'border-hairline bg-surface text-ink-soft hover:border-hairline-strong hover:text-ink' => $range !== $key,
                ])>{{ $preset['label'] }}</button>
            @endforeach

            {{-- Switches to a custom range and opens the picker in one go: the
                 picker is rendered by the response, then told to open. --}}
            <button type="button"
                x-on:click="$wire.startCustom().then(() => setTimeout(() => $dispatch('open-range-picker'), 30))" @class([
                'inline-flex h-8 shrink-0 snap-start items-center gap-1 whitespace-nowrap rounded-full border px-3 text-xs font-medium transition',
                'border-accent bg-accent text-on-accent' => $range === 'custom',
                'border-hairline bg-surface text-ink-soft hover:border-hairline-strong hover:text-ink' => $range !== 'custom',
            ])>
                <x-heroicon-o-calendar-days class="h-3.5 w-3.5" />
                Custom
            </button>
        </div>
        <div class="pointer-events-none absolute inset-y-0 right-0 w-8 bg-gradient-to-l from-[var(--c-app)] to-transparent sm:hidden"></div>
    </div>

    @if ($range === 'custom')
        <x-ui.date-range :from="$from" :to="$to" :today="$today" wire:key="range-picker" />
    @endif

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
