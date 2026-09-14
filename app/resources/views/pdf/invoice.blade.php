{{-- Rendered by dompdf: plain CSS only, no external assets or web fonts. --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->number }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; margin: 0; padding: 28px 32px; }
        h1 { font-size: 18px; margin: 0 0 2px; }
        .muted { color: #6b7280; }
        .header { border-bottom: 2px solid #111827; padding-bottom: 12px; margin-bottom: 18px; }
        .header td { vertical-align: top; }
        .right { text-align: right; }
        table { width: 100%; border-collapse: collapse; }
        .meta td { padding: 4px 0; vertical-align: top; }
        .meta .label { color: #6b7280; width: 110px; }
        .lines th { text-align: left; border-bottom: 1px solid #d1d5db; padding: 6px 0; color: #6b7280; font-size: 10px; text-transform: uppercase; }
        .lines td { padding: 8px 0; border-bottom: 1px solid #f3f4f6; }
        .totals td { padding: 5px 0; }
        .totals .grand td { border-top: 2px solid #111827; padding-top: 8px; font-size: 14px; font-weight: bold; }
        .status { display: inline-block; padding: 2px 7px; font-size: 10px; font-weight: bold; }
        .paid { background: #d1fae5; color: #065f46; }
        .open { background: #fef3c7; color: #92400e; }
        .void { background: #fee2e2; color: #991b1b; }
        .payments th { text-align: left; border-bottom: 1px solid #d1d5db; padding: 5px 0; color: #6b7280; font-size: 10px; text-transform: uppercase; }
        .payments td { padding: 5px 0; border-bottom: 1px solid #f3f4f6; }
        .footer { margin-top: 26px; padding-top: 12px; border-top: 1px solid #e5e7eb; font-size: 10px; color: #6b7280; }
        .notes { margin-top: 16px; padding: 10px 12px; background: #f9fafb; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td>
                <h1>{{ $organisation->name }}</h1>
                <div class="muted">
                    {{ collect([$organisation->contact_phone, $organisation->contact_email, $organisation->address['line1'] ?? null])->filter()->implode(' · ') }}
                </div>
            </td>
            <td class="right">
                <div style="font-size: 15px; font-weight: bold;">INVOICE</div>
                <div class="muted">{{ $invoice->number }}</div>
                @php
                    $class = match ($invoice->status->value) {
                        'paid' => 'paid',
                        'void' => 'void',
                        default => 'open',
                    };
                @endphp
                <div style="margin-top: 5px;"><span class="status {{ $class }}">{{ strtoupper($invoice->status->label()) }}</span></div>
            </td>
        </tr>
    </table>

    <table class="meta">
        <tr>
            <td class="label">Billed to</td>
            <td>
                <strong>{{ $invoice->member->name }}</strong>
                @if ($invoice->member->phone) <span class="muted">· {{ $invoice->member->phone }}</span> @endif
                <div class="muted">{{ $invoice->club->name }}</div>
            </td>
            <td class="label">Issued</td>
            <td>{{ $invoice->issue_date->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="label">Issued by</td>
            <td>{{ $invoice->createdBy?->user?->name ?? '—' }}</td>
            <td class="label">Due</td>
            <td><strong>{{ $invoice->due_date?->format('d M Y') ?? 'On receipt' }}</strong></td>
        </tr>
    </table>

    <table class="lines" style="margin-top: 18px;">
        <thead>
            <tr>
                <th>Description</th>
                <th class="right" style="width: 50px;">Qty</th>
                <th class="right" style="width: 100px;">Unit</th>
                <th class="right" style="width: 110px;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->lines as $line)
                <tr>
                    <td>{{ $line->description }}</td>
                    <td class="right">{{ $line->quantity }}</td>
                    <td class="right">{{ $organisation->money($line->unit_price_minor) }}</td>
                    <td class="right">{{ $organisation->money($line->line_total_minor) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals" style="margin-top: 10px; width: 45%; margin-left: 55%;">
        <tr class="grand">
            <td>Total</td>
            <td class="right">{{ $organisation->money($invoice->total_minor) }}</td>
        </tr>
        @if ($invoice->paid_minor > 0)
            <tr>
                <td class="muted">Paid</td>
                <td class="right">{{ $organisation->money($invoice->paid_minor) }}</td>
            </tr>
        @endif
        @if ($invoice->isOpen())
            <tr>
                <td><strong>Balance due</strong></td>
                <td class="right"><strong>{{ $organisation->money($invoice->outstandingMinor()) }}</strong></td>
            </tr>
        @endif
    </table>

    @if ($invoice->notes)
        <div class="notes">{{ $invoice->notes }}</div>
    @endif

    @php $confirmed = $invoice->payments->where('confirmation_status.value', 'confirmed'); @endphp
    @if ($confirmed->isNotEmpty())
        <table class="payments" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>Payments received</th>
                    <th>Method</th>
                    <th>Reference</th>
                    <th class="right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($confirmed as $payment)
                    <tr>
                        <td>{{ $payment->payment_date->format('d M Y') }}</td>
                        <td>{{ $payment->payment_method->label() }}</td>
                        <td>{{ $payment->transaction_reference ?: $organisation->reference('payment', $payment->id) }}</td>
                        <td class="right">{{ $organisation->money($payment->amount_minor) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        @if ($invoice->status->value === 'void')
            Voided {{ $invoice->voided_at?->format('d M Y') }} — {{ $invoice->void_reason }}.
        @else
            Generated {{ now($organisation->timezone)->format('d M Y H:i') }} · Only confirmed payments are shown as received.
        @endif
    </div>
</body>
</html>
