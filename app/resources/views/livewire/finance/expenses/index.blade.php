<div>
    <x-ui.flash />

    <x-ui.page-header title="Expenses" description="Money going out, by club, category, target, and funding account.">
        <x-slot:actions>
            <x-ui.download-button icon="arrow-down-tray" what="a CSV of the expenses shown" note="It uses the filters currently applied." :href="route('tenant.finance.expenses.export', request()->query())">Export CSV</x-ui.download-button>
            @can('create', \App\Models\Expense::class)
                <x-ui.button variant="primary" icon="plus" :href="route('tenant.finance.expenses.create')" wire:navigate>
                    Record expense
                </x-ui.button>
            @endcan
        </x-slot:actions>
    </x-ui.page-header>

    @if ($lifecycleError)
        <div class="mb-4"><x-ui.alert tone="critical">{{ $lifecycleError }}</x-ui.alert></div>
    @endif

    <div class="mb-4 grid grid-cols-2 gap-3">
        <x-ui.stat label="Completed expenses" :value="$organisation->money($totalCompleted)" icon="receipt-percent" tone="critical"
            hint="in selected range" />
        <x-ui.stat label="Reversed" :value="$organisation->money($reversedTotal)" icon="arrow-uturn-left" tone="neutral"
            hint="excluded from totals" />
    </div>

    <x-ui.card :padded="false">
        <x-ui.filters search="search" placeholder="Search description or payee…">
            <label class="flex items-center gap-1.5 text-xs text-ink-muted">
                <span class="sr-only sm:not-sr-only">From</span>
                <x-ui.date-input bare wire:model.live="from" aria-label="From date" class="w-40" />
            </label>
            <label class="flex items-center gap-1.5 text-xs text-ink-muted">
                <span class="sr-only sm:not-sr-only">To</span>
                <x-ui.date-input bare wire:model.live="to" aria-label="To date" class="w-40" />
            </label>

            <x-ui.filter-select wire:model.live="category" label="Category">
                <option value="">All categories</option>
                @foreach ($categories as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </x-ui.filter-select>

            <x-ui.filter-select wire:model.live="status" label="Status">
                <option value="">Any status</option>
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

            <x-ui.filter-select wire:model.live="account" label="Account">
                <option value="">All accounts</option>
                @foreach ($accounts as $accountOption)
                    <option value="{{ $accountOption->id }}">{{ $accountOption->name }}</option>
                @endforeach
            </x-ui.filter-select>
        </x-ui.filters>

        <div wire:loading.delay class="w-full"><x-ui.skeleton :rows="5" /></div>

        <div wire:loading.remove>
            @if ($expenses->isEmpty())
                <x-ui.empty icon="receipt-percent" title="No expenses found"
                    description="Try a wider date range, or record your first expense.">
                    <x-slot:actions>
                        @can('create', \App\Models\Expense::class)
                            <x-ui.button size="sm" variant="primary" :href="route('tenant.finance.expenses.create')" wire:navigate>
                                Record expense
                            </x-ui.button>
                        @endcan
                    </x-slot:actions>
                </x-ui.empty>
            @else
                <x-ui.table class="hidden lg:block">
                    <x-slot:head>
                        <x-ui.th>Date</x-ui.th>
                        <x-ui.th>Category</x-ui.th>
                        <x-ui.th>Description</x-ui.th>
                        <x-ui.th>{{ $organisation->term('club_singular') }}</x-ui.th>
                        <x-ui.th>Paid from</x-ui.th>
                        <x-ui.th align="right">Amount</x-ui.th>
                        <x-ui.th>Status</x-ui.th>
                        <x-ui.th align="right"></x-ui.th>
                    </x-slot:head>

                    @foreach ($expenses as $expense)
                        <tr class="transition hover:bg-raised">
                            <x-ui.td numeric class="whitespace-nowrap">{{ $expense->expense_date->format('d M Y') }}</x-ui.td>
                            <x-ui.td class="text-ink">{{ $expense->category }}
                                <x-ui.reference :value="$organisation->reference('expense', $expense->id)" class="ml-1" /></x-ui.td>
                            <x-ui.td>
                                <p class="max-w-xs truncate">{{ $expense->description ?: '—' }}</p>
                                @if ($expense->payee)
                                    <p class="text-xs text-ink-muted">to {{ $expense->payee }}</p>
                                @endif
                            </x-ui.td>
                            <x-ui.td>{{ $expense->club?->name ?? 'Organisation-wide' }}</x-ui.td>
                            <x-ui.td>{{ $expense->fundingAccount?->name ?? '—' }}</x-ui.td>
                            <x-ui.td align="right" numeric class="font-medium text-ink">{{ $organisation->money($expense->amount_minor) }}</x-ui.td>
                            <x-ui.td><x-ui.badge :tone="$expense->status->tone()">{{ $expense->status->label() }}</x-ui.badge></x-ui.td>
                            <x-ui.td align="right">
                                <div class="flex items-center justify-end gap-1">
                                    @if ($expense->receipt_path)
                                        <x-ui.download-button size="sm" variant="ghost" what="the receipt attached to this expense" :href="route('tenant.finance.expenses.receipt', $expense)"
                                            target="_blank">Receipt</x-ui.download-button>
                                    @endif
                                    @can('update', $expense)
                                        <x-ui.button size="sm" variant="ghost" :href="route('tenant.finance.expenses.edit', $expense)" wire:navigate>Edit</x-ui.button>
                                    @endcan
                                    @can('reverse', $expense)
                                        <x-ui.button size="sm" variant="ghost" wire:click="startReverse({{ $expense->id }})">Reverse</x-ui.button>
                                    @endcan
                                </div>
                            </x-ui.td>
                        </tr>
                    @endforeach
                </x-ui.table>

                <ul class="divide-y divide-[var(--c-hairline)] lg:hidden">
                    @foreach ($expenses as $expense)
                        <li class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-medium text-ink">{{ $expense->category }}
                                        <x-ui.reference :value="$organisation->reference('expense', $expense->id)" class="ml-1" /></p>
                                    <p class="mt-0.5 truncate text-xs text-ink-muted">
                                        {{ $expense->expense_date->format('d M Y') }} &middot;
                                        {{ $expense->club?->name ?? 'Organisation-wide' }}
                                    </p>
                                    @if ($expense->description)
                                        <p class="mt-1 line-clamp-2 text-sm text-ink-soft">{{ $expense->description }}</p>
                                    @endif
                                </div>
                                <p class="numeric shrink-0 font-medium text-ink">{{ $organisation->money($expense->amount_minor) }}</p>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <x-ui.badge :tone="$expense->status->tone()">{{ $expense->status->label() }}</x-ui.badge>
                                @can('update', $expense)
                                    <x-ui.button size="sm" :href="route('tenant.finance.expenses.edit', $expense)" wire:navigate>Edit</x-ui.button>
                                @endcan
                                @can('reverse', $expense)
                                    <x-ui.button size="sm" variant="danger" wire:click="startReverse({{ $expense->id }})">Reverse</x-ui.button>
                                @endcan
                            </div>
                        </li>
                    @endforeach
                </ul>

                {{ $expenses->links() }}
            @endif
        </div>
    </x-ui.card>

    <x-ui.modal name="reverse-expense" title="Reverse this expense"
        description="The record stays in history and is excluded from current totals.">
        <x-ui.textarea wire:model="reversalReason" name="reversalReason" label="Reason" required rows="3"
            placeholder="e.g. Entered twice by mistake">{{ $reversalReason }}</x-ui.textarea>

        <x-slot:footer>
            <x-ui.button variant="ghost" x-on:click="$dispatch('close-modal', 'reverse-expense')">Cancel</x-ui.button>
            <x-ui.button variant="danger" wire:click="reverse" wire:loading.attr="disabled" wire:target="reverse">
                <span wire:loading.remove wire:target="reverse">Reverse expense</span>
                <span wire:loading wire:target="reverse" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Reversing…</span>
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
