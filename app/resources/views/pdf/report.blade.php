<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ ucfirst($report) }} report — {{ $organisation->name }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; margin: 0; padding: 28px 32px; }
        h1 { font-size: 17px; margin: 0 0 2px; }
        h2 { font-size: 12px; margin: 22px 0 6px; text-transform: uppercase; letter-spacing: 0.04em; color: #374151; }
        .muted { color: #6b7280; }
        .header { border-bottom: 2px solid #111827; padding-bottom: 12px; margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; border-bottom: 1px solid #d1d5db; padding: 6px 4px; color: #6b7280; font-size: 10px; text-transform: uppercase; }
        td { padding: 6px 4px; border-bottom: 1px solid #f3f4f6; }
        .right { text-align: right; }
        .cards td { width: 25%; background: #f9fafb; border: 1px solid #e5e7eb; padding: 10px; }
        .cards .label { color: #6b7280; font-size: 10px; display: block; }
        .cards .value { font-size: 14px; font-weight: bold; }
        .footer { margin-top: 24px; padding-top: 10px; border-top: 1px solid #e5e7eb; font-size: 10px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $organisation->name }} — {{ ucfirst($report) }} report</h1>
        <div class="muted">{{ $period->label() }}</div>
    </div>

    <p class="muted">
        Scope: {{ $clubNames !== '' ? $clubNames : 'No clubs in scope' }}<br>
        Generated {{ $generatedAt->format('d M Y H:i') }} ({{ $organisation->timezone }})
    </p>

    @if ($report === 'finance')
        <table class="cards" style="margin-top: 14px;">
            <tr>
                <td><span class="label">Confirmed revenue</span><span class="value">{{ $organisation->money($metrics->revenueCollected()) }}</span></td>
                <td><span class="label">Expenses</span><span class="value">{{ $organisation->money($metrics->expensesRecorded()) }}</span></td>
                <td><span class="label">Net movement</span><span class="value">{{ $organisation->money($metrics->netMovement()) }}</span></td>
                <td><span class="label">Outstanding fees</span><span class="value">{{ $organisation->money($metrics->outstandingFees()) }}</span></td>
            </tr>
        </table>

        <h2>Payment lifecycle</h2>
        <table>
            <tr><th>Status</th><th class="right">Payments</th><th class="right">Value</th><th>Counts as revenue</th></tr>
            @foreach (\App\Enums\ConfirmationStatus::cases() as $case)
                <tr>
                    <td>{{ $case->label() }}</td>
                    <td class="right">{{ number_format($statusTotals[$case->value]['count']) }}</td>
                    <td class="right">{{ $organisation->money($statusTotals[$case->value]['total']) }}</td>
                    <td>{{ $case->value === 'confirmed' ? 'Yes' : 'No' }}</td>
                </tr>
            @endforeach
        </table>

        <h2>{{ $organisation->term('club_plural') }}</h2>
        <table>
            <tr>
                <th>{{ $organisation->term('club_singular') }}</th>
                <th class="right">{{ $organisation->term('member_plural') }}</th>
                <th class="right">Revenue</th>
                <th class="right">Expenses</th>
                <th class="right">Net</th>
            </tr>
            @forelse ($clubComparison as $row)
                <tr>
                    <td>{{ $row->club }}</td>
                    <td class="right">{{ number_format($row->members) }}</td>
                    <td class="right">{{ $organisation->money($row->revenue) }}</td>
                    <td class="right">{{ $organisation->money($row->expenses) }}</td>
                    <td class="right">{{ $organisation->money($row->revenue - $row->expenses) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="muted">No clubs in scope.</td></tr>
            @endforelse
        </table>

        <h2>Revenue by payment method</h2>
        <table>
            <tr><th>Method</th><th class="right">Value</th></tr>
            @forelse ($methodSplit as $method => $value)
                <tr><td>{{ ucfirst(str_replace('_', ' ', (string) $method)) }}</td><td class="right">{{ $organisation->money($value) }}</td></tr>
            @empty
                <tr><td colspan="2" class="muted">No confirmed payments in this period.</td></tr>
            @endforelse
        </table>

        <h2>Expenses by category</h2>
        <table>
            <tr><th>Category</th><th class="right">Value</th></tr>
            @forelse ($categorySplit as $category => $value)
                <tr><td>{{ $category }}</td><td class="right">{{ $organisation->money($value) }}</td></tr>
            @empty
                <tr><td colspan="2" class="muted">No expenses in this period.</td></tr>
            @endforelse
        </table>
    @elseif ($report === 'members')
        <table class="cards" style="margin-top: 14px;">
            <tr>
                <td><span class="label">Active</span><span class="value">{{ number_format($metrics->activeMembers()) }}</span></td>
                <td><span class="label">Joined this period</span><span class="value">{{ number_format($metrics->newMembers()) }}</span></td>
                <td><span class="label">Expiring (30 days)</span><span class="value">{{ number_format($metrics->expiringSubscriptions()) }}</span></td>
                <td><span class="label">Outstanding fees</span><span class="value">{{ $organisation->money($metrics->outstandingFees()) }}</span></td>
            </tr>
        </table>

        <h2>{{ $organisation->term('member_singular') }} status</h2>
        <table>
            <tr><th>Status</th><th class="right">Count</th></tr>
            @forelse ($metrics->memberStatusSplit() as $status => $count)
                <tr><td>{{ ucfirst((string) $status) }}</td><td class="right">{{ number_format($count) }}</td></tr>
            @empty
                <tr><td colspan="2" class="muted">No members in scope.</td></tr>
            @endforelse
        </table>

        <h2>Needing follow-up</h2>
        <table>
            <tr><th>{{ $organisation->term('member_singular') }}</th><th>Plan</th><th>Expires</th><th class="right">Outstanding</th></tr>
            @forelse ($metrics->membersNeedingFollowUp(25) as $subscription)
                <tr>
                    <td>{{ $subscription->member->name }}</td>
                    <td>{{ $subscription->plan->name }}</td>
                    <td>{{ $subscription->end_date->format('d M Y') }}</td>
                    <td class="right">{{ $organisation->money(max(0, $subscription->amount_due_minor - $subscription->amount_paid_minor)) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="muted">Nothing needs following up.</td></tr>
            @endforelse
        </table>
    @else
        <table class="cards" style="margin-top: 14px;">
            <tr>
                <td><span class="label">Attendance rate</span><span class="value">{{ $metrics->attendanceRate() }}%</span></td>
                @foreach (\App\Enums\AttendanceAction::cases() as $case)
                    @if ($loop->index < 3)
                        <td><span class="label">{{ $case->label() }}</span><span class="value">{{ number_format($metrics->attendanceActionSplit()[$case->value] ?? 0) }}</span></td>
                    @endif
                @endforeach
            </tr>
        </table>

        <h2>Attendance by {{ strtolower($organisation->term('club_singular')) }}</h2>
        <table>
            <tr>
                <th>{{ $organisation->term('club_singular') }}</th>
                <th class="right">Active {{ strtolower($organisation->term('member_plural')) }}</th>
                <th class="right">Present marks</th>
            </tr>
            @forelse ($clubComparison as $row)
                <tr>
                    <td>{{ $row->club }}</td>
                    <td class="right">{{ number_format($row->members) }}</td>
                    <td class="right">{{ number_format($row->attendance) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="muted">No clubs in scope.</td></tr>
            @endforelse
        </table>
    @endif

    <div class="footer">
        Only confirmed payments and completed expenses contribute to financial totals.
        Pending, rejected, and reversed payments are reported separately and excluded from revenue.
    </div>
</body>
</html>
