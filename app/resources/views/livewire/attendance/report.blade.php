@php
    $cellTone = fn (?\App\Models\Attendance $mark): string => match ($mark?->action->value) {
        'present' => 'bg-positive text-white',
        'late' => 'bg-caution text-white',
        'excused' => 'bg-info text-white',
        'absent' => 'bg-critical text-white',
        default => 'bg-sunken text-ink-muted',
    };
    $legend = ['present' => 'bg-positive', 'late' => 'bg-caution', 'excused' => 'bg-info', 'absent' => 'bg-critical'];
    $lead = ($monthStart->dayOfWeekIso - 1);
@endphp

<div class="space-y-4">
    {{-- The figures that matter, two up on a phone. --}}
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <x-ui.stat label="Visits this month" :value="number_format($monthVisits)" icon="clipboard-document-check"
            :hint="$monthMarked > 0 ? 'of '.$monthMarked.' marked days' : 'nothing marked yet'" tone="accent" />
        <x-ui.stat label="Attendance rate" :value="$windowRate === null ? '—' : $windowRate.'%'" icon="chart-bar"
            :hint="$windowVisits.' visits in 12 weeks'" :tone="$windowRate === null ? 'neutral' : ($windowRate >= 60 ? 'positive' : 'caution')" />
        <x-ui.stat label="Current streak" :value="$streak.' '.($streak === 1 ? 'day' : 'days')" icon="fire"
            :tone="$streak >= 3 ? 'positive' : 'neutral'" :hint="$streak > 0 ? 'present in a row' : 'no run going'" />
        <x-ui.stat label="Last visit" :value="$lastVisit ? $lastVisit->attendance_date->format('d M') : '—'" icon="calendar-days"
            :hint="$lastVisit ? $lastVisit->attendance_date->diffForHumans($today, ['parts' => 1]) : 'never marked present'" tone="neutral" />
    </div>

    <div class="grid gap-4 lg:grid-cols-3">
        {{-- The month, day by day. --}}
        <x-ui.card class="lg:col-span-2" :padded="false">
            <div class="flex items-center justify-between gap-2 border-b border-hairline px-3 py-2.5 sm:px-4">
                <x-ui.button size="icon" variant="ghost" wire:click="shiftMonth(-1)" aria-label="Previous month">
                    <x-heroicon-o-chevron-left class="h-4 w-4" />
                </x-ui.button>
                <div class="text-center">
                    <p class="font-[family-name:var(--font-display)] text-sm font-semibold text-ink">{{ $monthStart->format('F Y') }}</p>
                    @if ($canGoForward)
                        <button type="button" wire:click="thisMonth" class="text-xs font-medium text-accent hover:underline">Back to this month</button>
                    @endif
                </div>
                <x-ui.button size="icon" variant="ghost" wire:click="shiftMonth(1)" aria-label="Next month" :disabled="! $canGoForward">
                    <x-heroicon-o-chevron-right class="h-4 w-4" />
                </x-ui.button>
            </div>

            <div class="relative p-3 sm:p-4">
                <x-ui.list-loader target="shiftMonth,thisMonth" />

                <div class="grid grid-cols-7 gap-1 text-center text-[11px] font-medium text-ink-muted">
                    @foreach (['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'] as $day)
                        <span class="py-1">{{ $day }}</span>
                    @endforeach
                </div>
                <div class="grid grid-cols-7 gap-1">
                    @for ($i = 0; $i < $lead; $i++)
                        <span></span>
                    @endfor
                    @foreach (range(1, $monthStart->daysInMonth) as $day)
                        @php
                            $date = $monthStart->copy()->day($day);
                            $mark = $marks->get($date->toDateString());
                            $future = $date->gt($today);
                        @endphp
                        <div class="numeric flex aspect-square flex-col items-center justify-center rounded-lg text-sm {{ $future ? 'text-ink-muted opacity-40' : $cellTone($mark) }} {{ $date->isSameDay($today) ? 'ring-2 ring-accent ring-offset-1 ring-offset-[var(--c-surface)]' : '' }}"
                            title="{{ $date->format('D d M') }}{{ $mark ? ' — '.$mark->action->label().($mark->club ? ' at '.$mark->club->name : '') : ($future ? '' : ' — not marked') }}">
                            <span class="font-medium leading-none">{{ $day }}</span>
                            @if ($mark && $mark->check_in_at)
                                <span class="mt-0.5 hidden text-[10px] leading-none opacity-80 sm:block">{{ $mark->check_in_at->timezone($organisation->timezone)->format('H:i') }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs text-ink-muted">
                    @foreach ($actions as $case)
                        <span class="flex items-center gap-1.5">
                            <span class="h-3 w-3 rounded-[3px] {{ $legend[$case->value] ?? 'bg-sunken' }}"></span>
                            {{ $case->label() }}
                        </span>
                    @endforeach
                    <span class="flex items-center gap-1.5"><span class="h-3 w-3 rounded-[3px] bg-sunken"></span> Not marked</span>
                </div>
            </div>
        </x-ui.card>

        <div class="space-y-4">
            {{-- Which days they come in, over the last twelve weeks. --}}
            <x-ui.card title="Usual days" description="Visits by weekday, last 12 weeks">
                <div class="flex h-24 items-end gap-1.5">
                    @foreach (['M', 'T', 'W', 'T', 'F', 'S', 'S'] as $index => $letter)
                        @php $count = $byWeekday[$index]; @endphp
                        <div class="flex flex-1 flex-col items-center gap-1">
                            <span class="numeric text-[10px] text-ink-muted">{{ $count ?: '' }}</span>
                            <div class="w-full rounded-t-md {{ $count > 0 ? 'bg-accent' : 'bg-sunken' }}" style="height: {{ max(4, (int) round($count / $weekdayMax * 64)) }}px"></div>
                            <span class="text-[11px] font-medium text-ink-muted">{{ $letter }}</span>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <x-ui.card :padded="false" title="Recent">
                @if ($recentList->isEmpty())
                    <p class="px-4 py-5 text-sm text-ink-muted">Nothing marked in the last 12 weeks.</p>
                @else
                    <ul class="divide-y divide-[var(--c-hairline)]">
                        @foreach ($recentList as $mark)
                            <li class="flex items-center justify-between gap-3 px-4 py-2.5">
                                <div class="min-w-0">
                                    <p class="numeric text-sm text-ink">{{ $mark->attendance_date->format('D, d M') }}</p>
                                    <p class="truncate text-xs text-ink-muted">
                                        {{ $mark->club?->name ?? ($organisation->usesClubs() ? 'No club' : $organisation->name) }}
                                        @if ($mark->check_in_at) · {{ $mark->check_in_at->timezone($organisation->timezone)->format('H:i') }} @endif
                                    </p>
                                </div>
                                <x-ui.badge :tone="$mark->action->tone()" :dot="false">{{ $mark->action->label() }}</x-ui.badge>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card>
        </div>
    </div>
</div>
