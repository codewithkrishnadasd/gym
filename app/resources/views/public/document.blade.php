<x-layouts.guest :eyebrow="$organisation->name" :heading="$title" max-width="max-w-3xl" :padded="false" :title="$title.' · '.$organisation->name">
    @php
        $amount = $kind === 'invoice' ? $invoice->total_minor : $payment->settledMinor();
        $status = $kind === 'invoice' ? $invoice->status : $payment->confirmation_status;
    @endphp

    {{-- Summary strip: the number, who it is for, and the figure that matters,
         readable before the PDF has loaded on a slow connection. --}}
    <div class="flex flex-col gap-3 border-b border-hairline p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
        <div class="min-w-0">
            <p class="text-xs font-medium uppercase tracking-wide text-ink-muted">{{ $kind === 'invoice' ? 'Invoice' : 'Receipt' }}</p>
            <p class="font-[family-name:var(--font-display)] text-lg font-semibold text-ink">
                {{ $kind === 'invoice' ? $invoice->number : $organisation->reference('payment', $payment->id) }}
            </p>
            <p class="text-sm text-ink-soft">
                {{ $kind === 'invoice' ? $invoice->billedToName() : $payment->payerName() }}
                @if ($kind === 'invoice')
                    · issued {{ $invoice->issue_date->format('d M Y') }}
                    @if ($invoice->due_date) · due {{ $invoice->due_date->format('d M Y') }} @endif
                @else
                    · {{ $payment->payment_date->format('d M Y') }} · {{ $payment->purposeLabel() }}
                    @if ($payment->credit_applied_minor > 0)
                        · {{ $organisation->money($payment->amount_minor) }} received now, {{ $organisation->money($payment->credit_applied_minor) }} from an earlier payment
                    @endif
                @endif
            </p>
        </div>

        <div class="flex items-center gap-4 sm:text-right">
            <div>
                <p class="numeric font-[family-name:var(--font-display)] text-2xl font-semibold text-ink">{{ $organisation->money($amount) }}</p>
                <p class="text-xs text-ink-muted">
                    @if ($kind === 'invoice' && $invoice->isOpen())
                        {{ $organisation->money($invoice->outstandingMinor()) }} outstanding
                    @else
                        {{ $status->label() }}
                    @endif
                </p>
            </div>
            <x-ui.badge :tone="$status->tone()">{{ $status->label() }}</x-ui.badge>
        </div>
    </div>

    <div class="bg-sunken">
        <iframe src="{{ $previewUrl }}" title="{{ $title }}" class="block h-[70vh] min-h-[480px] w-full border-0"></iframe>
    </div>

    <div class="flex flex-col gap-2 border-t border-hairline p-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-xs text-ink-muted">
            Issued by {{ $organisation->name }}{{ $organisation->contact_phone ? ' · '.$organisation->contact_phone : '' }}.
            Keep this link to view the document again.
        </p>
        <x-ui.button variant="primary" icon="arrow-down-tray" :href="$downloadUrl">Download PDF</x-ui.button>
    </div>
</x-layouts.guest>
