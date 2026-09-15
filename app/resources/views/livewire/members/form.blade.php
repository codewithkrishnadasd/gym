<div>
    <x-ui.page-header :title="$member ? 'Edit '.$member->name : 'New '.$organisation->term('member_singular')"
        :back="$member ? route('tenant.members.show', $member) : route('tenant.members.index')"
        :back-label="$member ? $member->name : $organisation->term('member_plural')" />

    <div class="grid gap-5 lg:grid-cols-3">
        <form wire:submit="save" class="space-y-5 lg:col-span-2">
            <x-ui.card title="Identity">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input class="sm:col-span-2" wire:model="name" name="name" label="Full name" required />
                    <x-ui.input wire:model="phone" name="phone" label="WhatsApp number" type="tel" required
                        inputmode="tel" placeholder="98765 43210"
                        hint="Required — receipts and reminders are sent here." />
                    <x-ui.input wire:model="dateOfBirth" name="dateOfBirth" label="Date of birth" type="date" />

                    <x-ui.select wire:model="gender" name="gender" label="Gender">
                        <option value="">Prefer not to say</option>
                        @foreach (['Female', 'Male', 'Other'] as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.input class="sm:col-span-2" wire:model="addressLine" name="addressLine" label="Address" />
                </div>
            </x-ui.card>

            <x-ui.card :title="'Membership'">
                <div class="grid gap-4 sm:grid-cols-2">
                    @if (! $organisation->usesClubs())
                        {{-- No Clubs module: members belong to the organisation as a whole. --}}
                    @elseif ($member)
                        {{-- Club is only changed through the transfer flow, so the
                             history stays accurate (MEP 5.7). --}}
                        <x-ui.field :label="$organisation->term('club_singular')"
                            :hint="'Use Transfer to move this '.strtolower($organisation->term('member_singular')).' to another '.strtolower($organisation->term('club_singular')).'.'">
                            <input type="text" disabled value="{{ $member->primaryClub?->name ?? 'Unassigned' }}"
                                class="min-h-[40px] w-full cursor-not-allowed rounded-lg border border-hairline-strong bg-sunken px-3 py-2 text-sm text-ink-muted">
                        </x-ui.field>
                    @else
                        <x-ui.select wire:model.live="primaryClubId" name="primaryClubId"
                            :label="$organisation->term('club_singular')" required>
                            <option value="">Select…</option>
                            @foreach ($availableClubs as $club)
                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </x-ui.select>
                    @endif

                    <x-ui.input wire:model.live="joinedAt" name="joinedAt" label="Joined on" type="date" required />

                    <x-ui.select wire:model="status" name="status" label="Status" required>
                        @foreach ($statuses as $case)
                            <option value="{{ $case->value }}">{{ $case->label() }}</option>
                        @endforeach
                    </x-ui.select>
                </div>
            </x-ui.card>

            @if ($canStartPlan)
                <x-ui.card title="Starting plan" description="Optional. Starts the plan the moment the record is saved; you can also do this later from their page.">
                    @if ($availablePlans->isEmpty())
                        <p class="text-sm text-ink-muted">No active plans yet. Add one under Plans to offer it here.</p>
                    @else
                        <div class="grid gap-4 sm:grid-cols-2">
                            <x-ui.select class="sm:col-span-2" wire:model.live="planId" name="planId" label="Plan">
                                <option value="">No plan for now</option>
                                @foreach ($availablePlans as $plan)
                                    <option value="{{ $plan->id }}">
                                        {{ $plan->name }} — {{ $organisation->money($plan->price_minor) }} / {{ $plan->duration_days }} days
                                    </option>
                                @endforeach
                            </x-ui.select>

                            @if ($planId)
                                <x-ui.input wire:model="planStartDate" name="planStartDate" label="Plan starts on" type="date"
                                    hint="Defaults to the joining date." />

                                <x-ui.input wire:model="planDiscount" name="planDiscount" label="Discount" inputmode="decimal"
                                    :prefix="$organisation->currencySymbol()" placeholder="0.00"
                                    :hint="$planDiscount !== '' ? 'Prefilled from the '.strtolower($organisation->term('club_singular')).'’s standing discount on this plan. Change or clear it as needed.' : 'Optional. Taken off the plan price for this term.'" />
                            @endif
                        </div>
                    @endif
                </x-ui.card>
            @endif

            <x-ui.card title="Emergency contact">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input wire:model="emergencyName" name="emergencyName" label="Contact name" />
                    <x-ui.input wire:model="emergencyPhone" name="emergencyPhone" label="Contact phone" />
                </div>
            </x-ui.card>

            <x-ui.card title="Notes">
                <x-ui.textarea wire:model="notes" name="notes" rows="4"
                    placeholder="Injuries, goals, preferences, follow-up context…">{{ $notes }}</x-ui.textarea>
            </x-ui.card>

            <div class="flex flex-wrap gap-2">
                <x-ui.button type="submit" variant="primary" size="lg" wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">
                        {{ $member ? 'Save changes' : 'Add '.strtolower($organisation->term('member_singular')) }}
                    </span>
                    <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Saving…</span>
                </x-ui.button>

                <x-ui.button size="lg" variant="ghost" wire:navigate
                    :href="$member ? route('tenant.members.show', $member) : route('tenant.members.index')">Cancel</x-ui.button>
            </div>
        </form>

        <div class="space-y-5">
            @if ($member && $organisation->usesClubs())
                @can('transfer', $member)
                    <x-ui.card :title="'Transfer '.strtolower($organisation->term('club_singular'))"
                        description="Moves this record to another location and writes a history entry. Past attendance and payments keep their original club.">
                        <form wire:submit="transfer" class="space-y-3">
                            <x-ui.select wire:model="transferClubId" name="transferClubId" label="Move to" required>
                                <option value="">Select…</option>
                                @foreach ($availableClubs as $club)
                                    @if ($club->id !== $primaryClubId)
                                        <option value="{{ $club->id }}">{{ $club->name }}</option>
                                    @endif
                                @endforeach
                            </x-ui.select>

                            <x-ui.textarea wire:model="transferReason" name="transferReason" label="Reason" rows="2"
                                placeholder="Optional">{{ $transferReason }}</x-ui.textarea>

                            <x-ui.button type="submit" class="w-full" wire:loading.attr="disabled" wire:target="transfer">
                                <span wire:loading.remove wire:target="transfer">Transfer</span>
                                <span wire:loading wire:target="transfer" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Transferring…</span>
                            </x-ui.button>
                        </form>
                    </x-ui.card>
                @endcan

                @if ($history->isNotEmpty() && $organisation->usesClubs())
                    <x-ui.card :padded="false" :title="$organisation->term('club_singular').' history'">
                        <ol class="divide-y divide-[var(--c-hairline)]">
                            @foreach ($history as $entry)
                                <li class="px-4 py-2.5">
                                    <p class="text-sm text-ink">
                                        {{ $entry->fromClub?->name ?? 'Unassigned' }} →
                                        <span class="font-medium">{{ $entry->toClub?->name }}</span>
                                    </p>
                                    <p class="numeric text-xs text-ink-muted">{{ $entry->changed_at->format('d M Y') }}</p>
                                    @if ($entry->reason)
                                        <p class="mt-0.5 text-xs text-ink-soft">{{ $entry->reason }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </x-ui.card>
                @endif
            @else
                <x-ui.alert tone="info" title="After adding">
                    You can start a plan, collect a fee, and mark attendance from the
                    {{ strtolower($organisation->term('member_singular')) }}'s profile.
                </x-ui.alert>
            @endif
        </div>
    </div>
</div>
