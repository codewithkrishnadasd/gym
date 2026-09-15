<div>
    <x-ui.page-header :title="$expense ? 'Edit expense' : 'Record expense'"
        :back="route('tenant.finance.expenses.index')" back-label="Expenses"
        description="Attach the receipt so the record can be reconciled later." />

    <form wire:submit="save" class="grid gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-ui.card title="Expense details">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.field label="Category" name="category" for="f-category" required
                        hint="Pick one of your organisation's categories, or type a one-off.">
                        <input list="expense-categories" id="f-category" wire:model="category"
                            class="min-h-[40px] w-full rounded-lg border border-hairline-strong bg-surface px-3 py-2 text-sm text-ink placeholder:text-ink-muted focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25 max-lg:min-h-[44px]"
                            placeholder="e.g. Equipment">
                        <datalist id="expense-categories">
                            @foreach ($categories as $option)
                                <option value="{{ $option }}"></option>
                            @endforeach
                        </datalist>
                    </x-ui.field>

                    <x-ui.input wire:model="amount" name="amount" label="Amount" required inputmode="decimal"
                        :prefix="$organisation->currencySymbol()" placeholder="0.00" />

                    <x-ui.input wire:model="expenseDate" name="expenseDate" label="Expense date" type="date" required />

                    @if ($organisation->usesClubs())
                        <x-ui.select wire:model="clubId" name="clubId" label="{{ $organisation->term('club_singular') }}"
                            hint="Leave blank for an organisation-wide expense.">
                            <option value="">Organisation-wide</option>
                            @foreach ($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </x-ui.select>
                    @endif

                    <x-ui.select wire:model="fundingAccountId" name="fundingAccountId" label="Paid from">
                        <option value="">Not specified</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.input wire:model="payee" name="payee" label="Payee" placeholder="Who was paid" />

                    <x-ui.textarea class="sm:col-span-2" wire:model="description" name="description" label="Description"
                        rows="3" placeholder="What this expense was for…">{{ $description }}</x-ui.textarea>
                </div>
            </x-ui.card>

            <x-ui.card title="Attribute to"
                description="Optionally tie this expense to a specific club, member, or staff member for reporting.">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.select wire:model.live="targetType" name="targetType" label="Target type">
                        <option value="">None</option>
                        @foreach ($targetTypes as $case)
                            <option value="{{ $case->value }}">{{ ucfirst($case->value) }}</option>
                        @endforeach
                    </x-ui.select>

                    @if ($targetType === 'club')
                        <x-ui.select wire:model="targetId" name="targetId" label="{{ $organisation->term('club_singular') }}">
                            <option value="">Select…</option>
                            @foreach ($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </x-ui.select>
                    @elseif ($targetType === 'member')
                        <x-ui.select wire:model="targetId" name="targetId" label="{{ $organisation->term('member_singular') }}">
                            <option value="">Select…</option>
                            @foreach ($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </x-ui.select>
                    @elseif ($targetType === 'user')
                        <x-ui.select wire:model="targetId" name="targetId" label="{{ $organisation->term('user_singular') }}">
                            <option value="">Select…</option>
                            @foreach ($staff as $person)
                                <option value="{{ $person->id }}">{{ $person->user?->name }}</option>
                            @endforeach
                        </x-ui.select>
                    @endif
                </div>
            </x-ui.card>
        </div>

        <div class="space-y-5">
            <x-ui.card title="Receipt">
                @if ($expense?->receipt_path && ! $receipt)
                    <div class="flex items-center justify-between gap-2 rounded-lg border border-hairline bg-raised p-3">
                        <a href="{{ route('tenant.finance.expenses.receipt', $expense) }}" target="_blank" data-download
                            data-confirm="Download the receipt attached to this expense?" data-confirm-title="Download file" data-confirm-action="Download" data-confirm-tone="accent"
                            class="inline-flex items-center gap-1.5 text-sm text-accent hover:underline">
                            <x-heroicon-o-paper-clip class="h-4 w-4" />
                            View current receipt
                        </a>
                        <x-ui.button size="sm" variant="ghost" type="button" wire:click="removeReceipt">Remove</x-ui.button>
                    </div>
                @endif

                <x-ui.field class="mt-3" label="Upload a receipt" name="receipt" for="f-receipt"
                    hint="JPG, PNG, WEBP, or PDF up to 5 MB.">
                    <input type="file" id="f-receipt" wire:model="receipt" accept=".jpg,.jpeg,.png,.webp,.pdf"
                        class="block w-full text-sm text-ink-soft file:mr-3 file:min-h-[40px] file:cursor-pointer file:rounded-lg file:border-0 file:bg-sunken file:px-3 file:py-2 file:text-sm file:font-medium file:text-ink hover:file:bg-hairline">
                </x-ui.field>

                <div wire:loading wire:target="receipt" class="mt-2 flex items-center gap-2 text-xs text-ink-muted">
                    <x-ui.spinner size="xs" /> Uploading…
                </div>
            </x-ui.card>

            <x-ui.card title="Save">
                <div class="flex flex-col gap-2">
                    <x-ui.button type="submit" variant="primary" size="lg" wire:loading.attr="disabled" wire:target="save,receipt">
                        <span wire:loading.remove wire:target="save">{{ $expense ? 'Save changes' : 'Record expense' }}</span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Saving…</span>
                    </x-ui.button>
                    <x-ui.button :href="route('tenant.finance.expenses.index')" wire:navigate variant="ghost">Cancel</x-ui.button>
                </div>
            </x-ui.card>
        </div>
    </form>
</div>
