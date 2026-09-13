<div>
    <x-ui.page-header :title="$account ? 'Edit '.$account->name : 'New financial account'"
        :back="route('tenant.finance.accounts.index')" back-label="Accounts"
        description="Only the last four digits of an account number are stored. Never enter full credentials, PINs, or passwords." />

    <form wire:submit="save" class="grid gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-ui.card title="Account details">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input wire:model="name" name="name" label="Display name" required placeholder="e.g. Main current account" />

                    <x-ui.select wire:model.live="accountType" name="accountType" label="Account type" required>
                        @foreach ($types as $case)
                            <option value="{{ $case->value }}">{{ $case->label() }}</option>
                        @endforeach
                    </x-ui.select>

                    @if (in_array($accountType, ['bank', 'other'], true))
                        <x-ui.input wire:model="bankName" name="bankName" label="Bank name" />
                        <x-ui.input wire:model="accountNumberLast4" name="accountNumberLast4" label="Account number (last 4)"
                            inputmode="numeric" maxlength="4" placeholder="1234"
                            hint="Used only to identify the account on screen." />
                    @endif

                    @if (in_array($accountType, ['upi', 'bank'], true))
                        <x-ui.input class="sm:col-span-2" wire:model="upiId" name="upiId" label="UPI ID"
                            placeholder="name@bank" :required="$accountType === 'upi'" />
                    @endif

                    <x-ui.select wire:model="status" name="status" label="Status">
                        @foreach ($statuses as $case)
                            <option value="{{ $case->value }}">{{ $case->label() }}</option>
                        @endforeach
                    </x-ui.select>
                </div>
            </x-ui.card>

            <x-ui.card title="Payment QR code"
                description="Paste the payment string from your bank or UPI app. A QR image is generated from it on the account page.">
                <x-ui.textarea wire:model="qrPayload" name="qrPayload" rows="3"
                    placeholder="upi://pay?pa=name@bank&pn=Your%20Gym">{{ $qrPayload }}</x-ui.textarea>
            </x-ui.card>
        </div>

        <div class="space-y-5">
            <x-ui.card title="Save">
                <div class="flex flex-col gap-2">
                    <x-ui.button type="submit" variant="primary" size="lg" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">{{ $account ? 'Save changes' : 'Create account' }}</span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Saving…</span>
                    </x-ui.button>
                    <x-ui.button :href="route('tenant.finance.accounts.index')" wire:navigate variant="ghost">Cancel</x-ui.button>
                </div>
            </x-ui.card>

            <x-ui.alert tone="caution" title="Keep this safe">
                Do not store full account numbers, IFSC/SWIFT credentials, card numbers, or any password here.
            </x-ui.alert>
        </div>
    </form>
</div>
