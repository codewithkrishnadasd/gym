<div>
    <div class="grid gap-5 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-ui.card title="Price list"
                description="Everything that can be billed on an invoice — sessions, lockers, merchandise, fees. Membership plans are separate and live under Plans.">
                <x-slot:actions>
                    <x-ui.button size="sm" variant="primary" icon="plus" wire:click="startCreate">Add item</x-ui.button>
                </x-slot:actions>

                @if ($items->isEmpty())
                    <x-ui.empty icon="tag" title="Nothing on the price list yet"
                        description="Add the things you charge for outside a membership plan. Each becomes a line you can pick when raising an invoice." />
                @else
                    <ul class="divide-y divide-[var(--c-hairline)]">
                        @foreach ($items as $item)
                            <li class="flex items-center justify-between gap-3 py-2.5">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span @class(['truncate text-sm font-medium', 'text-ink' => $item->isActive(), 'text-ink-muted line-through' => ! $item->isActive()])>{{ $item->name }}</span>
                                        @unless ($item->isActive())
                                            <x-ui.badge :tone="$item->status->tone()">{{ $item->status->label() }}</x-ui.badge>
                                        @endunless
                                    </div>
                                    @if ($item->description)
                                        <p class="truncate text-xs text-ink-muted">{{ $item->description }}</p>
                                    @endif
                                </div>

                                <div class="flex shrink-0 items-center gap-3">
                                    <span class="numeric text-sm font-medium text-ink">{{ $organisation->money($item->unit_price_minor) }}</span>
                                    <div class="flex items-center gap-1">
                                        <x-ui.button size="sm" variant="ghost" icon="pencil-square" wire:click="startEdit({{ $item->id }})">Edit</x-ui.button>
                                        @if ($item->isActive())
                                            <x-ui.button size="sm" variant="ghost" icon="trash" wire:click="remove({{ $item->id }})"
                                                data-confirm-title="Remove this item?" data-confirm-action="Remove"
                                                data-confirm="“{{ $item->name }}” stops being offered on new invoices. Invoices already issued keep it.">Remove</x-ui.button>
                                        @else
                                            <x-ui.button size="sm" variant="ghost" icon="arrow-uturn-left" wire:click="restore({{ $item->id }})">Restore</x-ui.button>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card>
        </div>

        <div>
            <x-ui.card title="How invoices use this">
                <ul class="space-y-2.5 text-sm text-ink-soft">
                    <li class="flex gap-2">
                        <x-heroicon-o-document-text class="mt-0.5 h-4 w-4 shrink-0 text-ink-muted" />
                        Raising an invoice means picking items from this list and setting a quantity. A one-off line can
                        still be typed by hand.
                    </li>
                    <li class="flex gap-2">
                        <x-heroicon-o-lock-closed class="mt-0.5 h-4 w-4 shrink-0 text-ink-muted" />
                        Each invoice keeps its own copy of the name and price. Changing an item here never changes an
                        invoice already sent.
                    </li>
                    <li class="flex gap-2">
                        <x-heroicon-o-banknotes class="mt-0.5 h-4 w-4 shrink-0 text-ink-muted" />
                        Payments against an invoice go through the same confirmation queue and accounts as plan fees,
                        and can be made in parts.
                    </li>
                </ul>
            </x-ui.card>
        </div>
    </div>

    <x-ui.modal name="billable-item" :title="$editingId ? 'Edit item' : 'Add to the price list'">
        <div class="space-y-4">
            <x-ui.input wire:model="name" name="name" label="Name" required placeholder="e.g. Personal training session" />
            <x-ui.input wire:model="description" name="description" label="Description"
                placeholder="Optional — shown under the name on the invoice" />
            <x-ui.input wire:model="price" name="price" label="Price" required inputmode="decimal"
                :prefix="$organisation->currencySymbol()" placeholder="0.00" hint="Per unit. Quantity is set on each invoice." />
        </div>

        <x-slot:footer>
            <x-ui.button variant="ghost" x-on:click="$dispatch('close-modal', 'billable-item')">Cancel</x-ui.button>
            <x-ui.button variant="primary" wire:click="save" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">{{ $editingId ? 'Save item' : 'Add item' }}</span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Saving…</span>
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
