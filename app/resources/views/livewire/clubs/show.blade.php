@php
    // Tabs follow the organisation's modules (App\Enums\Feature).
    $tabs = array_filter([
        'overview' => 'Overview',
        'staff' => $organisation->hasFeature('staff') ? $organisation->term('user_plural') : null,
        'members' => $organisation->hasFeature('members') ? $organisation->term('member_plural') : null,
        'finance' => $organisation->hasFeature('payments') || $organisation->hasFeature('expenses') ? 'Finance' : null,
    ]);

    if (! isset($tabs[$tab])) {
        $tab = 'overview';
    }
@endphp

<div>
    <x-ui.flash />

    <x-ui.page-header :title="$club->name" :back="route('tenant.clubs.index')" :back-label="$organisation->term('club_plural')"
        :description="collect([$club->code, $club->phone, $club->address['line1'] ?? null])->filter()->join(' · ')">
        <x-slot:actions>
            @can('markMembers', [\App\Models\Attendance::class, $club->id])
                <x-ui.button icon="clipboard-document-check"
                    :href="route('tenant.attendance.members', ['clubId' => $club->id])" wire:navigate>Attendance</x-ui.button>
            @endcan
            @can('update', $club)
                <x-ui.button variant="primary" icon="pencil-square" :href="route('tenant.clubs.edit', $club)" wire:navigate>Edit</x-ui.button>
            @endcan
        </x-slot:actions>
    </x-ui.page-header>

    <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
        @php $periodQuery = ['club' => $club->id, 'from' => $from, 'to' => $to]; @endphp
        @feature('members')
            <x-ui.stat :label="'Active '.strtolower($organisation->term('member_plural'))" :value="number_format($memberCount)"
                icon="user-group" tone="accent" :href="route('tenant.members.index', ['club' => $club->id, 'status' => 'active'])" wire:navigate />
        @endfeature
        @feature('payments')
            <x-ui.stat label="Revenue" :value="$organisation->moneyCompact($revenue)" icon="banknotes" tone="positive"
                hint="this period" :href="route('tenant.finance.payments.index', ['status' => 'confirmed', ...$periodQuery])" wire:navigate />
        @endfeature
        @feature('expenses')
            <x-ui.stat label="Expenses" :value="$organisation->moneyCompact($expenses)" icon="receipt-percent" tone="critical"
                hint="this period" :href="route('tenant.finance.expenses.index', ['status' => 'completed', ...$periodQuery])" wire:navigate />
        @endfeature
        @feature('attendance')
            <x-ui.stat label="Attendance rate" :value="$attendanceRate.'%'" icon="chart-bar"
                :tone="$attendanceRate >= 60 ? 'positive' : 'caution'" hint="this period" :href="$organisation->hasFeature('reports') ? route('tenant.reports.index', ['tab' => 'attendance', 'range' => $range, ...$periodQuery]) : null" wire:navigate />
        @endfeature
    </div>

    <x-ui.tabs :items="collect($tabs)->map(fn ($label, $key) => [
        'label' => $label,
        'url' => route('tenant.clubs.show', ['club' => $club->id, 'tab' => $key]),
        'active' => $tab === $key,
    ])->values()->all()" />

    @if ($tab === 'overview')
        <x-ui.period-filter :presets="$presets" :range="$range" :from="$from" :to="$to" :today="\Illuminate\Support\Carbon::today($organisation->timezone)->toDateString()" />

        <div class="grid gap-3 lg:grid-cols-2">
            @if ($organisation->hasFeature('payments') || $organisation->hasFeature('expenses'))
            <x-ui.card title="Revenue and expenses" :description="$period->label()">
                <x-ui.chart type="line" :labels="$cashTrend['labels']" :height="230" value-format="currency"
                    :currency-symbol="$organisation->currencySymbol()"
                    :summary="'Revenue '.$organisation->money($revenue).' against '.$organisation->money($expenses).' of expenses.'"
                    :datasets="array_values(array_filter([
                        $organisation->hasFeature('payments') ? ['label' => 'Revenue', 'data' => $cashTrend['revenue'], 'color' => 'positive'] : null,
                        $organisation->hasFeature('expenses') ? ['label' => 'Expenses', 'data' => $cashTrend['expenses'], 'color' => 'critical'] : null,
                    ]))" />
            </x-ui.card>
            @endif

            @feature('attendance')
            <x-ui.card title="Attendance" :description="$period->label()">
                <x-ui.chart type="bar" :labels="$attendanceTrend['labels']" :height="230" stacked
                    :summary="'Attendance rate is '.$attendanceRate.'%.'"
                    :datasets="[
                        ['label' => 'Present', 'data' => $attendanceTrend['present'], 'color' => 'accent'],
                        ['label' => 'Absent', 'data' => $attendanceTrend['absent'], 'color' => 'caution'],
                    ]" />
            </x-ui.card>
            @endfeature

            <x-ui.card class="lg:col-span-2" title="Club settings">
                <dl class="grid gap-x-6 sm:grid-cols-3">
                    <x-ui.definition label="Code" :value="$club->code" />
                    <x-ui.definition label="Status">
                        <x-ui.badge :tone="$club->status->tone()">{{ $club->status->label() }}</x-ui.badge>
                    </x-ui.definition>
                    <x-ui.definition label="Timezone" :value="$club->timezone ?? $organisation->timezone" />
                    <x-ui.definition label="Phone" :value="$club->phone ?: '—'" />
                    <x-ui.definition label="Email" :value="$club->email ?: '—'" />
                    <x-ui.definition label="Address" :value="$club->address['line1'] ?? '—'" />
                </dl>
            </x-ui.card>
        </div>
    @elseif ($tab === 'staff')
        <x-ui.card :padded="false" :title="'Assigned '.strtolower($organisation->term('user_plural'))"
            :description="$staff->count().' with an active assignment'">
            <x-slot:actions>
                @can('update', $club)
                    <x-ui.button size="sm" :href="route('tenant.clubs.edit', $club)" wire:navigate>Manage assignments</x-ui.button>
                @endcan
            </x-slot:actions>

            @if ($staff->isEmpty())
                <x-ui.empty icon="identification" :title="'No '.strtolower($organisation->term('user_plural')).' assigned'"
                    :description="'Assign '.strtolower($organisation->term('user_plural')).' so they can work at this '.strtolower($organisation->term('club_singular')).'.'" />
            @else
                <ul class="divide-y divide-[var(--c-hairline)]">
                    @foreach ($staff as $assignment)
                        @php $person = $assignment->organisationUser; @endphp
                        <li class="flex items-center justify-between gap-3 px-4 py-3">
                            <div class="flex min-w-0 items-center gap-3">
                                <x-ui.avatar :name="$person?->user?->name ?? '?'" size="sm" />
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium text-ink">{{ $person?->user?->name }}</p>
                                    <p class="numeric truncate text-xs text-ink-muted">{{ $person?->user?->phone }}</p>
                                </div>
                            </div>
                            <div class="flex shrink-0 items-center gap-2">
                                <x-ui.badge :tone="$person?->isAdmin() ? 'accent' : 'neutral'" :dot="false">
                                    {{ $person?->isAdmin() ? 'Administrator' : $organisation->term('user_singular') }}
                                </x-ui.badge>
                                <p class="numeric hidden text-xs text-ink-muted sm:block">
                                    since {{ $assignment->assigned_at?->format('d M Y') }}
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.card>
    @elseif ($tab === 'members')
        <x-ui.card :padded="false" :title="$organisation->term('member_plural')" :description="$members->count().' shown'">
            <x-slot:actions>
                <x-ui.button size="sm" :href="route('tenant.members.index', ['club' => $club->id])" wire:navigate>
                    Open full list
                </x-ui.button>
            </x-slot:actions>

            @if ($members->isEmpty())
                <x-ui.empty icon="user-group" :title="'No '.strtolower($organisation->term('member_plural')).' yet'"
                    :description="'Add a '.strtolower($organisation->term('member_singular')).' to this '.strtolower($organisation->term('club_singular')).'.'">
                    <x-slot:actions>
                        @can('create', \App\Models\Member::class)
                            <x-ui.button size="sm" variant="primary" :href="route('tenant.members.create', ['club' => $club->id])" wire:navigate>
                                Add {{ $organisation->term('member_singular') }}
                            </x-ui.button>
                        @endcan
                    </x-slot:actions>
                </x-ui.empty>
            @else
                <ul class="divide-y divide-[var(--c-hairline)]">
                    @foreach ($members as $member)
                        <li>
                            <a href="{{ route('tenant.members.show', $member) }}" wire:navigate
                                class="flex items-center justify-between gap-3 px-4 py-2.5 transition hover:bg-raised">
                                <div class="flex min-w-0 items-center gap-3">
                                    <x-ui.avatar :name="$member->name" size="sm" />
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-medium text-ink">{{ $member->name }}</p>
                                        <p class="truncate text-xs text-ink-muted">{{ $member->phone }}</p>
                                    </div>
                                </div>
                                <x-ui.badge :tone="$member->status->tone()">{{ $member->status->label() }}</x-ui.badge>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.card>
    @elseif ($tab === 'finance')
        <x-ui.period-filter :presets="$presets" :range="$range" :from="$from" :to="$to" :today="\Illuminate\Support\Carbon::today($organisation->timezone)->toDateString()" />

        <div class="grid gap-3 lg:grid-cols-2">
            @feature('payments')
            <x-ui.card :padded="false" title="Recent payments" :description="'Confirmed · '.$period->label()">
                @if ($recentPayments->isEmpty())
                    <x-ui.empty icon="banknotes" title="No confirmed payments" description="Nothing collected in this period." />
                @else
                    <ul class="divide-y divide-[var(--c-hairline)]">
                        @foreach ($recentPayments as $payment)
                            <li class="flex items-center justify-between gap-3 px-4 py-2.5">
                                <div class="min-w-0">
                                    <a href="{{ route('tenant.finance.payments.show', $payment) }}" wire:navigate
                                        class="block truncate text-sm font-medium text-ink hover:text-accent">{{ $payment->payerName() }}</a>
                                    <p class="numeric text-xs text-ink-muted">{{ $payment->payment_date->format('d M Y') }}</p>
                                </div>
                                <p class="numeric shrink-0 text-sm font-medium text-positive">{{ $organisation->money($payment->amount_minor) }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card>
            @endfeature

            @feature('expenses')
            <x-ui.card :padded="false" title="Recent expenses" :description="$period->label()">
                @if ($recentExpenses->isEmpty())
                    <x-ui.empty icon="receipt-percent" title="No expenses" description="Nothing recorded in this period." />
                @else
                    <ul class="divide-y divide-[var(--c-hairline)]">
                        @foreach ($recentExpenses as $expense)
                            <li class="flex items-center justify-between gap-3 px-4 py-2.5">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium text-ink">{{ $expense->category }}</p>
                                    <p class="numeric text-xs text-ink-muted">{{ $expense->expense_date->format('d M Y') }}</p>
                                </div>
                                <p class="numeric shrink-0 text-sm font-medium text-critical">{{ $organisation->money($expense->amount_minor) }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card>
            @endfeature

            @feature('plans')
            <x-ui.card class="lg:col-span-2">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-ink">Outstanding fees at this {{ strtolower($organisation->term('club_singular')) }}</p>
                        <p class="text-xs text-ink-muted">Across all active and expired plans.</p>
                    </div>
                    <p class="numeric font-[family-name:var(--font-display)] text-2xl font-semibold {{ $outstanding > 0 ? 'text-caution' : 'text-positive' }}">
                        {{ $organisation->money($outstanding) }}
                    </p>
                </div>
            </x-ui.card>
            @endfeature
        </div>
    @endif
</div>
