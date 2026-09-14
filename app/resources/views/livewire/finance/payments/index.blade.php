<div>
    <x-ui.flash />

    <x-ui.page-header title="Payments"
        :description="$isAdmin ? 'Every fee collection in the organisation. Only confirmed payments count towards revenue.' : 'Fees you have collected and their confirmation status.'">
        <x-slot:actions>
            @can('viewAny', \App\Models\AuditEvent::class)
                <x-ui.download-button icon="arrow-down-tray" what="a CSV of the payments shown" note="It uses the filters currently applied."
                    :href="route('tenant.finance.payments.export', request()->query())">Export CSV</x-ui.download-button>
            @endcan
            @can('create', \App\Models\FeePayment::class)
                <x-ui.button variant="primary" icon="plus" :href="route('tenant.finance.payments.create')" wire:navigate>
                    Collect fee
                </x-ui.button>
            @endcan
        </x-slot:actions>
    </x-ui.page-header>

    <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
        <x-ui.stat label="Confirmed revenue" :value="$organisation->money($totals['confirmed'])" icon="banknotes" tone="positive"
            hint="in selected range" />
        <x-ui.stat label="Pending confirmation" :value="$organisation->money($totals['pending'])" icon="clock" tone="caution"
            hint="awaiting admin review" />
        <x-ui.stat label="Rejected" :value="$organisation->money($totals['rejected'])" icon="x-circle" tone="critical"
            hint="excluded from revenue" />
        <x-ui.stat label="Reversed" :value="$organisation->money($totals['reversed'])" icon="arrow-uturn-left" tone="neutral"
            hint="excluded from revenue" />
    </div>

    <x-ui.card :padded="false">
        <x-ui.filters search="search" placeholder="Search member, payer or reference…">
            <label class="flex items-center gap-1.5 text-xs text-ink-muted">
                <span class="sr-only sm:not-sr-only">From</span>
                <x-ui.date-input bare wire:model.live="from" aria-label="From date" class="w-40" />
            </label>

            <label class="flex items-center gap-1.5 text-xs text-ink-muted">
                <span class="sr-only sm:not-sr-only">To</span>
                <x-ui.date-input bare wire:model.live="to" aria-label="To date" class="w-40" />
            </label>

            <x-ui.filter-select wire:model.live="status" label="Status">
                <option value="">All statuses</option>
                @foreach ($statuses as $case)
                    <option value="{{ $case->value }}">{{ $case->label() }}</option>
                @endforeach
            </x-ui.filter-select>

            <x-ui.filter-select wire:model.live="club" label="Club">
                <option value="">All {{ strtolower($organisation->term('club_plural')) }}</option>
                @foreach ($clubs as $clubOption)
                    <option value="{{ $clubOption->id }}">{{ $clubOption->name }}</option>
                @endforeach
            </x-ui.filter-select>

            <x-ui.filter-select wire:model.live="method" label="Payment method">
                <option value="">All methods</option>
                @foreach ($methods as $case)
                    <option value="{{ $case->value }}">{{ $case->label() }}</option>
                @endforeach
            </x-ui.filter-select>

            @if ($isAdmin)
                <x-ui.filter-select wire:model.live="collector" label="Collected by">
                    <option value="">All collectors</option>
                    @foreach ($collectors as $person)
                        <option value="{{ $person->id }}">{{ $person->user?->name }}</option>
                    @endforeach
                </x-ui.filter-select>

                <x-ui.filter-select wire:model.live="account" label="Account">
                    <option value="">All accounts</option>
                    @foreach ($accounts as $accountOption)
                        <option value="{{ $accountOption->id }}">{{ $accountOption->name }}</option>
                    @endforeach
                </x-ui.filter-select>
            @endif

            <x-ui.button size="sm" variant="ghost" wire:click="clearFilters">Clear</x-ui.button>
        </x-ui.filters>

        <div wire:loading.delay class="w-full"><x-ui.skeleton :rows="6" /></div>

        <div wire:loading.remove>
            @if ($payments->isEmpty())
                <x-ui.empty icon="banknotes" title="No payments found"
                    description="Try widening the date range or clearing the filters.">
                    <x-slot:actions>
                        <x-ui.button size="sm" wire:click="clearFilters">Clear filters</x-ui.button>
                    </x-slot:actions>
                </x-ui.empty>
            @else
                <x-ui.table class="hidden lg:block">
                    <x-slot:head>
                        <x-ui.th>Date</x-ui.th>
                        <x-ui.th>{{ $organisation->term('member_singular') }}</x-ui.th>
                        <x-ui.th>{{ $organisation->term('club_singular') }}</x-ui.th>
                        <x-ui.th>Method</x-ui.th>
                        <x-ui.th>Collected by</x-ui.th>
                        <x-ui.th align="right">Amount</x-ui.th>
                        <x-ui.th>Status</x-ui.th>
                        <x-ui.th align="right"></x-ui.th>
                    </x-slot:head>

                    @foreach ($payments as $payment)
                        <tr class="transition hover:bg-raised">
                            <x-ui.td numeric class="whitespace-nowrap">{{ $payment->payment_date->format('d M Y') }}</x-ui.td>
                            <x-ui.td>
                                <p class="font-medium text-ink">{{ $payment->member->name }}
                                    <x-ui.reference :value="$organisation->reference('payment', $payment->id)" class="ml-1" /></p>
                                <p class="text-xs text-ink-muted">
                                    {{ $payment->purposeLabel() }}
                                    @if ($payment->discount_minor > 0)
                                        · {{ $organisation->money($payment->discount_minor) }} discount
                                    @endif
                                    @if ($payment->transaction_reference)
                                        · ref {{ $payment->transaction_reference }}
                                    @endif
                                    @if ($payment->invoice)
                                        ·
                                        <a href="{{ route('tenant.billing.show', $payment->invoice_id) }}" wire:navigate class="font-mono hover:text-accent">{{ $payment->invoice->number }}</a>
                                    @endif
                                </p>
                            </x-ui.td>
                            <x-ui.td>{{ $payment->club->name }}</x-ui.td>
                            <x-ui.td>
                                {{ $payment->payment_method->label() }}
                                @if ($payment->financialAccount)
                                    <span class="block text-xs text-ink-muted">{{ $payment->financialAccount->name }}</span>
                                @endif
                            </x-ui.td>
                            <x-ui.td>{{ $payment->collectedBy?->user?->name ?? '—' }}</x-ui.td>
                            <x-ui.td align="right" numeric class="font-medium text-ink">{{ $organisation->money($payment->amount_minor) }}</x-ui.td>
                            <x-ui.td>
                                <x-ui.badge :tone="$payment->confirmation_status->tone()">{{ $payment->confirmation_status->label() }}</x-ui.badge>
                            </x-ui.td>
                            <x-ui.td align="right">
                                <x-ui.button size="sm" variant="ghost" :href="route('tenant.finance.payments.show', $payment)" wire:navigate>
                                    View
                                </x-ui.button>
                            </x-ui.td>
                        </tr>
                    @endforeach
                </x-ui.table>

                <ul class="divide-y divide-[var(--c-hairline)] lg:hidden">
                    @foreach ($payments as $payment)
                        <li>
                            <a href="{{ route('tenant.finance.payments.show', $payment) }}" wire:navigate
                                class="block p-4 transition hover:bg-raised">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="truncate font-medium text-ink">{{ $payment->member->name }}
                                            <x-ui.reference :value="$organisation->reference('payment', $payment->id)" class="ml-1" /></p>
                                        <p class="numeric mt-0.5 text-xs text-ink-muted">
                                            {{ $payment->payment_date->format('d M Y') }} &middot;
                                            {{ $payment->club->name }} &middot; {{ $payment->payment_method->label() }}
                                        </p>
                                    </div>
                                    <p class="numeric shrink-0 font-medium text-ink">{{ $organisation->money($payment->amount_minor) }}</p>
                                </div>
                                <div class="mt-2">
                                    <x-ui.badge :tone="$payment->confirmation_status->tone()">{{ $payment->confirmation_status->label() }}</x-ui.badge>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{ $payments->links() }}
            @endif
        </div>
    </x-ui.card>
</div>
