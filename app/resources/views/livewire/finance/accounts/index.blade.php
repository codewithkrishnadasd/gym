<div>
    <x-ui.flash />

    <x-ui.page-header title="Financial accounts"
        description="Where money is received and spent. Removing an account never changes historical transactions.">
    </x-ui.page-header>

    {{-- The page's one action, as the floating button every page shares. --}}
    @can('create', \App\Models\FinancialAccount::class)
        <x-ui.fab :href="route('tenant.finance.accounts.create')" label="New account" symbol="+" />
    @endcan

    <x-ui.card :padded="false">
        <x-ui.filters>
            <x-ui.filter-select wire:model.live="status" label="Status">
                <option value="">All except removed</option>
                @foreach ($statuses as $case)
                    <option value="{{ $case->value }}">{{ $case->label() }}</option>
                @endforeach
            </x-ui.filter-select>
        </x-ui.filters>

        <x-ui.list-loader />

        @if ($accounts->isEmpty())
        <x-ui.empty icon="credit-card" title="No accounts yet"
            description="Add the bank, UPI, or cash accounts you receive payments into.">
            <x-slot:actions>
                @can('create', \App\Models\FinancialAccount::class)
                    <x-ui.button variant="primary" icon="plus" :href="route('tenant.finance.accounts.create')" wire:navigate>
                        New account
                    </x-ui.button>
                @endcan
            </x-slot:actions>
        </x-ui.empty>
        @else
        <ul class="divide-y divide-[var(--c-hairline)]">
            @foreach ($accounts as $account)
                @php $balance = $balances[$account->id] ?? ['in' => 0, 'out' => 0]; @endphp

                <li class="flex flex-col gap-3 p-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex min-w-0 items-start gap-3">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-sunken text-ink-soft">
                            <x-dynamic-component
                                :component="'heroicon-o-'.match ($account->account_type->value) {
                                    'bank' => 'building-library',
                                    'upi' => 'qr-code',
                                    'cash' => 'banknotes',
                                    default => 'credit-card',
                                }" class="h-4.5 w-4.5" />
                        </span>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('tenant.finance.accounts.show', $account) }}" wire:navigate
                                    class="font-medium text-ink hover:text-accent">{{ $account->name }}</a>
                                <x-ui.badge :tone="$account->account_type->tone()" :dot="false">{{ $account->account_type->label() }}</x-ui.badge>
                                @if ($account->status->value !== 'active')
                                    <x-ui.badge :tone="$account->status->tone()">{{ $account->status->label() }}</x-ui.badge>
                                @endif
                            </div>
                            <p class="mt-0.5 truncate text-xs text-ink-muted">
                                {{ collect([
                                    $account->bank_name,
                                    $account->account_number_last4 ? '••••'.$account->account_number_last4 : null,
                                    $account->upi_id,
                                ])->filter()->implode(' · ') ?: 'No additional details' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center gap-5">
                        <div class="text-right">
                            <p class="numeric text-sm font-medium text-positive">+{{ $organisation->money($balance['in']) }}</p>
                            <p class="numeric text-xs text-ink-muted">−{{ $organisation->money($balance['out']) }}</p>
                        </div>

                        <div class="flex items-center gap-1">
                            <x-ui.button size="sm" variant="ghost" :href="route('tenant.finance.accounts.show', $account)" wire:navigate>View</x-ui.button>
                            @can('update', \App\Models\FinancialAccount::class)
                                <x-ui.button size="sm" variant="ghost" :href="route('tenant.finance.accounts.edit', $account)" wire:navigate>Edit</x-ui.button>
                            @endcan
                            @can('archive', \App\Models\FinancialAccount::class)
                                @if ($account->status->value === 'archived')
                                    <x-ui.button size="sm" variant="ghost" icon="arrow-uturn-left" wire:click="restore({{ $account->id }})">Restore</x-ui.button>
                                @else
                                    <x-ui.button size="sm" variant="ghost" icon="trash" wire:click="archive({{ $account->id }})"
                                        data-confirm-title="Remove this account?" data-confirm-action="Remove" data-confirm-tone="danger" data-confirm="Remove “{{ $account->name }}”? Historical transactions are unchanged.">Remove</x-ui.button>
                                @endif
                            @endcan
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        @endif
    </x-ui.card>
</div>
