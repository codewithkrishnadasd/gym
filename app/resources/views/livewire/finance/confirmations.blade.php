<div>
    <x-ui.flash />

    <x-ui.page-header title="Confirmation queue"
        description="Staff-submitted payments awaiting your review. Nothing here counts as revenue yet." />

    @if ($lifecycleError)
        <div class="mb-4"><x-ui.alert tone="critical" title="This payment changed">{{ $lifecycleError }}</x-ui.alert></div>
    @endif

    <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-3">
        <x-ui.stat label="Awaiting confirmation" :value="$pendingCount" icon="clock" tone="caution" />
        <x-ui.stat label="Pending value" :value="$organisation->money($pendingTotal)" icon="banknotes" tone="caution" />
        <x-ui.stat label="Oldest submission" :value="$oldest ? \Illuminate\Support\Carbon::parse($oldest)->diffForHumans(null, true) : '—'"
            icon="exclamation-triangle" :tone="$oldest && \Illuminate\Support\Carbon::parse($oldest)->lt(now()->subDays(2)) ? 'critical' : 'neutral'"
            hint="submission age" />
    </div>

    @if ($lastConfirmedNotificationId)
        <div class="mb-4">
            <livewire:notifications.action-panel :notification-id="$lastConfirmedNotificationId"
                :key="'queue-panel-'.$lastConfirmedNotificationId" />
        </div>
    @endif

    <x-ui.card :padded="false">
        <x-ui.filters>
            <x-ui.filter-select wire:model.live="club" label="Club">
                <option value="">All {{ strtolower($organisation->term('club_plural')) }}</option>
                @foreach ($clubs as $clubOption)
                    <option value="{{ $clubOption->id }}">{{ $clubOption->name }}</option>
                @endforeach
            </x-ui.filter-select>

            <x-ui.filter-select wire:model.live="collector" label="Collected by">
                <option value="">All collectors</option>
                @foreach ($collectors as $person)
                    <option value="{{ $person->id }}">{{ $person->user?->name }}</option>
                @endforeach
            </x-ui.filter-select>
        </x-ui.filters>

        <div wire:loading.delay class="w-full"><x-ui.skeleton :rows="4" /></div>

        <div wire:loading.remove>
            @if ($payments->isEmpty())
                <x-ui.empty icon="check-badge" title="Nothing waiting"
                    description="Every submitted payment has been reviewed.">
                    <x-slot:actions>
                        <x-ui.button size="sm" :href="route('tenant.finance.payments.index')" wire:navigate>Open the ledger</x-ui.button>
                    </x-slot:actions>
                </x-ui.empty>
            @else
                <ul class="divide-y divide-[var(--c-hairline)]">
                    @foreach ($payments as $payment)
                        @php $ageDays = $payment->created_at?->diffInDays(now()) ?? 0; @endphp

                        <li class="flex flex-col gap-3 p-4 lg:flex-row lg:items-center lg:justify-between">
                            <div class="flex min-w-0 items-start gap-3">
                                <x-ui.avatar :name="$payment->member->name" tone="accent" />
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('tenant.finance.payments.show', $payment) }}" wire:navigate
                                            class="truncate font-medium text-ink hover:text-accent">{{ $payment->member->name }}</a>
                                        @if ($ageDays >= 2)
                                            <x-ui.badge tone="critical">{{ (int) $ageDays }}d waiting</x-ui.badge>
                                        @endif
                                    </div>
                                    <p class="mt-0.5 text-xs text-ink-muted">
                                        {{ $payment->club->name }}
                                        &middot; {{ $payment->payment_method->label() }}
                                        &middot; collected by {{ $payment->collectedBy?->user?->name ?? '—' }}
                                        &middot; {{ $payment->created_at?->diffForHumans() }}
                                    </p>
                                    @if ($payment->subscription)
                                        <p class="text-xs text-ink-muted">Plan: {{ $payment->subscription->plan->name }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center justify-between gap-3 lg:justify-end">
                                <p class="numeric font-[family-name:var(--font-display)] text-lg font-semibold">
                                    {{ $organisation->money($payment->amount_minor) }}
                                </p>

                                <div class="flex items-center gap-2">
                                    <x-ui.button size="sm" variant="danger" wire:click="startReject({{ $payment->id }})">Reject</x-ui.button>

                                    <x-ui.button size="sm" variant="primary" icon="check"
                                        wire:click="confirm({{ $payment->id }})"
                                        wire:loading.attr="disabled" wire:target="confirm({{ $payment->id }})">
                                        <span wire:loading.remove wire:target="confirm({{ $payment->id }})">Confirm</span>
                                        <span wire:loading wire:target="confirm({{ $payment->id }})"><x-ui.spinner size="xs" /></span>
                                    </x-ui.button>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                {{ $payments->links() }}
            @endif
        </div>
    </x-ui.card>

    <x-ui.modal name="reject-queued-payment" title="Reject this payment"
        description="The submission stays on record and remains visible to the collector.">
        <x-ui.textarea wire:model="rejectionReason" name="rejectionReason" label="Reason" required rows="3"
            placeholder="e.g. Amount does not match the receipt">{{ $rejectionReason }}</x-ui.textarea>

        <x-slot:footer>
            <x-ui.button variant="ghost" x-on:click="$dispatch('close-modal', 'reject-queued-payment')">Cancel</x-ui.button>
            <x-ui.button variant="danger" wire:click="reject" wire:loading.attr="disabled" wire:target="reject">
                <span wire:loading.remove wire:target="reject">Reject payment</span>
                <span wire:loading wire:target="reject" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Rejecting…</span>
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
