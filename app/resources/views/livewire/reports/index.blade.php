@php
    /** Renders a proportional breakdown without needing a chart. */
    $breakdown = function (array $rows, callable $format) {
        $max = max([1, ...array_map('abs', $rows)]);

        return collect($rows)->map(fn ($value, $label) => [
            'label' => $label,
            'value' => $format($value),
            'width' => max(2, (int) round(abs($value) / $max * 100)),
        ]);
    };
@endphp

<div>
    <x-ui.page-header title="Reports" :description="$organisation->name.' · '.$period->label()">
        <x-slot:actions>
            <x-ui.download-button icon="arrow-down-tray" what="this report as a CSV" :note="'It covers '.$period->label().'.'" :href="route('tenant.reports.export', ['report' => $tab, ...$exportQuery])">
                Export CSV
            </x-ui.download-button>
            <x-ui.download-button icon="document-arrow-down" what="the PDF summary of this report" :note="'It covers '.$period->label().'.'" :href="route('tenant.reports.pdf', ['report' => $tab, ...$exportQuery])" target="_blank">
                PDF summary
            </x-ui.download-button>
        </x-slot:actions>
    </x-ui.page-header>

    @php
        $hasPayments = $organisation->hasFeature('payments');
        $hasExpenses = $organisation->hasFeature('expenses');
        $hasPlans = $organisation->hasFeature('plans');
    @endphp

    <x-ui.tabs :items="collect($reportTabs)->map(fn ($label, $key) => [
        'label' => $label,
        'url' => route('tenant.reports.index', ['tab' => $key, ...$exportQuery]),
        'active' => $tab === $key,
    ])->values()->all()" />

    <x-ui.period-filter :presets="$presets" :range="$range" :clubs="$organisation->usesClubs() ? $clubs : null" :club-label="$organisation->term('club_plural')" />

    {{-- Changing the period or club re-queries every figure below; the
         figures stay put, dimmed, while that happens. --}}
    <div class="relative">
        <x-ui.list-loader />
    @if ($tab === 'finance')
        <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
            @if ($hasPayments)
                <x-ui.stat label="Confirmed revenue" :value="$organisation->money($revenue)" icon="banknotes" tone="positive" />
            @endif
            @if ($hasExpenses)
                <x-ui.stat label="Expenses" :value="$organisation->money($expenses)" icon="receipt-percent" tone="critical" />
            @endif
            @if ($hasPayments && $hasExpenses)
                <x-ui.stat label="Net movement" :value="$organisation->money($netMovement)" icon="arrows-right-left"
                    :tone="$netMovement >= 0 ? 'positive' : 'critical'" />
            @endif
            @if ($hasPlans)
                <x-ui.stat label="Outstanding fees" :value="$organisation->money($outstanding)" icon="exclamation-circle"
                    :tone="$outstanding > 0 ? 'caution' : 'neutral'" hint="all active plans" />
            @endif
        </div>

        <x-ui.card class="mb-4" :title="$hasPayments && $hasExpenses ? 'Revenue and expenses' : ($hasPayments ? 'Revenue' : 'Expenses')" :description="$period->label()">
            <x-ui.chart type="line" :labels="$cashTrend['labels']" :height="280" value-format="currency"
                :currency-symbol="$organisation->currencySymbol()"
                :summary="'Revenue '.$organisation->money($revenue).', expenses '.$organisation->money($expenses).', net '.$organisation->money($netMovement).'.'"
                :datasets="array_values(array_filter([
                    $hasPayments ? ['label' => 'Revenue', 'data' => $cashTrend['revenue'], 'color' => 'positive'] : null,
                    $hasExpenses ? ['label' => 'Expenses', 'data' => $cashTrend['expenses'], 'color' => 'critical'] : null,
                    $hasPayments && $hasExpenses ? ['label' => 'Net', 'data' => $cashTrend['net'], 'color' => 'accent', 'fill' => false] : null,
                ]))" />
        </x-ui.card>

        @if ($hasPayments)
        <x-ui.card class="mb-4" :padded="false" title="Payment lifecycle"
            description="Only confirmed payments contribute to revenue and account statements.">
            <x-ui.table>
                <x-slot:head>
                    <x-ui.th>Status</x-ui.th>
                    <x-ui.th align="right">Payments</x-ui.th>
                    <x-ui.th align="right">Value</x-ui.th>
                    <x-ui.th>Counts towards revenue</x-ui.th>
                </x-slot:head>

                @foreach (\App\Enums\ConfirmationStatus::cases() as $case)
                    <tr class="transition hover:bg-raised">
                        <x-ui.td><x-ui.badge :tone="$case->tone()">{{ $case->label() }}</x-ui.badge></x-ui.td>
                        <x-ui.td align="right" numeric>{{ number_format($statusTotals[$case->value]['count']) }}</x-ui.td>
                        <x-ui.td align="right" numeric class="font-medium text-ink">{{ $organisation->money($statusTotals[$case->value]['total']) }}</x-ui.td>
                        <x-ui.td class="{{ $case->value === 'confirmed' ? 'text-positive' : 'text-ink-muted' }}">
                            {{ $case->value === 'confirmed' ? 'Yes' : 'No' }}
                        </x-ui.td>
                    </tr>
                @endforeach
            </x-ui.table>

            @if ($turnaround !== null)
                <p class="border-t border-hairline px-4 py-2.5 text-xs text-ink-muted">
                    Average admin confirmation turnaround:
                    <span class="numeric font-medium text-ink-soft">{{ $turnaround }} hours</span>
                </p>
            @endif
        </x-ui.card>
        @endif

        <div class="mb-4 grid gap-3 lg:grid-cols-2">
            @foreach (array_values(array_filter([
                $hasPayments ? ['Revenue by payment method', $methodSplit, 'credit-card'] : null,
                $hasPayments && $hasPlans ? ['Revenue by plan', $planSplit, 'rectangle-stack'] : null,
                $hasPayments ? ['Revenue by account', $accountSplit, 'building-library'] : null,
                $hasExpenses ? ['Expenses by category', $categorySplit, 'receipt-percent'] : null,
            ])) as [$title, $rows, $icon])
                <x-ui.card :title="$title">
                    @if (empty($rows))
                        <p class="py-4 text-center text-sm text-ink-muted">Nothing recorded in this period.</p>
                    @else
                        <ul class="space-y-2.5">
                            @foreach ($breakdown($rows, fn ($v) => $organisation->money((int) $v)) as $row)
                                <li>
                                    <div class="flex items-baseline justify-between gap-3 text-sm">
                                        <span class="truncate text-ink-soft">{{ \Illuminate\Support\Str::of($row['label'])->replace('_', ' ')->ucfirst() }}</span>
                                        <span class="numeric shrink-0 font-medium text-ink">{{ $row['value'] }}</span>
                                    </div>
                                    <div class="mt-1 h-1.5 overflow-hidden rounded-full bg-sunken">
                                        <div class="h-full rounded-full bg-accent" style="width: {{ $row['width'] }}%"></div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </x-ui.card>
            @endforeach
        </div>

        <div class="grid gap-3 lg:grid-cols-2">
            @if ($organisation->usesClubs())
            <x-ui.card :padded="false" :title="$organisation->term('club_plural').' performance'">
                @if ($clubComparison->isEmpty())
                    <x-ui.empty icon="building-office-2" title="No data" description="No clubs in scope for this period." />
                @else
                    <x-ui.table>
                        <x-slot:head>
                            <x-ui.th>{{ $organisation->term('club_singular') }}</x-ui.th>
                            <x-ui.th align="right">Revenue</x-ui.th>
                            <x-ui.th align="right">Expenses</x-ui.th>
                            <x-ui.th align="right">Net</x-ui.th>
                        </x-slot:head>

                        @foreach ($clubComparison as $row)
                            <tr class="transition hover:bg-raised">
                                <x-ui.td class="font-medium text-ink">{{ $row->club }}</x-ui.td>
                                <x-ui.td align="right" numeric class="text-positive">{{ $organisation->money($row->revenue) }}</x-ui.td>
                                <x-ui.td align="right" numeric class="text-critical">{{ $organisation->money($row->expenses) }}</x-ui.td>
                                <x-ui.td align="right" numeric class="font-medium">{{ $organisation->money($row->revenue - $row->expenses) }}</x-ui.td>
                            </tr>
                        @endforeach
                    </x-ui.table>
                @endif
            </x-ui.card>
            @endif

            @if ($hasPayments)
            <x-ui.card :padded="false" title="Collections by staff">
                @if ($leaderboard->isEmpty())
                    <x-ui.empty icon="trophy" title="No collections" description="No confirmed payments in this period." />
                @else
                    <x-ui.table>
                        <x-slot:head>
                            <x-ui.th>{{ $organisation->term('user_singular') }}</x-ui.th>
                            <x-ui.th align="right">Payments</x-ui.th>
                            <x-ui.th align="right">Collected</x-ui.th>
                        </x-slot:head>

                        @foreach ($leaderboard as $row)
                            <tr class="transition hover:bg-raised">
                                <x-ui.td class="font-medium text-ink">{{ $row->name }}</x-ui.td>
                                <x-ui.td align="right" numeric>{{ number_format($row->count) }}</x-ui.td>
                                <x-ui.td align="right" numeric class="font-medium">{{ $organisation->money($row->collected) }}</x-ui.td>
                            </tr>
                        @endforeach
                    </x-ui.table>
                @endif
            </x-ui.card>
            @endif
        </div>
    @elseif ($tab === 'members')
        <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
            <x-ui.stat :label="'Active '.strtolower($organisation->term('member_plural'))" :value="number_format($activeMembers)" icon="user-group" tone="accent" />
            <x-ui.stat label="Joined this period" :value="number_format($newMembers)" icon="user-plus" tone="positive" />
            <x-ui.stat label="Expiring plans" :value="number_format($expiring)" icon="clock" :tone="$expiring > 0 ? 'caution' : 'neutral'" hint="next 30 days" />
            <x-ui.stat label="Needing follow-up" :value="number_format($followUps->count())" icon="phone" tone="caution" />
        </div>

        <x-ui.card class="mb-4" title="Growth and churn" :description="$period->label()">
            <x-ui.chart type="bar" :labels="$growth['labels']" :height="260"
                :summary="number_format($newMembers).' joined during this period.'"
                :datasets="[
                    ['label' => 'Joined', 'data' => $growth['joined'], 'color' => 'positive'],
                    ['label' => 'Left', 'data' => $growth['left'], 'color' => 'critical'],
                ]" />
        </x-ui.card>

        <div class="grid gap-3 lg:grid-cols-3">
            <x-ui.card :title="$organisation->term('member_singular').' status'">
                @if (empty($statusSplit))
                    <p class="py-4 text-center text-sm text-ink-muted">No members in scope.</p>
                @else
                    <ul class="space-y-2.5">
                        @foreach ($breakdown($statusSplit, fn ($v) => number_format((int) $v)) as $row)
                            <li>
                                <div class="flex items-baseline justify-between gap-3 text-sm">
                                    <span class="capitalize text-ink-soft">{{ $row['label'] }}</span>
                                    <span class="numeric font-medium text-ink">{{ $row['value'] }}</span>
                                </div>
                                <div class="mt-1 h-1.5 overflow-hidden rounded-full bg-sunken">
                                    <div class="h-full rounded-full bg-accent" style="width: {{ $row['width'] }}%"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card>

            <x-ui.card class="lg:col-span-2" :padded="false" title="Follow-up list"
                description="Plans expiring within 14 days or carrying an outstanding balance.">
                @if ($followUps->isEmpty())
                    <x-ui.empty icon="check-circle" title="All clear" description="No follow-ups needed right now." />
                @else
                    <x-ui.table>
                        <x-slot:head>
                            <x-ui.th>{{ $organisation->term('member_singular') }}</x-ui.th>
                            <x-ui.th>Plan</x-ui.th>
                            <x-ui.th>Expires</x-ui.th>
                            <x-ui.th align="right">Outstanding</x-ui.th>
                        </x-slot:head>

                        @foreach ($followUps as $subscription)
                            @php $due = max(0, $subscription->amount_due_minor - $subscription->amount_paid_minor); @endphp
                            <tr class="transition hover:bg-raised">
                                <x-ui.td>
                                    <a href="{{ route('tenant.members.show', $subscription->member) }}" wire:navigate
                                        class="font-medium text-ink hover:text-accent">{{ $subscription->member->name }}</a>
                                    <span class="block text-xs text-ink-muted">{{ $subscription->member->phone }}</span>
                                </x-ui.td>
                                <x-ui.td>{{ $subscription->plan->name }}</x-ui.td>
                                <x-ui.td numeric>{{ $subscription->end_date->format('d M Y') }}</x-ui.td>
                                <x-ui.td align="right" numeric class="{{ $due > 0 ? 'font-medium text-caution' : '' }}">
                                    {{ $due > 0 ? $organisation->money($due) : '—' }}
                                </x-ui.td>
                            </tr>
                        @endforeach
                    </x-ui.table>
                @endif
            </x-ui.card>
        </div>
    @elseif ($tab === 'attendance')
        <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
            <x-ui.stat label="Attendance rate" :value="$attendanceRate.'%'" icon="chart-bar"
                :tone="$attendanceRate >= 60 ? 'positive' : 'caution'" hint="present or late" />
            @foreach (\App\Enums\AttendanceAction::cases() as $case)
                @if ($loop->index < 3)
                    <x-ui.stat :label="$case->label()" :value="number_format($actionSplit[$case->value] ?? 0)"
                        :tone="$case->tone()" icon="clipboard-document-check" />
                @endif
            @endforeach
        </div>

        <x-ui.card class="mb-4" title="Attendance over time" :description="$period->label()">
            <x-ui.chart type="bar" :labels="$attendanceTrend['labels']" :height="280" stacked
                :summary="'Attendance rate for the period is '.$attendanceRate.'%.'"
                :datasets="[
                    ['label' => 'Present', 'data' => $attendanceTrend['present'], 'color' => 'accent'],
                    ['label' => 'Absent or excused', 'data' => $attendanceTrend['absent'], 'color' => 'caution'],
                ]" />
        </x-ui.card>

        @if ($organisation->usesClubs())
        <x-ui.card :padded="false" :title="'Attendance by '.strtolower($organisation->term('club_singular'))">
            @if ($clubComparison->isEmpty())
                <x-ui.empty icon="building-office-2" title="No data" description="No clubs in scope for this period." />
            @else
                <x-ui.table>
                    <x-slot:head>
                        <x-ui.th>{{ $organisation->term('club_singular') }}</x-ui.th>
                        <x-ui.th align="right">Active {{ strtolower($organisation->term('member_plural')) }}</x-ui.th>
                        <x-ui.th align="right">Present marks</x-ui.th>
                    </x-slot:head>

                    @foreach ($clubComparison as $row)
                        <tr class="transition hover:bg-raised">
                            <x-ui.td class="font-medium text-ink">{{ $row->club }}</x-ui.td>
                            <x-ui.td align="right" numeric>{{ number_format($row->members) }}</x-ui.td>
                            <x-ui.td align="right" numeric>{{ number_format($row->attendance) }}</x-ui.td>
                        </tr>
                    @endforeach
                </x-ui.table>
            @endif
        </x-ui.card>
        @endif
    @endif
    </div>
</div>
