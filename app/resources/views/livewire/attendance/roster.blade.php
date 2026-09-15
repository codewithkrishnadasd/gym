@php
    $isMembers = $subjectType === \App\Enums\AttendanceSubjectType::Member;
    $rosterLabel = $isMembers ? $organisation->term('member_plural') : $organisation->term('user_plural');

    $tones = [
        'present' => ['positive', 'check'],
        'late' => ['caution', 'clock'],
        'excused' => ['info', 'hand-raised'],
        'absent' => ['critical', 'x-mark'],
    ];
@endphp

<div>
    <x-ui.flash />

    <x-ui.page-header title="Attendance" :description="$isStaffRoster
        ? 'Mark who is in for the day. '.$organisation->term('user_plural').' are marked once per day'.($organisation->usesClubs() ? ', whichever '.strtolower($organisation->term('club_plural')).' they work across.' : '.')
        : ($organisation->usesClubs() ? 'Mark the daily roster for one '.strtolower($organisation->term('club_singular')).' at a time.' : 'Mark the daily roster.')" />

    @php
        // One tab per roster the user may mark; a lone roster needs no tabs.
        $rosterTabs = array_values(array_filter([
            auth()->user()?->can('markMembers', \App\Models\Attendance::class) ? ['label' => $organisation->term('member_plural'), 'route' => 'tenant.attendance.members'] : null,
            auth()->user()?->can('markStaff', \App\Models\Attendance::class) ? ['label' => $organisation->term('user_plural'), 'route' => 'tenant.attendance.staff'] : null,
        ]));
    @endphp

    @if (count($rosterTabs) > 1)
        <x-ui.tabs :items="$rosterTabs" />
    @endif

    @if (! $isStaffRoster && $organisation->usesClubs() && $clubs->isEmpty())
        <x-ui.card>
            <x-ui.empty icon="building-office-2" :title="'No '.strtolower($organisation->term('club_plural')).' available'"
                :description="'You need at least one active '.strtolower($organisation->term('club_singular')).' assigned to you before attendance can be marked.'" />
        </x-ui.card>
    @else
        {{-- Date + club selection, and the day's summary. --}}
        <div class="mb-4 grid gap-3 lg:grid-cols-[1fr_auto]">
            <x-ui.card :padded="false">
                <div class="flex flex-col gap-3 p-3 sm:flex-row sm:items-center">
                    <div class="flex items-center gap-1">
                        <x-ui.button size="icon" variant="secondary" wire:click="shiftDate(-1)" aria-label="Previous day">
                            <x-heroicon-o-chevron-left class="h-4 w-4" />
                        </x-ui.button>

                        <label class="relative flex-1">
                            <span class="sr-only">Attendance date</span>
                            <x-ui.date-input bare wire:model.live="date" aria-label="Attendance date" class="w-full" />
                        </label>

                        <x-ui.button size="icon" variant="secondary" wire:click="shiftDate(1)" aria-label="Next day">
                            <x-heroicon-o-chevron-right class="h-4 w-4" />
                        </x-ui.button>

                        @unless ($isToday)
                            <x-ui.button size="sm" variant="ghost" wire:click="goToToday">Today</x-ui.button>
                        @endunless
                    </div>

                    @if (! $isStaffRoster && $organisation->usesClubs())
                        <x-ui.filter-select wire:model.live="clubId" label="Club" class="sm:max-w-xs">
                            @foreach ($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </x-ui.filter-select>
                    @endif

                    <div class="relative min-w-0 flex-1">
                        <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-muted" />
                        <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search name or phone…"
                            aria-label="Search roster"
                            class="min-h-[40px] w-full rounded-lg border border-hairline-strong bg-surface py-2 pl-9 pr-3 text-sm text-ink placeholder:text-ink-muted focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25 max-lg:min-h-[44px]">
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card :padded="false" class="lg:min-w-[260px]">
                <div class="flex items-center justify-around gap-4 p-3 text-center">
                    <div>
                        <p class="numeric font-[family-name:var(--font-display)] text-xl font-semibold text-positive">{{ $presentCount }}</p>
                        <p class="text-[11px] uppercase tracking-wide text-ink-muted">Present</p>
                    </div>
                    <div>
                        <p class="numeric font-[family-name:var(--font-display)] text-xl font-semibold">{{ $markedCount }}/{{ $roster->count() }}</p>
                        <p class="text-[11px] uppercase tracking-wide text-ink-muted">Marked</p>
                    </div>
                    <div>
                        <p class="numeric font-[family-name:var(--font-display)] text-xl font-semibold {{ $unmarkedCount > 0 ? 'text-caution' : 'text-ink-muted' }}">{{ $unmarkedCount }}</p>
                        <p class="text-[11px] uppercase tracking-wide text-ink-muted">Unmarked</p>
                    </div>
                </div>
            </x-ui.card>
        </div>

        @if ($bulkResult)
            <div class="mb-4"><x-ui.alert tone="positive">{{ $bulkResult }}</x-ui.alert></div>
        @endif

        <x-ui.card :padded="false"
            :title="$rosterLabel.' — '.\Illuminate\Support\Carbon::parse($date)->format('D, d M Y')"
            :description="$roster->count().' on this roster'">
            @if ($canMark && $unmarkedCount > 0)
                <x-slot:actions>
                    <x-ui.button size="sm" variant="secondary" icon="check-circle" wire:click="markAllPresent"
                        data-confirm-title="Mark everyone present?" data-confirm-action="Mark all present" data-confirm-tone="accent" data-confirm="Mark the {{ $unmarkedCount }} unmarked {{ strtolower($rosterLabel) }} as present?"
                        wire:loading.attr="disabled" wire:target="markAllPresent">
                        Mark all present
                    </x-ui.button>
                </x-slot:actions>
            @endif

            <x-ui.list-loader />

            @if ($roster->isEmpty())
                <x-ui.empty icon="user-group" :title="'Nobody on this roster'"
                    :description="$search !== '' ? 'No one matches “'.$search.'”.' : ($isStaffRoster ? 'No active '.strtolower($organisation->term('user_plural')).' yet.' : ($organisation->usesClubs() ? 'Nobody is assigned to this '.strtolower($organisation->term('club_singular')).' yet.' : 'No active '.strtolower($organisation->term('member_plural')).' yet.'))" />
            @else
                <ul class="divide-y divide-[var(--c-hairline)]">
                    @foreach ($roster as $person)
                        @php $record = $attendance->get($person->id); @endphp

                        <li class="flex flex-col gap-3 p-3 sm:flex-row sm:items-center sm:justify-between sm:px-4">
                            <div class="flex min-w-0 items-center gap-3">
                                <x-ui.avatar :name="$person->name" size="sm" />
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium text-ink">{{ $person->name }}</p>
                                    <p class="truncate text-xs text-ink-muted">
                                        {{ $person->detail }}
                                        @if ($record)
                                            &middot; marked by {{ $record->markedBy?->user?->name ?? 'system' }}
                                            {{ $record->updated_at?->diffForHumans() }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            {{-- Large, always-visible tap targets: never hover-only (MEP 9.3). --}}
                            <div class="grid shrink-0 grid-cols-4 gap-1.5 sm:flex" role="group"
                                aria-label="Attendance for {{ $person->name }}">
                                @foreach ($actions as $action)
                                    @php
                                        $selected = $record?->action === $action;
                                        [$tone, $icon] = $tones[$action->value];
                                        $activeClasses = [
                                            'positive' => 'bg-positive text-white border-positive',
                                            'caution' => 'bg-caution text-white border-caution',
                                            'info' => 'bg-info text-white border-info',
                                            'critical' => 'bg-critical text-white border-critical',
                                        ][$tone];
                                    @endphp

                                    <button type="button"
                                        @disabled(! $canMark)
                                        wire:click="mark({{ $person->id }}, '{{ $action->value }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="mark({{ $person->id }}, '{{ $action->value }}')"
                                        aria-pressed="{{ $selected ? 'true' : 'false' }}"
                                        @class([
                                            // Icon over label on a phone: four side-by-side labels need ~267px of
                                            // a 262px row at 320px, so the last one was clipped.
                                            'flex min-h-[52px] flex-col items-center justify-center gap-0.5 rounded-lg border px-1 text-[11px] font-medium leading-tight transition sm:min-h-[44px] sm:flex-row sm:gap-1.5 sm:px-2.5 sm:text-xs sm:min-w-[86px]',
                                            $activeClasses => $selected,
                                            'border-hairline-strong text-ink-soft hover:bg-sunken' => ! $selected,
                                            'cursor-not-allowed opacity-50' => ! $canMark,
                                        ])>
                                        <x-dynamic-component :component="'heroicon-o-'.$icon" class="h-4 w-4 shrink-0" />
                                        <span class="capitalize">{{ $action->value }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.card>

        @unless ($canMark)
            <div class="mt-4">
                <x-ui.alert tone="caution" title="View only">
                    You can see this roster but do not have permission to mark attendance for it.
                </x-ui.alert>
            </div>
        @endunless
    @endif
</div>
