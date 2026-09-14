{{-- Rendered by dompdf: plain CSS only, no external assets or web fonts. --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $organisation->reference('payment', $payment->id) }}</title>
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
        .logo { height: 44px; max-width: 160px; margin-bottom: 8px; display: block; }
        .footer { margin-top: 26px; padding-top: 12px; border-top: 1px solid #e5e7eb; font-size: 10px; color: #6b7280; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td>
                @if ($logo = $organisation->logoDataUri())
                    <img src="{{ $logo }}" alt="" class="logo">
                @endif
                <h1>{{ $organisation->name }}</h1>
                <div class="muted">
                    {{ collect([$organisation->contact_phone, $organisation->contact_email])->filter()->implode(' · ') }}
                </div>
            </td>
            <td class="right">
                <div style="font-size: 15px; font-weight: bold;">RECEIPT</div>
                <div class="muted">{{ $organisation->reference('payment', $payment->id) }}</div>
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
            <td class="label">Paid for</td>
            <td>
                @switch($payment->purpose->value)
                    @case('plan')
                        <strong>{{ $payment->subscription?->plan?->name ?? 'Plan fee' }}</strong>
                        @if ($payment->subscription)
                            <span class="muted">· {{ $payment->subscription->start_date->format('d M Y') }} – {{ $payment->subscription->end_date->format('d M Y') }}</span>
                        @endif
                        @break
                    @case('invoice')
                        <strong>Invoice {{ $payment->invoice?->number ?? '' }}</strong>
                        @if ($payment->invoice && $payment->invoice->lines->isNotEmpty())
                            <span class="muted">· {{ $payment->invoice->lines->map(fn ($line) => ($line->quantity > 1 ? $line->quantity.' × ' : '').$line->description)->implode(', ') }}</span>
                        @endif
                        @break
                    @case('admission')
                        <strong>Admission fee</strong> <span class="muted">· one-time joining fee</span>
                        @break
                    @default
                        <strong>Not linked to a plan, invoice or admission fee</strong>
                        <span class="muted">· held as credit for the {{ strtolower($organisation->term('member_singular')) }}</span>
                @endswitch
            </td>
        </tr>
        <tr>
            <td class="label">Payment date</td>
            <td>{{ $payment->payment_date->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="label">Payment method</td>
            {{-- The receiving account is internal bookkeeping; the member's copy
                 names only how they paid. --}}
            <td>{{ $payment->payment_method->label() }}</td>
        </tr>
        @if ($payment->transaction_reference)
            <tr><td class="label">Reference</td><td>{{ $payment->transaction_reference }}</td></tr>
        @endif
    </table>

    {{-- The headline is everything this payment put towards its purpose. When
         part of it was money paid earlier, the breakup below says how much was
         handed over now and how much came from that credit. --}}
    <div class="amount">
        <table>
            <tr>
                <td><div class="muted">Amount {{ $payment->isConfirmed() ? 'paid' : 'submitted' }} · {{ $payment->purposeLabel() }}</div></td>
                <td class="right"><span class="value">{{ $organisation->money($payment->settledMinor()) }}</span></td>
            </tr>
            @if ($payment->credit_applied_minor > 0)
                <tr>
                    <td><div class="muted">&nbsp;&nbsp;of which received now</div></td>
                    <td class="right">{{ $organisation->money($payment->amount_minor) }}</td>
                </tr>
                <tr>
                    <td><div class="muted">&nbsp;&nbsp;of which applied from earlier payment</div></td>
                    <td class="right">{{ $organisation->money($payment->credit_applied_minor) }}</td>
                </tr>
            @endif
            @if ($payment->discount_minor > 0)
                <tr>
                    <td><div class="muted">Discount given</div></td>
                    <td class="right">{{ $organisation->money($payment->discount_minor) }}</td>
                </tr>
                <tr>
                    <td><div class="muted">Total settled (paid + discount)</div></td>
                    <td class="right">{{ $organisation->money($payment->settledMinor() + $payment->discount_minor) }}</td>
                </tr>
            @endif
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
                <td class="right">{{ $organisation->money($subscription->outstandingMinor()) }}</td>
            </tr>
        </table>
    @endif

    @if ($payment->invoice && $payment->invoice->lines->isNotEmpty())
        @php $invoice = $payment->invoice; @endphp
        <table class="lines">
            <tr>
                <th>Invoice {{ $invoice->number }} — items</th>
                <th class="right">Qty</th>
                <th class="right">Unit</th>
                <th class="right">Line total</th>
            </tr>
            @foreach ($invoice->lines as $line)
                <tr>
                    <td>{{ $line->description }}</td>
                    <td class="right">{{ $line->quantity }}</td>
                    <td class="right">{{ $organisation->money($line->unit_price_minor) }}</td>
                    <td class="right">{{ $organisation->money($line->line_total_minor) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" class="right muted">Invoice total</td>
                <td class="right"><strong>{{ $organisation->money($invoice->total_minor) }}</strong></td>
            </tr>
            @if ($invoice->discount_minor > 0)
                <tr>
                    <td colspan="3" class="right muted">Discounts</td>
                    <td class="right">−{{ $organisation->money($invoice->discount_minor) }}</td>
                </tr>
            @endif
            <tr>
                <td colspan="3" class="right muted">Paid to date</td>
                <td class="right">{{ $organisation->money($invoice->paid_minor) }}</td>
            </tr>
            <tr>
                <td colspan="3" class="right muted">Balance</td>
                <td class="right"><strong>{{ $organisation->money($invoice->outstandingMinor()) }}</strong></td>
            </tr>
        </table>
    @endif

    @if ($payment->purpose->value === 'admission')
        <table class="lines">
            <tr>
                <th>Admission fee</th>
                <th class="right">Fee</th>
                <th class="right">Discounts</th>
                <th class="right">Paid</th>
                <th class="right">Balance</th>
            </tr>
            <tr>
                <td>One-time joining fee for {{ $payment->member->name }}</td>
                <td class="right">{{ $organisation->money($payment->member->admission_fee_minor) }}</td>
                <td class="right">{{ $organisation->money($payment->member->admission_discount_minor) }}</td>
                <td class="right">{{ $organisation->money($payment->member->admission_paid_minor) }}</td>
                <td class="right">{{ $organisation->money($payment->member->admissionOutstandingMinor()) }}</td>
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
