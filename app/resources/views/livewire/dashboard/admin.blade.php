@php
    $delta = function (int|float $current, int|float $previous): array {
        if ($previous == 0) {
            return $current > 0 ? ['+100%', 'positive'] : ['—', 'neutral'];
        }

        $change = round((($current - $previous) / abs($previous)) * 100);

        return [($change > 0 ? '+' : '').$change.'%', $change > 0 ? 'positive' : ($change < 0 ? 'critical' : 'neutral')];
    };

    [$revenueDelta, $revenueTone] = $delta($revenue, $revenuePrevious);
    [$membersDelta, $membersTone] = $delta($newMembers, $newMembersPrevious);
@endphp

<div>
    <x-ui.flash />

    <x-ui.page-header :title="'Good '.(now($organisation->timezone)->hour < 12 ? 'morning' : (now($organisation->timezone)->hour < 17 ? 'afternoon' : 'evening')).', '.\Illuminate\Support\Str::before($membership->user?->name ?? '', ' ')"
        :description="$organisation->name.' · '.$period->label()">
        <x-slot:actions>
            <x-ui.alert-centre :alerts="$alerts" />
        </x-slot:actions>
    </x-ui.page-header>

    @php $quickAction = \App\Support\Navigation::quickAction($organisation, auth()->user(), $membership); @endphp
    @if ($quickAction)
        <x-ui.fab :href="route($quickAction['route'])" :label="$quickAction['label']" :symbol="$quickAction['symbol']" />
    @endif

    <x-ui.period-filter :presets="$presets" :range="$range" :from="$from" :to="$to" :today="\Illuminate\Support\Carbon::today($organisation->timezone)->toDateString()" :clubs="$organisation->usesClubs() ? $clubs : null" :club-label="$organisation->term('club_plural')" />

    @php
        // Every card and panel follows its module (App\Enums\Feature); the
        // grids reflow around whatever is switched on.
        $hasPayments = $organisation->hasFeature('payments');
        $hasExpenses = $organisation->hasFeature('expenses');
        $hasPlans = $organisation->hasFeature('plans');
        $hasMembers = $organisation->hasFeature('members');
        $hasAttendance = $organisation->hasFeature('member_attendance') && $hasMembers;
    @endphp

    {{-- Core cards (MEP 6.2). --}}
    <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
        @if ($hasPayments)
            <x-ui.stat label="Revenue collected" :value="$organisation->moneyCompact($revenue)" icon="banknotes" tone="positive"
                :delta="$revenueDelta" :delta-tone="$revenueTone" hint="vs previous period" :href="$links['revenue']" wire:navigate />
        @endif

        @if ($hasPlans || $organisation->hasFeature('billing'))
            <x-ui.stat label="Outstanding" :value="$organisation->moneyCompact($outstanding)" icon="exclamation-circle"
                :tone="$outstanding > 0 ? 'caution' : 'neutral'" hint="plans, admission and invoices" :href="$links['outstanding']" wire:navigate />
        @endif

        @if ($hasExpenses)
            <x-ui.stat label="Expenses" :value="$organisation->moneyCompact($expenses)" icon="receipt-percent" tone="critical"
                hint="completed only" :href="$links['expenses']" wire:navigate />
        @endif

        @if ($hasPayments && $hasExpenses)
            <x-ui.stat label="Net movement" :value="$organisation->moneyCompact($netMovement)" icon="arrows-right-left"
                :tone="$netMovement >= 0 ? 'positive' : 'critical'" hint="revenue − expenses" :href="$links['net']" wire:navigate />
        @endif

        @if ($hasMembers)
            <x-ui.stat :label="'Active '.strtolower($organisation->term('member_plural'))" :value="number_format($activeMembers)"
                icon="user-group" tone="accent" :href="$links['activeMembers']" wire:navigate />

            <x-ui.stat :label="'New '.strtolower($organisation->term('member_plural'))" :value="number_format($newMembers)"
                icon="user-plus" :delta="$membersDelta" :delta-tone="$membersTone" hint="this period" :href="$links['newMembers']" wire:navigate />
        @endif

        @if ($hasPlans)
            <x-ui.stat label="Expiring plans" :value="number_format($expiring)" icon="clock"
                :tone="$expiring > 0 ? 'caution' : 'neutral'" hint="next 30 days" :href="$links['expiring']" wire:navigate />
        @endif

        @if ($hasPayments)
            <x-ui.stat label="Pending confirmations" :value="number_format($pendingCount)" icon="check-badge"
                :tone="$pendingCount > 0 ? 'caution' : 'positive'" :href="$links['pending']" wire:navigate
                :hint="$organisation->moneyCompact($pendingValue).' awaiting'" />
        @endif

        @if ($hasAttendance)
            <x-ui.stat label="Attendance rate" :value="$attendanceRate.'%'" icon="chart-bar"
                :tone="$attendanceRate >= 60 ? 'positive' : 'caution'" hint="present or late, this period" :href="$links['attendanceRate']" wire:navigate />
            <x-ui.stat label="Today's attendance" :value="number_format($todaysAttendance)" icon="clipboard-document-check"
                :href="$links['todaysAttendance']" wire:navigate hint="checked in today" />
        @endif
        @feature('staff')
            <x-ui.stat :label="'Active '.strtolower($organisation->term('user_plural'))" :value="number_format($activeStaff)"
                icon="identification" :href="$links['staff']" wire:navigate />
        @endfeature
        @if ($organisation->usesClubs())
            <x-ui.stat :label="$organisation->term('club_plural')" :value="number_format($clubs->count())"
                icon="building-office-2" :href="$links['clubs']" wire:navigate hint="active" />
        @endif
    </div>

    {{-- Trends. --}}
    @if ($hasPayments || $hasExpenses || $hasAttendance)
    <div class="mb-4 grid gap-3 lg:grid-cols-2">
        @if ($hasPayments || $hasExpenses)
        <x-ui.card :title="$hasPayments && $hasExpenses ? 'Revenue and expenses' : ($hasPayments ? 'Revenue' : 'Expenses')" :description="$period->label()">
            <x-ui.chart type="line" :labels="$cashTrend['labels']" :height="240" value-format="currency"
                :currency-symbol="$organisation->currencySymbol()"
                :summary="'Revenue totalled '.$organisation->money($revenue).' against '.$organisation->money($expenses).' of expenses.'"
                :datasets="array_values(array_filter([
                    $hasPayments ? ['label' => 'Revenue', 'data' => $cashTrend['revenue'], 'color' => 'positive'] : null,
                    $hasExpenses ? ['label' => 'Expenses', 'data' => $cashTrend['expenses'], 'color' => 'critical'] : null,
                ]))" />
        </x-ui.card>
        @endif

        @if ($hasAttendance)
        <x-ui.card title="Attendance" :description="$period->label()">
            <x-ui.chart type="bar" :labels="$attendanceTrend['labels']" :height="240" stacked
                :summary="'Attendance rate for the period is '.$attendanceRate.'%.'"
                :datasets="[
                    ['label' => 'Present', 'data' => $attendanceTrend['present'], 'color' => 'accent'],
                    ['label' => 'Absent', 'data' => $attendanceTrend['absent'], 'color' => 'caution'],
                ]" />
        </x-ui.card>
        @endif
    </div>
    @endif

    <div class="grid gap-3 lg:grid-cols-3">
        @if ($hasPayments)
        {{-- Pending staff-collected payments: the queue that needs action. --}}
        <x-ui.card class="lg:col-span-2" :padded="false" title="Pending confirmations"
            description="Staff-collected payments awaiting your review.">
            <x-slot:actions>
                <x-ui.button size="sm" variant="ghost" :href="route('tenant.finance.confirmations')" wire:navigate>Open queue</x-ui.button>
            </x-slot:actions>

            @if ($pendingPayments->isEmpty())
                <x-ui.empty icon="check-badge" title="Nothing waiting" description="Every submitted payment has been reviewed." />
            @else
                <ul class="divide-y divide-[var(--c-hairline)]">
                    @foreach ($pendingPayments as $payment)
                        <li class="flex items-center justify-between gap-3 px-4 py-2.5">
                            <div class="flex min-w-0 items-center gap-2.5">
                                <x-ui.avatar :name="$payment->payerName()" size="sm" />
                                <div class="min-w-0">
                                    <a href="{{ route('tenant.finance.payments.show', $payment) }}" wire:navigate
                                        class="block truncate text-sm font-medium text-ink hover:text-accent">{{ $payment->payerName() }}</a>
                                    <p class="truncate text-xs text-ink-muted">
                                        @if ($payment->club){{ $payment->club->name }} · @endif{{ $payment->collectedBy?->user?->name }}
                                        · {{ $payment->created_at?->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <p class="numeric shrink-0 text-sm font-medium">{{ $organisation->money($payment->amount_minor) }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.card>

        <x-ui.card :padded="false" title="Top collectors" description="Confirmed, this period.">
            @if ($leaderboard->isEmpty())
                <x-ui.empty icon="trophy" title="No collections yet" description="Confirmed payments will rank collectors here." />
            @else
                <ol class="divide-y divide-[var(--c-hairline)]">
                    @foreach ($leaderboard as $index => $row)
                        <li class="flex items-center gap-3 px-4 py-2.5">
                            <span class="numeric grid h-6 w-6 shrink-0 place-items-center rounded-full bg-sunken text-xs font-semibold text-ink-soft">{{ $index + 1 }}</span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-ink">{{ $row->name }}</p>
                                <p class="numeric text-xs text-ink-muted">{{ $row->count }} {{ \Illuminate\Support\Str::plural('payment', $row->count) }}</p>
                            </div>
                            <p class="numeric shrink-0 text-sm font-medium">{{ $organisation->moneyCompact($row->collected) }}</p>
                        </li>
                    @endforeach
                </ol>
            @endif
        </x-ui.card>

        @endif

        @if ($organisation->usesClubs())
        {{-- Club comparison. --}}
        <x-ui.card class="lg:col-span-2" :padded="false" :title="$organisation->term('club_plural').' comparison'">
            @if ($clubComparison->isEmpty())
                <x-ui.empty icon="building-office-2" :title="'No '.strtolower($organisation->term('club_plural')).' yet'"
                    :description="'Create a '.strtolower($organisation->term('club_singular')).' to start comparing performance.'" />
            @else
                <x-ui.table>
                    <x-slot:head>
                        <x-ui.th>{{ $organisation->term('club_singular') }}</x-ui.th>
                        <x-ui.th align="right">{{ $organisation->term('member_plural') }}</x-ui.th>
                        <x-ui.th align="right">Attendance</x-ui.th>
                        <x-ui.th align="right">Revenue</x-ui.th>
                        <x-ui.th align="right">Expenses</x-ui.th>
                    </x-slot:head>

                    @foreach ($clubComparison as $row)
                        <tr class="transition hover:bg-list-hover">
                            <x-ui.td class="font-medium text-ink">{{ $row->club }}</x-ui.td>
                            <x-ui.td align="right" numeric>{{ number_format($row->members) }}</x-ui.td>
                            <x-ui.td align="right" numeric>{{ number_format($row->attendance) }}</x-ui.td>
                            <x-ui.td align="right" numeric class="text-positive">{{ $organisation->money($row->revenue) }}</x-ui.td>
                            <x-ui.td align="right" numeric class="text-critical">{{ $organisation->money($row->expenses) }}</x-ui.td>
                        </tr>
                    @endforeach
                </x-ui.table>
            @endif
        </x-ui.card>

        @endif

        @if ($hasPlans)
        <x-ui.card :padded="false" title="Needs follow-up" description="Expiring soon or unpaid.">
            @if ($followUps->isEmpty())
                <x-ui.empty icon="check-circle" title="All clear" description="No plans expiring soon or carrying a balance." />
            @else
                <ul class="divide-y divide-[var(--c-hairline)]">
                    @foreach ($followUps as $subscription)
                        @php $due = max(0, $subscription->amount_due_minor - $subscription->amount_paid_minor); @endphp
                        <li class="px-4 py-2.5">
                            <a href="{{ route('tenant.members.show', $subscription->member) }}" wire:navigate class="block group">
                                <p class="truncate text-sm font-medium text-ink group-hover:text-accent">{{ $subscription->member->name }}</p>
                                <p class="truncate text-xs text-ink-muted">
                                    {{ $subscription->plan->name }} · expires {{ $subscription->end_date->format('d M') }}
                                </p>
                                @if ($due > 0)
                                    <p class="numeric mt-0.5 text-xs font-medium text-caution">{{ $organisation->money($due) }} outstanding</p>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.card>

        @endif

        @if ($hasPayments)
        <x-ui.card :padded="false" title="Recent payments">
            @if ($recentPayments->isEmpty())
                <x-ui.empty icon="banknotes" title="No confirmed payments" description="Confirmed collections appear here." />
            @else
                <ul class="divide-y divide-[var(--c-hairline)]">
                    @foreach ($recentPayments as $payment)
                        <li class="flex items-center justify-between gap-3 px-4 py-2.5">
                            <div class="min-w-0">
                                <a href="{{ route('tenant.finance.payments.show', $payment) }}" wire:navigate
                                    class="block truncate text-sm font-medium text-ink hover:text-accent">{{ $payment->payerName() }}</a>
                                <p class="numeric truncate text-xs text-ink-muted">{{ $payment->payment_date->format('d M') }}@if ($payment->club) · {{ $payment->club->name }}@endif</p>
                            </div>
                            <p class="numeric shrink-0 text-sm font-medium text-positive">{{ $organisation->money($payment->amount_minor) }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.card>

        @endif

        @if ($hasExpenses)
        <x-ui.card :padded="false" title="Recent expenses" class="lg:col-span-2">
            @if ($recentExpenses->isEmpty())
                <x-ui.empty icon="receipt-percent" title="No expenses recorded" description="Completed expenses appear here." />
            @else
                <ul class="divide-y divide-[var(--c-hairline)]">
                    @foreach ($recentExpenses as $expense)
                        <li class="flex items-center justify-between gap-3 px-4 py-2.5">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-ink">{{ $expense->category }}</p>
                                <p class="numeric truncate text-xs text-ink-muted">
                                    {{ $expense->expense_date->format('d M') }} ·
                                    {{ $expense->club?->name ?? 'Organisation-wide' }}
                                    @if ($expense->payee) · {{ $expense->payee }} @endif
                                </p>
                            </div>
                            <p class="numeric shrink-0 text-sm font-medium text-critical">{{ $organisation->money($expense->amount_minor) }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.card>
        @endif
    </div>
</div>
