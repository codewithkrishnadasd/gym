<div>
    <x-ui.page-header title="Collect fee" :back="route('tenant.finance.payments.index')" back-label="Payments"
        :description="$isAdmin ? 'Record a payment at the counter. You can confirm it immediately.' : 'Submitted payments are reviewed by an administrator before they count as revenue.'" />

    <form wire:submit="save" class="grid gap-5 lg:grid-cols-3">
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

                    @error('memberId')
                        <p class="mt-2 text-xs text-critical">{{ $message }}</p>
                    @enderror
                @else
                    <x-ui.field label="Search {{ strtolower($organisation->term('member_singular')) }}" name="memberId"
                        hint="Type at least 2 characters of a name or phone number.">
                        <div class="relative">
                            <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-muted" />
                            <input type="search" wire:model.live.debounce.300ms="memberSearch"
                                placeholder="Name or phone number…"
                                class="min-h-[44px] w-full rounded-lg border border-hairline-strong bg-surface py-2 pl-9 pr-3 text-sm text-ink placeholder:text-ink-muted focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25">
                            <div wire:loading wire:target="memberSearch" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-muted">
                                <x-ui.spinner size="xs" />
                            </div>
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
                                            <span class="block truncate text-xs text-ink-muted">
                                                {{ $result->phone }} &middot; {{ $result->primaryClub?->name ?? 'No club' }}
                                            </span>
                                        </span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @elseif (mb_strlen($memberSearch) >= 2)
                        <p class="mt-2 text-sm text-ink-muted">
                            No {{ strtolower($organisation->term('member_plural')) }} match “{{ $memberSearch }}”.
                        </p>
                    @endif
                @endif
            </x-ui.card>

            <x-ui.card title="Payment details">
                <div class="grid gap-4 sm:grid-cols-2">
                    @if ($subscriptions->isNotEmpty())
                        <x-ui.select class="sm:col-span-2" wire:model="subscriptionId" name="subscriptionId"
                            label="Apply to plan" hint="Confirmed payments are credited against the selected plan.">
                            <option value="">Not linked to a plan</option>
                            @foreach ($subscriptions as $subscription)
                                <option value="{{ $subscription->id }}">
                                    {{ $subscription->plan->name }} —
                                    {{ $subscription->start_date->format('d M Y') }} to {{ $subscription->end_date->format('d M Y') }}
                                    (outstanding {{ $organisation->money(max(0, $subscription->amount_due_minor - $subscription->amount_paid_minor)) }})
                                </option>
                            @endforeach
                        </x-ui.select>
                    @endif

                    <x-ui.input wire:model="amount" name="amount" label="Amount" required inputmode="decimal"
                        :prefix="$organisation->currencySymbol()" placeholder="0.00" />

                    <x-ui.input wire:model="paymentDate" name="paymentDate" label="Payment date" type="date" required />

                    <x-ui.select wire:model="paymentMethod" name="paymentMethod" label="Payment method" required>
                        @foreach ($methods as $case)
                            <option value="{{ $case->value }}">{{ $case->label() }}</option>
                        @endforeach
                    </x-ui.select>

                    @if ($accounts->isNotEmpty())
                        <x-ui.select wire:model="financialAccountId" name="financialAccountId" label="Received into">
                            <option value="">Not specified</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->account_type->label() }})</option>
                            @endforeach
                        </x-ui.select>
                    @endif

                    <x-ui.input class="sm:col-span-2" wire:model="transactionReference" name="transactionReference"
                        label="Transaction reference" placeholder="UPI reference, cheque number, receipt number…" />

                    <x-ui.textarea class="sm:col-span-2" wire:model="notes" name="notes" label="Notes" rows="2">{{ $notes }}</x-ui.textarea>
                </div>
            </x-ui.card>
        </div>

        <div class="space-y-5">
            <x-ui.card title="Submit">
                @if ($isAdmin)
                    <div class="mb-3 rounded-lg border border-hairline bg-raised p-1">
                        <x-ui.checkbox wire:model="confirmImmediately" label="Confirm immediately"
                            description="Applies the payment to the plan and generates the receipt message straight away." />
                    </div>
                @else
                    <div class="mb-3">
                        <x-ui.alert tone="info">
                            This will be submitted for admin confirmation. It does not count as revenue until confirmed.
                        </x-ui.alert>
                    </div>
                @endif

                <div class="flex flex-col gap-2">
                    <x-ui.button type="submit" variant="primary" size="lg" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">
                            {{ $isAdmin && $confirmImmediately ? 'Record and confirm' : 'Submit payment' }}
                        </span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5">
                            <x-ui.spinner /> Saving…
                        </span>
                    </x-ui.button>

                    <x-ui.button :href="route('tenant.finance.payments.index')" wire:navigate variant="ghost">Cancel</x-ui.button>
                </div>
            </x-ui.card>
        </div>
    </form>
</div>
