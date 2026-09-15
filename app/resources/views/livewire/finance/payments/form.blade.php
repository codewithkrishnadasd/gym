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
                                    {{ $selectedMember->phone }}@if ($organisation->usesClubs()) &middot; {{ $selectedMember->primaryClub?->name ?? 'No club' }}@endif
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
                                                {{ $result->phone }}@if ($organisation->usesClubs()) &middot; {{ $result->primaryClub?->name ?? 'No club' }}@endif
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
                    {{-- One choice for what the money settles. Admission is
                         offered only while it is owed; each open invoice and
                         recent plan term is listed with its balance. --}}
                    <x-ui.select class="sm:col-span-2" wire:model.live="target" name="target" label="This payment is for"
                        hint="The amount defaults to what is still owed. A smaller amount records a part payment; a discount writes some of it off.">
                        @if ($admissionOutstanding > 0)
                            <option value="admission">Admission fee — {{ $organisation->money($admissionOutstanding) }} outstanding</option>
                        @endif
                        @foreach ($openInvoices as $openInvoice)
                            <option value="invoice:{{ $openInvoice->id }}">
                                Invoice {{ $openInvoice->number }} — {{ $organisation->money($openInvoice->outstandingMinor()) }} outstanding
                                of {{ $organisation->money($openInvoice->total_minor) }}{{ $openInvoice->due_date ? ', due '.$openInvoice->due_date->format('d M') : '' }}
                            </option>
                        @endforeach
                        @foreach ($subscriptions as $subscription)
                            <option value="plan:{{ $subscription->id }}">
                                Plan: {{ $subscription->plan->name }} —
                                {{ $subscription->start_date->format('d M Y') }} to {{ $subscription->end_date->format('d M Y') }}
                                ({{ $organisation->money($subscription->outstandingMinor()) }} outstanding)
                            </option>
                        @endforeach
                        <option value="other">Not linked to anything</option>
                    </x-ui.select>

                    <x-ui.input wire:model.live.debounce.400ms="amount" name="amount" label="Amount" required inputmode="decimal"
                        :prefix="$organisation->currencySymbol()" placeholder="0.00"
                        :hint="$useCredit ? 'Being paid in total. Unlinked money covers part of it — see below for what to collect now.' : null" />

                    <x-ui.input wire:model="discount" name="discount" label="Discount" inputmode="decimal"
                        :prefix="$organisation->currencySymbol()" placeholder="0.00"
                        :hint="$targetOutstanding !== null
                            ? 'Optional. Written off what is owed; amount and discount together may not exceed '.$organisation->money($targetOutstanding).'.'
                            : 'Optional. Needs a plan, invoice, or admission fee to come off.'" />

                    @if ($availableCredit > 0 && $target !== 'other')
                        {{-- Money this member paid earlier without saying what for. It
                             is not a discount: it was received, just never linked. --}}
                        <div class="sm:col-span-2 rounded-lg border border-info/25 bg-info-soft p-3">
                            <x-ui.checkbox wire:model.live="useCredit"
                                :label="'Use money already paid without a link — '.$organisation->money($availableCredit).' available'"
                                description="Covers as much of the amount as it can; only the rest is collected now. It counts as paid, not as a discount, and is not counted as revenue again." />

                            @if ($useCredit)
                                <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                    <x-ui.input wire:model.live.debounce.400ms="creditAmount" name="creditAmount" label="Amount to apply" inputmode="decimal"
                                        :prefix="$organisation->currencySymbol()" placeholder="0.00"
                                        :hint="'Up to '.$organisation->money($availableCredit).', and never more than the amount.'" />

                                    {{-- The figure the desk actually needs: what to take from the member. --}}
                                    <div class="rounded-lg border border-hairline bg-surface px-3 py-2">
                                        <p class="text-[13px] font-medium text-ink-soft">To collect now</p>
                                        <p class="numeric mt-1 font-[family-name:var(--font-display)] text-xl font-semibold text-ink">{{ $organisation->money($receivedNow) }}</p>
                                        <p class="text-xs text-ink-muted">amount − unlinked money applied</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <x-ui.input wire:model="paymentDate" name="paymentDate" label="Payment date" type="date" required />

                    <x-ui.select wire:model="paymentMethod" name="paymentMethod" label="Payment method" required>
                        @foreach ($methods as $case)
                            <option value="{{ $case->value }}">{{ $case->label() }}</option>
                        @endforeach
                    </x-ui.select>

                    @if ($accounts->isNotEmpty())
                        <x-ui.select wire:model="financialAccountId" name="financialAccountId" label="Received into" required>
                            <option value="">Choose an account…</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->account_type->label() }})</option>
                            @endforeach
                        </x-ui.select>
                    @else
                        {{-- Every payment must name a receiving account, so there is
                             nothing useful this form can do until one exists. --}}
                        <div class="sm:col-span-2">
                            <x-ui.alert tone="caution" title="No account to receive this payment">
                                Every payment has to name the account the money went into. Ask an admin to add a
                                bank, UPI, or cash account under Finance → Accounts first.
                            </x-ui.alert>
                        </div>
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
                    {{-- Disabled with no account to receive into: submitting could
                         only ever fail validation. --}}
                    <x-ui.button type="submit" variant="primary" size="lg" wire:loading.attr="disabled" wire:target="save"
                        :disabled="$accounts->isEmpty()">
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
