<div>
    <x-ui.page-header title="New invoice" :back="route('tenant.billing.index')" back-label="Invoices"
        :description="'Bill a '.strtolower($organisation->term('member_singular')).' for anything outside their plan. They can pay in full or in parts.'" />

    <form wire:submit="issue" class="grid gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-ui.card :title="$organisation->term('member_singular')">
                @if ($selectedMember)
                    <div class="flex items-center justify-between gap-3 rounded-lg border border-hairline bg-raised p-3">
                        <div class="flex min-w-0 items-center gap-3">
                            <x-ui.avatar :name="$selectedMember->name" tone="accent" />
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-ink">{{ $selectedMember->name }}</p>
                                <p class="truncate text-xs text-ink-muted">
                                    {{ $selectedMember->phone }} &middot; {{ $selectedMember->primaryClub?->name ?? 'No club' }}
                                </p>
                            </div>
                        </div>
                        <x-ui.button size="sm" variant="ghost" wire:click="clearMember" type="button">Change</x-ui.button>
                    </div>

                    @error('memberId')<p class="mt-2 text-xs text-critical">{{ $message }}</p>@enderror
                @else
                    <x-ui.field label="Search {{ strtolower($organisation->term('member_singular')) }}" name="memberId"
                        hint="Type at least 2 characters of a name or phone number.">
                        <div class="relative">
                            <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-muted" />
                            <input type="search" wire:model.live.debounce.300ms="memberSearch" placeholder="Name or phone number…"
                                class="min-h-[44px] w-full rounded-lg border border-hairline-strong bg-surface py-2 pl-9 pr-3 text-sm text-ink placeholder:text-ink-muted focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25">
                            <div wire:loading wire:target="memberSearch" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-muted"><x-ui.spinner size="xs" /></div>
                        </div>
                    </x-ui.field>

                    @if ($results->isNotEmpty())
                        <ul class="mt-2 divide-y divide-[var(--c-hairline)] overflow-hidden rounded-lg border border-hairline">
                            @foreach ($results as $result)
                                <li>
                                    <button type="button" wire:click="selectMember({{ $result->id }})"
                                        class="flex w-full items-center gap-3 p-3 text-left transition hover:bg-raised">
                                        <x-ui.avatar :name="$result->name" size="sm" />
                                        <span class="min-w-0">
                                            <span class="block truncate text-sm font-medium text-ink">{{ $result->name }}</span>
                                            <span class="block truncate text-xs text-ink-muted">{{ $result->phone }} &middot; {{ $result->primaryClub?->name ?? 'No club' }}</span>
                                        </span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @elseif (mb_strlen($memberSearch) >= 2)
                        <p class="mt-2 text-sm text-ink-muted">No {{ strtolower($organisation->term('member_plural')) }} match “{{ $memberSearch }}”.</p>
                    @endif
                @endif
            </x-ui.card>

            <x-ui.card title="Lines" :padded="false">
                <div class="flex flex-col gap-2 border-b border-hairline p-4 sm:flex-row sm:items-end">
                    <div class="min-w-0 flex-1">
                        {{-- Picking an item adds it straight away; no second click. --}}
                        <x-ui.select wire:model.live="pickedItemId" name="pickedItemId" label="From the price list" :disabled="$catalogue->isEmpty()">
                            <option value="">Choose an item to add…</option>
                            @foreach ($catalogue as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} — {{ $organisation->money($item->unit_price_minor) }}</option>
                            @endforeach
                        </x-ui.select>
                    </div>
                    <div class="flex gap-2">
                        <x-ui.button type="button" icon="plus" wire:click="addCustomLine">Add custom</x-ui.button>
                    </div>
                </div>

                @if ($catalogue->isEmpty())
                    <p class="px-4 pt-3 text-xs text-ink-muted">
                        The price list is empty. Add items under
                        @can('manage', \App\Models\BillableItem::class)
                            <a href="{{ route('tenant.settings.organisation', ['tab' => 'billing']) }}" wire:navigate class="underline underline-offset-2">Settings → Billing</a>,
                        @else
                            Settings → Billing,
                        @endcan
                        or add a custom line.
                    </p>
                @endif

                @error('lines')<p class="px-4 pt-3 text-sm text-critical">{{ $message }}</p>@enderror

                @if ($lines === [])
                    <p class="px-4 py-8 text-center text-sm text-ink-muted">No lines yet.</p>
                @else
                    <ul class="divide-y divide-[var(--c-hairline)]">
                        @foreach ($lines as $index => $line)
                            <li class="grid gap-2 p-4 sm:grid-cols-[1fr_5rem_8rem_auto] sm:items-start" wire:key="line-{{ $index }}">
                                <div>
                                    <x-ui.input wire:model.live.debounce.300ms="lines.{{ $index }}.description" name="lines.{{ $index }}.description"
                                        label="Description" placeholder="What is being billed" required />
                                    @if ($line['billable_item_id'] !== null)
                                        <p class="mt-1 text-[11px] text-ink-muted">From the price list</p>
                                    @endif
                                </div>
                                <x-ui.input wire:model.live.debounce.300ms="lines.{{ $index }}.quantity" name="lines.{{ $index }}.quantity"
                                    label="Qty" type="number" min="1" max="999" inputmode="numeric" required />
                                <x-ui.input wire:model.live.debounce.300ms="lines.{{ $index }}.price" name="lines.{{ $index }}.price"
                                    label="Unit price" inputmode="decimal" :prefix="$organisation->currencySymbol()" required />
                                <div class="flex items-end sm:pt-6">
                                    <x-ui.button type="button" size="sm" variant="ghost" icon="trash" wire:click="removeLine({{ $index }})">
                                        <span class="sm:sr-only">Remove</span>
                                    </x-ui.button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="flex items-center justify-between border-t border-hairline bg-raised px-4 py-3">
                    <span class="text-sm text-ink-soft">Total</span>
                    <span class="numeric font-[family-name:var(--font-display)] text-lg font-semibold text-ink">{{ $organisation->money($total) }}</span>
                </div>
            </x-ui.card>

            <x-ui.card title="Terms">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input wire:model="dueDate" name="dueDate" label="Due date" type="date" hint="Leave blank for “due on receipt”." />
                    <x-ui.textarea class="sm:col-span-2" wire:model="notes" name="notes" label="Notes" rows="2"
                        placeholder="Printed on the invoice. Payment instructions, what was agreed, anything the member should see.">{{ $notes }}</x-ui.textarea>
                </div>
            </x-ui.card>
        </div>

        <div class="space-y-5">
            <x-ui.card title="Issue">
                <div class="flex flex-col gap-2">
                    <x-ui.button type="submit" variant="primary" size="lg" wire:loading.attr="disabled" wire:target="issue"
                        :disabled="$lines === [] || $selectedMember === null">
                        <span wire:loading.remove wire:target="issue">Issue invoice</span>
                        <span wire:loading wire:target="issue" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Issuing…</span>
                    </x-ui.button>
                    <x-ui.button :href="route('tenant.billing.index')" wire:navigate variant="ghost">Cancel</x-ui.button>
                </div>

                <p class="mt-3 text-xs text-ink-muted">
                    Lines cannot be edited once issued — a mistake is corrected by voiding and reissuing, so the numbering
                    stays honest. A WhatsApp message to the {{ strtolower($organisation->term('member_singular')) }} is
                    prepared on issue.
                </p>
            </x-ui.card>
        </div>
    </form>
</div>
