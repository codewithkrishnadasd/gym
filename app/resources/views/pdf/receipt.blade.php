{{-- Rendered by dompdf: plain CSS only, no external assets or web fonts. --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Receipt PMT-{{ $payment->id }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; margin: 0; padding: 28px 32px; }
        h1 { font-size: 18px; margin: 0 0 2px; }
        .muted { color: #6b7280; }
        .header { border-bottom: 2px solid #111827; padding-bottom: 12px; margin-bottom: 18px; }
        .header td { vertical-align: top; }
        .right { text-align: right; }
        table { width: 100%; border-collapse: collapse; }
        .meta td { padding: 4px 0; }
        .meta .label { color: #6b7280; width: 130px; }
        .amount { background: #f3f4f6; padding: 14px 16px; margin: 18px 0; }
        .amount .value { font-size: 22px; font-weight: bold; }
        .lines th { text-align: left; border-bottom: 1px solid #d1d5db; padding: 6px 0; color: #6b7280; font-size: 10px; text-transform: uppercase; }
        .lines td { padding: 7px 0; border-bottom: 1px solid #f3f4f6; }
        .status { display: inline-block; padding: 2px 7px; font-size: 10px; font-weight: bold; }
        .confirmed { background: #d1fae5; color: #065f46; }
        .pending { background: #fef3c7; color: #92400e; }
        .void { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 26px; padding-top: 12px; border-top: 1px solid #e5e7eb; font-size: 10px; color: #6b7280; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td>
                <h1>{{ $organisation->name }}</h1>
                <div class="muted">
                    {{ collect([$organisation->contact_phone, $organisation->contact_email])->filter()->implode(' · ') }}
                </div>
            </td>
            <td class="right">
                <div style="font-size: 15px; font-weight: bold;">RECEIPT</div>
                <div class="muted">PMT-{{ $payment->id }}</div>
                @php
                    $class = match ($payment->confirmation_status->value) {
                        'confirmed' => 'confirmed',
                        'pending_admin_confirmation' => 'pending',
                        default => 'void',
                    };
                @endphp
                <div style="margin-top: 5px;">
                    <span class="status {{ $class }}">{{ strtoupper($payment->confirmation_status->label()) }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="meta">
        <tr>
            <td class="label">{{ $organisation->term('member_singular') }}</td>
            <td><strong>{{ $payment->member->name }}</strong>
                @if ($payment->member->phone) <span class="muted">· {{ $payment->member->phone }}</span> @endif
            </td>
        </tr>
        <tr>
            <td class="label">{{ $organisation->term('club_singular') }}</td>
            <td>{{ $payment->club->name }}</td>
        </tr>
        <tr>
            <td class="label">Payment date</td>
            <td>{{ $payment->payment_date->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="label">Payment method</td>
            <td>{{ $payment->payment_method->label() }}@if ($payment->financialAccount) — {{ $payment->financialAccount->name }} @endif</td>
        </tr>
        @if ($payment->transaction_reference)
            <tr><td class="label">Reference</td><td>{{ $payment->transaction_reference }}</td></tr>
        @endif
    </table>

    <div class="amount">
        <table>
            <tr>
                <td><div class="muted">Amount {{ $payment->isConfirmed() ? 'received' : 'submitted' }}</div></td>
                <td class="right"><span class="value">{{ $organisation->money($payment->amount_minor) }}</span></td>
            </tr>
        </table>
    </div>

    @if ($payment->subscription)
        @php $subscription = $payment->subscription; @endphp
        <table class="lines">
            <tr>
                <th>Plan</th>
                <th>Valid</th>
                <th class="right">Due</th>
                <th class="right">Paid</th>
                <th class="right">Balance</th>
            </tr>
            <tr>
                <td>{{ $subscription->plan->name }}</td>
                <td>{{ $subscription->start_date->format('d M Y') }} – {{ $subscription->end_date->format('d M Y') }}</td>
                <td class="right">{{ $organisation->money($subscription->amount_due_minor) }}</td>
                <td class="right">{{ $organisation->money($subscription->amount_paid_minor) }}</td>
                <td class="right">{{ $organisation->money(max(0, $subscription->amount_due_minor - $subscription->amount_paid_minor)) }}</td>
            </tr>
        </table>
    @endif

    @if ($payment->notes)
        <p class="muted" style="margin-top: 14px;">Note: {{ $payment->notes }}</p>
    @endif

    <div class="footer">
        <table>
            <tr>
                <td>
                    Collected by {{ $payment->collectedBy?->user?->name ?? '—' }}
                    @if ($payment->confirmed_at)
                        <br>Confirmed by {{ $payment->confirmedBy?->user?->name ?? '—' }}
                        on {{ $payment->confirmed_at->timezone($organisation->timezone)->format('d M Y H:i') }}
                    @endif
                </td>
                <td class="right">
                    Generated {{ now($organisation->timezone)->format('d M Y H:i') }}<br>
                    {{ $organisation->timezone }}
                </td>
            </tr>
        </table>

        @unless ($payment->isConfirmed())
            <p style="margin-top: 10px; color: #92400e;">
                This payment has not been confirmed by an administrator and is not yet counted as received.
            </p>
        @endunless
    </div>
</body>
</html>
