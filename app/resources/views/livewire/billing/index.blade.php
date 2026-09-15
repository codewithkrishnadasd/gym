<div>
    <x-ui.flash />

    <x-ui.page-header title="Invoices"
        :description="'Bills raised against '.strtolower($organisation->term('member_plural')).' — or anyone else, by name — for anything outside a plan. Payments against them go through the usual confirmation.'">
        <x-slot:actions>
            @can('create', \App\Models\Invoice::class)
                <x-ui.button variant="primary" icon="plus" :href="route('tenant.billing.create')" wire:navigate>New invoice</x-ui.button>
            @endcan
        </x-slot:actions>
    </x-ui.page-header>

    <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-3">
        <x-ui.stat label="Outstanding" :value="$organisation->money($outstandingTotal)" icon="banknotes"
            :tone="$outstandingTotal > 0 ? 'caution' : 'positive'" hint="across open invoices" />
        <x-ui.stat label="Open invoices" :value="$openCount" icon="document-text" tone="neutral" hint="unpaid or partly paid" />
        <x-ui.stat label="Overdue" :value="$overdueCount" icon="exclamation-triangle"
            :tone="$overdueCount > 0 ? 'critical' : 'positive'" hint="past their due date" />
    </div>

    <x-ui.card :padded="false">
        <x-ui.filters search="search" placeholder="Search by number or name…">
            <x-ui.filter-select wire:model.live="status" label="Status">
                <option value="">All except void</option>
                @foreach ($statuses as $case)
                    <option value="{{ $case->value }}">{{ $case->label() }}</option>
                @endforeach
            </x-ui.filter-select>

            @if ($organisation->usesClubs())
                <x-ui.filter-select wire:model.live="club" :label="$organisation->term('club_singular')">
                    <option value="">All {{ strtolower($organisation->term('club_plural')) }}</option>
                    @foreach ($clubs as $clubOption)
                        <option value="{{ $clubOption->id }}">{{ $clubOption->name }}</option>
                    @endforeach
                </x-ui.filter-select>
            @endif
        </x-ui.filters>

        <x-ui.list-loader />

        @if ($invoices->isEmpty())
            <x-ui.empty icon="document-text"
                :title="$search !== '' || $status !== '' || $club !== '' ? 'Nothing matches those filters' : 'No invoices yet'"
                :description="$search !== '' || $status !== '' || $club !== '' ? 'Try a different search or clear the filters.' : 'Raise one for a session pack, a locker, merchandise — anything outside a membership plan.'">
                <x-slot:actions>
                    @can('create', \App\Models\Invoice::class)
                        <x-ui.button variant="primary" icon="plus" :href="route('tenant.billing.create')" wire:navigate>New invoice</x-ui.button>
                    @endcan
                </x-slot:actions>
            </x-ui.empty>
        @else
            <x-ui.table class="hidden lg:block">
                <x-slot:head>
                    <x-ui.th>Invoice</x-ui.th>
                    <x-ui.th>{{ $organisation->term('member_singular') }}</x-ui.th>
                    <x-ui.th>Due</x-ui.th>
                    <x-ui.th align="right">Total</x-ui.th>
                    <x-ui.th align="right">Outstanding</x-ui.th>
                    <x-ui.th>Status</x-ui.th>
                    <x-ui.th align="right"></x-ui.th>
                </x-slot:head>

                @foreach ($invoices as $invoice)
                    <tr class="transition hover:bg-raised">
                        <x-ui.td>
                            <a href="{{ route('tenant.billing.show', $invoice) }}" wire:navigate class="font-mono text-sm font-medium text-ink hover:text-accent">{{ $invoice->number }}</a>
                            <p class="text-xs text-ink-muted">{{ collect([$invoice->issue_date->format('d M Y'), $organisation->usesClubs() ? $invoice->club?->name : null])->filter()->join(' · ') }}</p>
                        </x-ui.td>
                        <x-ui.td>
                            @if ($invoice->member)
                                <a href="{{ route('tenant.members.show', $invoice->member_id) }}" wire:navigate class="text-ink hover:text-accent">{{ $invoice->member->name }}</a>
                            @else
                                <span class="text-ink">{{ $invoice->billedToName() }}</span>
                                <span class="block text-xs text-ink-muted">not a {{ strtolower($organisation->term('member_singular')) }}</span>
                            @endif
                        </x-ui.td>
                        <x-ui.td numeric class="{{ $invoice->isOverdue() ? 'font-medium text-critical' : 'text-ink-soft' }}">
                            {{ $invoice->due_date?->format('d M Y') ?? 'On receipt' }}
                        </x-ui.td>
                        <x-ui.td align="right" numeric>{{ $organisation->money($invoice->total_minor) }}</x-ui.td>
                        <x-ui.td align="right" numeric class="{{ $invoice->outstandingMinor() > 0 ? 'font-medium text-caution' : 'text-ink-muted' }}">
                            {{ $invoice->isOpen() ? $organisation->money($invoice->outstandingMinor()) : '—' }}
                        </x-ui.td>
                        <x-ui.td><x-ui.badge :tone="$invoice->status->tone()">{{ $invoice->status->label() }}</x-ui.badge></x-ui.td>
                        <x-ui.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button size="sm" variant="ghost" :href="route('tenant.billing.show', $invoice)" wire:navigate>View</x-ui.button>
                                @if ($invoice->isOpen())
                                    @can('create', \App\Models\FeePayment::class)
                                        <x-ui.button size="sm" variant="ghost" icon="banknotes"
                                            :href="route('tenant.finance.payments.create', ['member' => $invoice->member_id, 'invoice' => $invoice->id])" wire:navigate>Collect</x-ui.button>
                                    @endcan
                                @endif
                            </div>
                        </x-ui.td>
                    </tr>
                @endforeach
            </x-ui.table>

            <ul class="divide-y divide-[var(--c-hairline)] lg:hidden">
                @foreach ($invoices as $invoice)
                    <li>
                        <a href="{{ route('tenant.billing.show', $invoice) }}" wire:navigate class="flex items-center gap-3 p-4 transition hover:bg-raised">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="font-mono text-sm font-medium text-ink">{{ $invoice->number }}</span>
                                    <x-ui.badge :tone="$invoice->status->tone()">{{ $invoice->status->label() }}</x-ui.badge>
                                </div>
                                <p class="mt-0.5 truncate text-sm text-ink-soft">{{ $invoice->billedToName() }}</p>
                                <p class="numeric text-xs {{ $invoice->isOverdue() ? 'text-critical' : 'text-ink-muted' }}">
                                    Due {{ $invoice->due_date?->format('d M Y') ?? 'on receipt' }}
                                </p>
                            </div>
                            <div class="shrink-0 text-right">
                                <p class="numeric text-sm font-semibold text-ink">{{ $organisation->money($invoice->total_minor) }}</p>
                                @if ($invoice->isOpen() && $invoice->outstandingMinor() < $invoice->total_minor)
                                    <p class="numeric text-xs text-caution">{{ $organisation->money($invoice->outstandingMinor()) }} due</p>
                                @endif
                            </div>
                            <x-heroicon-o-chevron-right class="h-4 w-4 shrink-0 text-ink-muted" />
                        </a>
                    </li>
                @endforeach
            </ul>

            {{ $invoices->links() }}
        @endif
    </x-ui.card>
</div>
