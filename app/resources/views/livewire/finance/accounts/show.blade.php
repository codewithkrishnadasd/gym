<div>
    <x-ui.flash />

    <x-ui.page-header :title="$account->name" :back="route('tenant.finance.accounts.index')" back-label="Accounts"
        :description="$account->account_type->label()">
        <x-slot:actions>
            @can('update', \App\Models\FinancialAccount::class)
                <x-ui.button icon="pencil-square" :href="route('tenant.finance.accounts.edit', $account)" wire:navigate>Edit</x-ui.button>
            @endcan
        </x-slot:actions>
    </x-ui.page-header>

    <div class="grid gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <div class="grid grid-cols-2 gap-3">
                <x-ui.stat label="Received" :value="$organisation->money($totalIn)" icon="arrow-down-left" tone="positive"
                    hint="confirmed payments" />
                <x-ui.stat label="Spent" :value="$organisation->money($totalOut)" icon="arrow-up-right" tone="critical"
                    hint="completed expenses" />
            </div>

            <x-ui.card title="Statement" description="Most recent 50 movements." :padded="false">
                @if ($statement->isEmpty())
                    <x-ui.empty icon="document-text" title="No movements yet"
                        description="Confirmed payments and completed expenses using this account will appear here." />
                @else
                    <x-ui.table>
                        <x-slot:head>
                            <x-ui.th>Date</x-ui.th>
                            <x-ui.th>Description</x-ui.th>
                            <x-ui.th align="right">Amount</x-ui.th>
                        </x-slot:head>

                        @foreach ($statement as $row)
                            <tr class="transition hover:bg-list-hover">
                                <x-ui.td numeric class="whitespace-nowrap">{{ $row->date->format('d M Y') }}</x-ui.td>
                                <x-ui.td>{{ $row->description }}</x-ui.td>
                                <x-ui.td align="right" numeric>
                                    <span class="font-medium {{ $row->inbound ? 'text-positive' : 'text-critical' }}">
                                        {{ $row->inbound ? '+' : '−' }}{{ $organisation->money($row->amountMinor) }}
                                    </span>
                                </x-ui.td>
                            </tr>
                        @endforeach
                    </x-ui.table>
                @endif
            </x-ui.card>
        </div>

        <div class="space-y-5">
            <x-ui.card title="Details">
                <dl>
                    <x-ui.definition label="Type" :value="$account->account_type->label()" />
                    @if ($account->bank_name)
                        <x-ui.definition label="Bank" :value="$account->bank_name" />
                    @endif
                    @if ($account->account_number_last4)
                        <x-ui.definition label="Account number" :value="'•••• •••• '.$account->account_number_last4" />
                    @endif
                    <x-ui.definition label="Status">
                        <x-ui.badge :tone="$account->status->tone()">{{ $account->status->label() }}</x-ui.badge>
                    </x-ui.definition>
                </dl>
            </x-ui.card>

            @if ($account->upi_id || $qrSvg)
                <x-ui.card title="Accept payment">
                    @if ($qrSvg)
                        {{-- Printed on a white ground so the code scans reliably in both themes. --}}
                        <div class="mx-auto w-fit rounded-xl border border-hairline bg-white p-3">
                            {!! $qrSvg !!}
                        </div>
                    @endif

                    <p class="mt-3 text-center text-sm font-medium text-ink">{{ $account->name }}</p>

                    @if ($account->upi_id)
                        <div class="mt-2" x-data="{ copied: false, upi: @js($account->upi_id) }">
                            <button type="button"
                                x-on:click="copied = await window.copyToClipboard(upi); setTimeout(() => copied = false, 2000)"
                                class="flex w-full min-h-[44px] items-center justify-between gap-2 rounded-lg border border-hairline bg-raised px-3 py-2 text-sm transition hover:bg-sunken">
                                <span class="truncate font-medium text-ink">{{ $account->upi_id }}</span>
                                <span class="shrink-0 text-xs text-ink-muted" x-show="! copied">Copy</span>
                                <span class="shrink-0 text-xs text-positive" x-show="copied" x-cloak>Copied</span>
                            </button>
                        </div>
                    @endif

                    @unless ($qrSvg)
                        <p class="mt-3 text-center text-xs text-ink-muted">
                            @if ($account->status->value !== 'active')
                                A QR code is only shown for active accounts.
                            @else
                                Add a QR payload to this account to display a scannable code.
                            @endif
                        </p>
                    @endunless

                    <p class="mt-3 text-center text-xs text-ink-muted">
                        Scan with any UPI app, or pay using the ID above and share the reference with reception.
                    </p>
                </x-ui.card>
            @endif
        </div>
    </div>
</div>
