@props(['presets' => [], 'range' => 'month', 'from' => '', 'to' => '', 'today' => null, 'clubs' => null, 'clubLabel' => 'Clubs'])

{{-- Shared date-range + club scope control for the dashboard and reports
     (MEP.md 6.2). Presets are a scrolling row of chips ending in "Custom",
     which opens the range picker; the picker only shows for a custom range. --}}
<div {{ $attributes->class('mb-4 flex flex-col gap-2 rounded-xl border border-hairline bg-surface p-2 elevate sm:flex-row sm:flex-wrap sm:items-center') }}>
    {{-- One row that scrolls sideways on a phone, so every preset stays a
         single tap away; the fade at the right edge hints there is more. --}}
    <div class="relative min-w-0 flex-1">
        <div class="scrollbar-none flex gap-1.5 overflow-x-auto pr-6 snap-x" role="group" aria-label="Date range preset">
            @foreach ($presets as $key => $preset)
                <button type="button" wire:click="applyPreset('{{ $key }}')" @class([
                    'min-h-[36px] shrink-0 snap-start whitespace-nowrap rounded-full px-3.5 text-[13px] font-medium transition max-lg:min-h-[40px]',
                    'bg-accent text-on-accent shadow-sm' => $range === $key,
                    'bg-sunken text-ink-soft hover:bg-raised hover:text-ink' => $range !== $key,
                ])>{{ $preset['label'] }}</button>
            @endforeach

            <button type="button" wire:click="startCustom" @class([
                'inline-flex min-h-[36px] shrink-0 snap-start items-center gap-1.5 whitespace-nowrap rounded-full px-3.5 text-[13px] font-medium transition max-lg:min-h-[40px]',
                'bg-accent text-on-accent shadow-sm' => $range === 'custom',
                'bg-sunken text-ink-soft hover:bg-raised hover:text-ink' => $range !== 'custom',
            ])>
                <x-heroicon-o-calendar-days class="h-4 w-4" />
                Custom
            </button>
        </div>
        <div class="pointer-events-none absolute inset-y-0 right-0 w-8 bg-gradient-to-l from-[var(--c-surface)] to-transparent"></div>
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
