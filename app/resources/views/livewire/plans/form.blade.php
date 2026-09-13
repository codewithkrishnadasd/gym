<div>
    <x-ui.page-header :title="$plan ? 'Edit '.$plan->name : 'New plan'"
        :back="route('tenant.plans.index')" back-label="Plans"
        description="Duration determines the end date of every subscription sold on this plan." />

    <form wire:submit="save" class="grid gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-ui.card title="Plan details">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input class="sm:col-span-2" wire:model="name" name="name" label="Name" required
                        placeholder="e.g. Gold — 3 months" />

                    <x-ui.input wire:model="price" name="price" label="Price" required inputmode="decimal"
                        :prefix="$organisation->currencySymbol()" placeholder="0.00"
                        :hint="'Charged in '.$organisation->currency_code" />

                    <x-ui.input wire:model="durationDays" name="durationDays" label="Duration (days)" required
                        type="number" min="1" hint="A 1-month plan is usually 30 days." />

                    <x-ui.input wire:model="sessionLimit" name="sessionLimit" label="Session limit" type="number" min="1"
                        placeholder="Unlimited" hint="Leave blank for unlimited sessions." />

                    <x-ui.select wire:model="status" name="status" label="Status">
                        @foreach ($statuses as $case)
                            <option value="{{ $case->value }}">{{ ucfirst($case->value) }}</option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.textarea class="sm:col-span-2" wire:model="description" name="description" label="Description"
                        rows="3" placeholder="What this plan includes…">{{ $description }}</x-ui.textarea>
                </div>
            </x-ui.card>
        </div>

        <div class="space-y-5">
            <x-ui.card title="Save">
                <div class="flex flex-col gap-2">
                    <x-ui.button type="submit" variant="primary" size="lg" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">{{ $plan ? 'Save changes' : 'Create plan' }}</span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5">
                            <x-ui.spinner /> Saving…
                        </span>
                    </x-ui.button>

                    <x-ui.button :href="route('tenant.plans.index')" wire:navigate variant="ghost">Cancel</x-ui.button>
                </div>
            </x-ui.card>

            @if ($plan)
                <x-ui.alert tone="info" title="Editing an existing plan">
                    Price and duration changes apply to future subscriptions only. Terms already sold keep the amount
                    and dates they were sold with.
                </x-ui.alert>
            @endif
        </div>
    </form>
</div>
