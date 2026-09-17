<div class="space-y-5">
    <x-ui.flash />

    <x-ui.page-header :title="'Payment '.$organisation->reference('payment', $payment->id)" :back="route('tenant.finance.payments.index')" back-label="Payments"
        :description="collect([$payment->payerName(), $organisation->usesClubs() ? $payment->club?->name : null])->filter()->join(' · ')">
        @if ($canNotify && $payment->member && $payment->member->phone)
            <x-slot:quick>
                <livewire:notifications.compose-menu recipient-type="member" :recipient-id="$payment->member_id" :key="'compose-'.$payment->id" />
            </x-slot:quick>
        @endif

        <x-slot:actions>
            @can('notify', $payment)
                <x-ui.download-button icon="document-arrow-down" what="the receipt for this payment as a PDF" :href="route('tenant.finance.payments.receipt', $payment)">Receipt PDF</x-ui.download-button>
            @endcan

            @can('confirm', $payment)
                <x-ui.button variant="danger" x-on:click="$dispatch('open-modal', 'reject-payment')">Reject</x-ui.button>
                <x-ui.button variant="primary" icon="check" wire:click="confirm" wire:loading.attr="disabled" wire:target="confirm">
                    <span wire:loading.remove wire:target="confirm">Confirm payment</span>
                    <span wire:loading wire:target="confirm" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Confirming…</span>
                </x-ui.button>
            @endcan

            @can('reverse', $payment)
                <x-ui.button variant="danger" icon="arrow-uturn-left" x-on:click="$dispatch('open-modal', 'reverse-payment')">
                    Reverse
                </x-ui.button>
            @endcan
        </x-slot:actions>
    </x-ui.page-header>

    @if ($lifecycleError)
        <x-ui.alert tone="critical" title="This payment changed">{{ $lifecycleError }}</x-ui.alert>
    @endif

    {{-- The receipt message, first thing on the page: after a collection or
         a confirmation it is the next action, on a phone as much as a desk. --}}
    @if ($canNotify)
        <livewire:notifications.action-panel :notification-id="$notificationId" :key="'panel-'.$payment->id" />
    @endif

    @if ($payment->confirmation_status->value === 'pending_admin_confirmation')
        <x-ui.alert tone="caution" title="Awaiting confirmation">
            This payment does not count towards revenue and has not been applied to any plan yet.
        </x-ui.alert>
    @elseif ($payment->confirmation_status->value === 'rejected')
        <x-ui.alert tone="critical" title="Rejected">{{ $payment->rejection_reason }}</x-ui.alert>
    @elseif ($payment->confirmation_status->value === 'reversed')
        <x-ui.alert tone="caution" title="Reversed">{{ $payment->reversal_reason }}</x-ui.alert>
    @endif

    <div class="grid gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-ui.card title="Payment">
                <div class="mb-4 flex flex-wrap items-baseline justify-between gap-2 border-b border-hairline pb-4">
                    <div>
                        {{-- Headline is everything paid towards the purpose; the breakup
                             says how much changed hands now and how much came from
                             earlier unlinked money. --}}
                        <p class="numeric font-[family-name:var(--font-display)] text-3xl font-semibold tracking-tight">
                            {{ $organisation->money($payment->settledMinor()) }}
                        </p>
                        @if ($payment->discount_minor > 0 || $payment->credit_applied_minor > 0)
                            <p class="numeric mt-0.5 text-sm text-ink-soft">
                                @if ($payment->credit_applied_minor > 0)
                                    {{ $organisation->money($payment->amount_minor) }} received now
                                    + {{ $organisation->money($payment->credit_applied_minor) }} from money paid earlier without a link
                                @endif
                                @if ($payment->discount_minor > 0)
                                    {{ $payment->credit_applied_minor > 0 ? '·' : '' }} {{ $organisation->money($payment->discount_minor) }} discount
                                    <span class="text-ink-muted">· {{ $organisation->money($payment->settledMinor() + $payment->discount_minor) }} settled in total</span>
                                @endif
                            </p>
                        @elseif ($payment->isUnlinked() && $payment->isConfirmed())
                            <p class="mt-0.5 text-sm text-ink-soft">Not linked to a plan, invoice or admission fee — sits as credit for this {{ strtolower($organisation->term('member_singular')) }} until applied.</p>
                        @endif
                    </div>
                    <x-ui.badge :tone="$payment->confirmation_status->tone()">{{ $payment->confirmation_status->label() }}</x-ui.badge>
                </div>

                <dl class="grid gap-x-6 sm:grid-cols-2">
                    <x-ui.definition label="Payment date" :value="$payment->payment_date->format('d M Y')" />
                    <x-ui.definition label="Method" :value="$payment->payment_method->label()" />
                    @if ($payment->member)
                        <x-ui.definition label="{{ $organisation->term('member_singular') }}">
                            <a href="{{ route('tenant.members.show', $payment->member) }}" wire:navigate class="text-accent hover:underline">
                                {{ $payment->member->name }}
                            </a>
                        </x-ui.definition>
                    @else
                        <x-ui.definition label="Paid by" :value="$payment->payer_name" />
                    @endif
                    @if ($organisation->usesClubs())
                        <x-ui.definition label="{{ $organisation->term('club_singular') }}" :value="$payment->club?->name ?? '—'" />
                    @endif
                    <x-ui.definition label="For" :value="$payment->purposeLabel()" />
                    <x-ui.definition label="Received into" :value="$payment->financialAccount?->name ?? 'Not specified'" />
                    @if ($payment->invoice)
                        <x-ui.definition label="Invoice">
                            @feature('billing')
                                <a href="{{ route('tenant.billing.show', $payment->invoice) }}" wire:navigate class="font-mono text-accent hover:underline">{{ $payment->invoice->number }}</a>
                            @else
                                <span class="font-mono">{{ $payment->invoice->number }}</span>
                            @endfeature
                            <span class="text-ink-muted">· {{ $payment->invoice->status->label() }}</span>
                        </x-ui.definition>
                    @endif
                    <x-ui.definition label="Reference" :value="$payment->transaction_reference ?: '—'" />
                    <x-ui.definition label="Collected by" :value="$payment->collectedBy?->user?->name ?? '—'" />

                    @if ($payment->confirmed_at)
                        <x-ui.definition label="{{ $payment->confirmation_status->value === 'rejected' ? 'Rejected by' : 'Confirmed by' }}"
                            :value="($payment->confirmedBy?->user?->name ?? '—').' · '.$payment->confirmed_at->format('d M Y H:i')" />
                    @endif

                    @if ($payment->notes)
                        <x-ui.definition class="sm:col-span-2" label="Notes" :value="$payment->notes" />
                    @endif
                </dl>
            </x-ui.card>

            @if ($payment->isConfirmed())
                <x-ui.card title="Share the receipt" description="Anyone with this link can view and download the receipt — no sign-in needed. The confirmation message includes it.">
                    <x-ui.share-link :url="$payment->publicUrl()" label="Receipt link" />
                </x-ui.card>
            @endif

            @if ($payment->subscription)
                @php
                    $subscription = $payment->subscription;
                    $outstanding = $subscription->outstandingMinor();
                @endphp

                <x-ui.card title="Plan balance" :description="$subscription->plan->name">
                    <dl class="grid gap-x-6 sm:grid-cols-3">
                        <x-ui.definition label="Due" :value="$organisation->money($subscription->amount_due_minor)" />
                        <x-ui.definition label="Paid" :value="$organisation->money($subscription->amount_paid_minor)" />
                        <x-ui.definition label="Outstanding">
                            <span class="{{ $outstanding > 0 ? 'text-caution' : 'text-positive' }} font-medium">
                                {{ $organisation->money($outstanding) }}
                            </span>
                        </x-ui.definition>
                    </dl>
                </x-ui.card>
            @endif

            <x-ui.card title="History" description="Immutable audit trail for this payment." :padded="false">
                <ol class="divide-y divide-[var(--c-hairline)]">
                    @forelse ($history as $event)
                        <li class="flex items-start gap-3 px-4 py-3">
                            <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-accent"></span>
                            <div class="min-w-0">
                                <p class="text-sm text-ink">
                                    {{ str_replace(['fee_payment.', '_'], ['', ' '], $event->action) }}
                                    <span class="text-ink-muted">by {{ $event->actor?->user?->name ?? 'system' }}</span>
                                </p>
                                <p class="numeric text-xs text-ink-muted">{{ $event->created_at?->format('d M Y H:i') }}</p>
                                @if (! empty($event->metadata['reason']))
                                    <p class="mt-0.5 text-xs text-ink-soft">Reason: {{ $event->metadata['reason'] }}</p>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="px-4 py-6 text-center text-sm text-ink-muted">No history recorded.</li>
                    @endforelse
                </ol>
            </x-ui.card>
        </div>

        <div class="space-y-5">
            <x-ui.card :title="$payment->member ? $organisation->term('member_singular') : 'Paid by'"
                :description="$payment->member ? null : 'Not a '.strtolower($organisation->term('member_singular')).' — recorded by name.'">
                <div class="flex items-center gap-3">
                    <x-ui.avatar :name="$payment->payerName()" size="lg" tone="accent" />
                    <div class="min-w-0">
                        <p class="truncate font-medium text-ink">{{ $payment->payerName() }}</p>
                        <p class="truncate text-sm text-ink-muted">{{ $payment->payerPhone() ?: 'No phone' }}</p>
                    </div>
                </div>

                @if ($payment->member)
                    <x-ui.button class="mt-3 w-full" :href="route('tenant.members.show', $payment->member)" wire:navigate>
                        Open profile
                    </x-ui.button>
                @endif
            </x-ui.card>
        </div>
    </div>

    {{-- Rejection requires a reason and preserves the submission (MEP 6.8). --}}
    @can('reject', $payment)
        <x-ui.modal name="reject-payment" title="Reject this payment"
            description="The submission stays on record and remains visible to the collector.">
            <x-ui.textarea wire:model="rejectionReason" name="rejectionReason" label="Reason" required rows="3"
                placeholder="e.g. Amount does not match the receipt">{{ $rejectionReason }}</x-ui.textarea>

            <x-slot:footer>
                <x-ui.button variant="ghost" x-on:click="$dispatch('close-modal', 'reject-payment')">Cancel</x-ui.button>
                <x-ui.button variant="danger" wire:click="reject" wire:loading.attr="disabled" wire:target="reject">
                    <span wire:loading.remove wire:target="reject">Reject payment</span>
                    <span wire:loading wire:target="reject" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Rejecting…</span>
                </x-ui.button>
            </x-slot:footer>
        </x-ui.modal>
    @endcan

    @can('reverse', $payment)
        <x-ui.modal name="reverse-payment" title="Reverse this payment"
            description="The confirmation stays in history; the amount is withdrawn from the plan balance and current totals.">
            <x-ui.textarea wire:model="reversalReason" name="reversalReason" label="Reason" required rows="3"
                placeholder="e.g. Duplicate entry — replaced by {{ $organisation->idPrefix('payment') }}-124">{{ $reversalReason }}</x-ui.textarea>

            <x-slot:footer>
                <x-ui.button variant="ghost" x-on:click="$dispatch('close-modal', 'reverse-payment')">Cancel</x-ui.button>
                <x-ui.button variant="danger" wire:click="reverse" wire:loading.attr="disabled" wire:target="reverse">
                    <span wire:loading.remove wire:target="reverse">Reverse payment</span>
                    <span wire:loading wire:target="reverse" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Reversing…</span>
                </x-ui.button>
            </x-slot:footer>
        </x-ui.modal>
    @endcan
</div>
