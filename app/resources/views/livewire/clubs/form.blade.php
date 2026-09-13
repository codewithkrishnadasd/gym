<div>
    <x-ui.page-header :title="$club ? 'Edit '.$club->name : 'New '.$organisation->term('club_singular')"
        :back="route('tenant.clubs.index')" :back-label="$organisation->term('club_plural')"
        :description="'The code must be unique within '.$organisation->name.'.'" />

    <form wire:submit="save" class="grid gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-ui.card title="Details">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input wire:model="name" name="name" label="Name" required
                        :placeholder="'e.g. Downtown '.$organisation->term('club_singular')" />

                    <x-ui.input wire:model="code" name="code" label="Code" required placeholder="e.g. DTN"
                        hint="A short identifier used in reports and exports." />

                    <x-ui.input wire:model="phone" name="phone" label="Phone" />
                    <x-ui.input wire:model="email" name="email" label="Email" type="email" />

                    <x-ui.input class="sm:col-span-2" wire:model="addressLine" name="addressLine" label="Address" />

                    <x-ui.select class="sm:col-span-2" wire:model="timezone" name="timezone" label="Timezone" required
                        hint="Used for this location's attendance dates when it differs from the organisation.">
                        @foreach ($timezones as $zone)
                            <option value="{{ $zone }}">{{ $zone }}</option>
                        @endforeach
                    </x-ui.select>
                </div>
            </x-ui.card>

            <x-ui.card title="Opening hours" description="Shown to staff and used as context for attendance.">
                <div class="space-y-1.5">
                    @foreach ($days as $key => $label)
                        <div class="flex flex-wrap items-center gap-2 rounded-lg px-1 py-1 sm:flex-nowrap">
                            <span class="w-24 shrink-0 text-sm text-ink-soft">{{ $label }}</span>

                            <label class="flex min-h-[36px] cursor-pointer items-center gap-1.5 text-xs text-ink-muted">
                                <input type="checkbox" wire:model.live="openingHours.{{ $key }}.closed"
                                    class="h-4 w-4 rounded border-hairline-strong accent-[var(--c-accent)]">
                                Closed
                            </label>

                            @unless ($openingHours[$key]['closed'])
                                <div class="flex items-center gap-1.5">
                                    <input type="time" wire:model="openingHours.{{ $key }}.open" aria-label="{{ $label }} opening time"
                                        class="numeric min-h-[36px] rounded-lg border border-hairline-strong bg-surface px-2 text-sm text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25 max-lg:min-h-[44px]">
                                    <span class="text-xs text-ink-muted">to</span>
                                    <input type="time" wire:model="openingHours.{{ $key }}.close" aria-label="{{ $label }} closing time"
                                        class="numeric min-h-[36px] rounded-lg border border-hairline-strong bg-surface px-2 text-sm text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25 max-lg:min-h-[44px]">
                                </div>
                            @endunless
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <x-ui.card :title="'Assigned '.strtolower($organisation->term('user_plural'))"
                :description="strtolower($organisation->term('user_plural')).' can be assigned to several '.strtolower($organisation->term('club_plural')).'. Unassigning keeps the history rather than deleting it.'">
                @if ($availableUsers->isEmpty())
                    <p class="py-3 text-sm text-ink-muted">
                        No active {{ strtolower($organisation->term('user_plural')) }} to assign yet.
                    </p>
                @else
                    <div class="grid gap-0.5 sm:grid-cols-2">
                        @foreach ($availableUsers as $person)
                            <x-ui.checkbox wire:model="assignedUserIds" value="{{ $person->id }}"
                                :label="$person->user?->name"
                                :description="$person->isAdmin() ? 'Administrator' : $person->user?->email" />
                        @endforeach
                    </div>
                @endif
            </x-ui.card>
        </div>

        <div class="space-y-5">
            <x-ui.card title="Save">
                <div class="flex flex-col gap-2">
                    <x-ui.button type="submit" variant="primary" size="lg" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">
                            {{ $club ? 'Save changes' : 'Create '.strtolower($organisation->term('club_singular')) }}
                        </span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Saving…</span>
                    </x-ui.button>

                    <x-ui.button :href="route('tenant.clubs.index')" wire:navigate variant="ghost">Cancel</x-ui.button>
                </div>
            </x-ui.card>

            @if ($club)
                <x-ui.card title="Shortcuts">
                    <div class="flex flex-col gap-2">
                        <x-ui.button :href="route('tenant.clubs.show', $club)" wire:navigate icon="chart-bar">Overview and KPIs</x-ui.button>
                        <x-ui.button :href="route('tenant.members.index', ['club' => $club->id])" wire:navigate icon="user-group">
                            {{ $organisation->term('member_plural') }}
                        </x-ui.button>
                        <x-ui.button :href="route('tenant.attendance.members', ['clubId' => $club->id])" wire:navigate icon="clipboard-document-check">
                            Attendance
                        </x-ui.button>
                    </div>
                </x-ui.card>
            @endif
        </div>
    </form>
</div>
