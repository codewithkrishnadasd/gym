<div>
    <x-ui.flash />

    <x-ui.page-header :title="$invoice->number" :back="route('tenant.billing.index')" back-label="Invoices"
        :description="'Issued '.$invoice->issue_date->format('d M Y').' · '.$invoice->club?->name">
        <x-slot:actions>
            <x-ui.download-button icon="arrow-down-tray" :what="'invoice '.$invoice->number.' as a PDF'" :href="route('tenant.billing.pdf', $invoice)">PDF</x-ui.download-button>

            @if ($invoice->isOpen())
                @can('create', \App\Models\FeePayment::class)
                    <x-ui.button variant="primary" icon="banknotes"
                        :href="route('tenant.finance.payments.create', ['member' => $invoice->member_id, 'invoice' => $invoice->id])" wire:navigate>
                        Collect payment
                    </x-ui.button>
                @endcan
            @endif

            @can('void', $invoice)
                <x-ui.button variant="danger" icon="x-circle" wire:click="startVoid">Void</x-ui.button>
            @endcan
        </x-slot:actions>
    </x-ui.page-header>

    @if ($lifecycleError)
        <div class="mb-4"><x-ui.alert tone="critical" title="Cannot void this invoice">{{ $lifecycleError }}</x-ui.alert></div>
    @endif

    @if ($canNotify)
        <div class="mb-4">
            <livewire:notifications.action-panel :notification-id="$notificationId" :key="'invoice-panel-'.$invoice->id" />
        </div>
    @endif

    <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
        <x-ui.stat label="Status" :value="$invoice->status->label()" :tone="$invoice->status->tone()" icon="document-text" />
        <x-ui.stat label="Total" :value="$organisation->money($invoice->total_minor)" icon="calculator" tone="neutral" />
        <x-ui.stat label="Paid" :value="$organisation->money($invoice->paid_minor)" icon="check-circle"
            :tone="$invoice->paid_minor > 0 ? 'positive' : 'neutral'" hint="confirmed payments only" />
        <x-ui.stat label="Outstanding" :value="$organisation->money($invoice->isOpen() ? $invoice->outstandingMinor() : 0)" icon="exclamation-circle"
            :tone="$invoice->isOpen() && $invoice->outstandingMinor() > 0 ? ($invoice->isOverdue() ? 'critical' : 'caution') : 'positive'"
            :hint="$invoice->due_date ? ($invoice->isOverdue() ? 'overdue since '.$invoice->due_date->format('d M') : 'due '.$invoice->due_date->format('d M Y')) : 'due on receipt'" />
    </div>

    <div class="grid gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-ui.card title="Lines" :padded="false">
                <x-ui.table>
                    <x-slot:head>
                        <x-ui.th>Description</x-ui.th>
                        <x-ui.th align="right">Qty</x-ui.th>
                        <x-ui.th align="right">Unit</x-ui.th>
                        <x-ui.th align="right">Amount</x-ui.th>
                    </x-slot:head>
                    @foreach ($invoice->lines as $line)
                        <tr>
                            <x-ui.td class="text-ink">{{ $line->description }}</x-ui.td>
                            <x-ui.td align="right" numeric>{{ $line->quantity }}</x-ui.td>
                            <x-ui.td align="right" numeric>{{ $organisation->money($line->unit_price_minor) }}</x-ui.td>
                            <x-ui.td align="right" numeric class="font-medium text-ink">{{ $organisation->money($line->line_total_minor) }}</x-ui.td>
                        </tr>
                    @endforeach
                    <tr class="bg-raised">
                        <x-ui.td class="font-medium text-ink" colspan="3">Total</x-ui.td>
                        <x-ui.td align="right" numeric class="font-[family-name:var(--font-display)] text-base font-semibold text-ink">{{ $organisation->money($invoice->total_minor) }}</x-ui.td>
                    </tr>
                    @if ($invoice->discount_minor > 0)
                        <tr>
                            <x-ui.td class="text-ink-soft" colspan="3">Discount given at payment</x-ui.td>
                            <x-ui.td align="right" numeric class="text-ink-soft">−{{ $organisation->money($invoice->discount_minor) }}</x-ui.td>
                        </tr>
                    @endif
                </x-ui.table>

                @if ($invoice->notes)
                    <p class="border-t border-hairline px-4 py-3 text-sm text-ink-soft">{{ $invoice->notes }}</p>
                @endif
            </x-ui.card>

            @unless ($invoice->status === \App\Enums\InvoiceStatus::Void)
                <x-ui.card title="Share with the member" description="Anyone with this link can view and download the invoice — no sign-in needed. The WhatsApp message includes it.">
                    <x-ui.share-link :url="$invoice->publicUrl()" label="Invoice link" />
                </x-ui.card>
            @endunless

            <x-ui.card title="Payments" :padded="false"
                description="Only confirmed payments reduce the balance. A pending one is shown but does not count yet.">
                @if ($invoice->payments->isEmpty())
                    <p class="px-4 py-6 text-center text-sm text-ink-muted">Nothing paid against this invoice yet.</p>
                @else
                    <ul class="divide-y divide-[var(--c-hairline)]">
                        @foreach ($invoice->payments as $payment)
                            <li class="flex items-center justify-between gap-3 px-4 py-3">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        @feature('payments')
                                            <a href="{{ route('tenant.finance.payments.show', $payment) }}" wire:navigate class="text-sm font-medium text-ink hover:text-accent">
                                                {{ $payment->payment_date->format('d M Y') }}
                                            </a>
                                        @else
                                            <span class="text-sm font-medium text-ink">{{ $payment->payment_date->format('d M Y') }}</span>
                                        @endfeature
                                        <x-ui.badge :tone="$payment->confirmation_status->tone()">{{ $payment->confirmation_status->label() }}</x-ui.badge>
                                    </div>
                                    <p class="truncate text-xs text-ink-muted">
                                        {{ $payment->payment_method->label() }}
                                        @if ($payment->financialAccount) · into {{ $payment->financialAccount->name }} @endif
                                        · by {{ $payment->collectedBy?->user?->name ?? '—' }}
                                    </p>
                                </div>
                                <span class="numeric shrink-0 text-right text-sm font-medium {{ $payment->confirmation_status->value === 'confirmed' ? 'text-positive' : 'text-ink-muted' }}">
                                    {{ $organisation->money($payment->settledMinor()) }}
                                    @if ($payment->credit_applied_minor > 0)
                                        <span class="block text-xs font-normal text-ink-muted">{{ $organisation->money($payment->amount_minor) }} now · {{ $organisation->money($payment->credit_applied_minor) }} earlier</span>
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card>
        </div>

        <div class="space-y-5">
            <x-ui.card title="Billed to">
                <div class="flex items-center gap-3">
                    <x-ui.avatar :name="$invoice->member?->name ?? '?'" tone="accent" />
                    <div class="min-w-0">
                        <a href="{{ route('tenant.members.show', ['member' => $invoice->member_id, 'tab' => 'billing']) }}" wire:navigate
                            class="block truncate text-sm font-medium text-ink hover:text-accent">{{ $invoice->member?->name }}</a>
                        <p class="numeric truncate text-xs text-ink-muted">{{ $invoice->member?->phone }}</p>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card title="History">
                <dl class="space-y-2 text-sm">
                    <x-ui.definition label="Issued by" :value="$invoice->createdBy?->user?->name ?? '—'" />
                    <x-ui.definition label="Issued on" :value="$invoice->issue_date->format('d M Y')" />
                    @if ($invoice->voided_at)
                        <x-ui.definition label="Voided by" :value="($invoice->voidedBy?->user?->name ?? '—').' · '.$invoice->voided_at->format('d M Y')" />
                        <x-ui.definition label="Reason" :value="$invoice->void_reason" />
                    @endif
                </dl>
            </x-ui.card>
        </div>
    </div>

    <x-ui.modal name="void-invoice" title="Void this invoice"
        description="It stays on record, marked void, so the numbering has no unexplained gap.">
        <x-ui.textarea wire:model="voidReason" name="voidReason" label="Reason" required rows="3"
            placeholder="e.g. Raised against the wrong member">{{ $voidReason }}</x-ui.textarea>

        <x-slot:footer>
            <x-ui.button variant="ghost" x-on:click="$dispatch('close-modal', 'void-invoice')">Cancel</x-ui.button>
            <x-ui.button variant="danger" wire:click="void" wire:loading.attr="disabled" wire:target="void">
                <span wire:loading.remove wire:target="void">Void invoice</span>
                <span wire:loading wire:target="void" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Voiding…</span>
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
