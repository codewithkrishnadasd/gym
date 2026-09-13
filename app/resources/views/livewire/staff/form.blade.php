<div>
    <x-ui.page-header :title="$organisationUser ? 'Edit '.$name : 'Invite '.$organisation->term('user_singular')"
        :back="route('tenant.staff.index')" :back-label="$organisation->term('user_plural')"
        description="Club assignments and permissions are set here, in the same flow." />

    <form wire:submit="save" class="grid gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-ui.card title="Person">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input wire:model="name" name="name" label="Full name" required />

                    @if ($organisationUser)
                        <x-ui.field label="WhatsApp number" hint="The sign-in number cannot be changed after the invitation.">
                            <input type="text" disabled value="{{ $phone }}"
                                class="min-h-[40px] w-full cursor-not-allowed rounded-lg border border-hairline-strong bg-sunken px-3 py-2 text-sm text-ink-muted">
                        </x-ui.field>
                    @else
                        <x-ui.input wire:model="phone" name="phone" label="WhatsApp number" type="tel" required
                            inputmode="tel" placeholder="98765 43210"
                            hint="This is how they sign in. If they already have an account, it is reused." />

                        <x-ui.input class="sm:col-span-2" wire:model="password" name="password" label="Temporary password"
                            type="password" hint="Only needed if this number has no account yet. Minimum 8 characters." />
                    @endif
                </div>
            </x-ui.card>

            <x-ui.card title="Access">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.select wire:model.live="role" name="role" label="Role" required
                        hint="Administrators have full access and bypass individual permissions.">
                        @foreach ($roles as $case)
                            <option value="{{ $case->value }}">
                                {{ $case->value === 'admin' ? 'Administrator' : $organisation->term('user_singular') }}
                            </option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.select wire:model="status" name="status" label="Status" required>
                        @foreach ($statuses as $case)
                            <option value="{{ $case->value }}">{{ $case->label() }}</option>
                        @endforeach
                    </x-ui.select>
                </div>
            </x-ui.card>

            <x-ui.card :title="'Assigned '.strtolower($organisation->term('club_plural'))"
                :description="$role === 'admin'
                    ? 'Administrators can already reach every '.strtolower($organisation->term('club_singular')).'. Assignments are still recorded for reporting.'
                    : 'A '.strtolower($organisation->term('user_singular')).' can only see and act on the '.strtolower($organisation->term('club_plural')).' assigned here.'">
                @if ($availableClubs->isEmpty())
                    <p class="py-3 text-sm text-ink-muted">
                        No active {{ strtolower($organisation->term('club_plural')) }} to assign yet.
                    </p>
                @else
                    <div class="grid gap-0.5 sm:grid-cols-2">
                        @foreach ($availableClubs as $club)
                            <x-ui.checkbox wire:model="clubIds" value="{{ $club->id }}" :label="$club->name"
                                :description="$club->address['line1'] ?? $club->code" />
                        @endforeach
                    </div>
                @endif
            </x-ui.card>

            @if ($role !== 'admin')
                <x-ui.card title="Permissions"
                    description="Explicitly grant only what this person needs. Finance administration, settings, expenses, and the audit log stay administrator-only.">
                    <div class="space-y-4">
                        @foreach ($permissionGroups as $groupName => $groupPermissions)
                            <div>
                                <p class="mb-1 px-2 text-[11px] font-semibold uppercase tracking-[0.08em] text-ink-muted">
                                    {{ $groupName }}
                                </p>
                                <div class="grid gap-0.5 sm:grid-cols-2">
                                    @foreach ($groupPermissions as $permission)
                                        <x-ui.checkbox wire:model="permissions" value="{{ $permission->value }}"
                                            :label="$permission->label()" />
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif
        </div>

        <div class="space-y-5">
            <x-ui.card title="Save">
                <div class="flex flex-col gap-2">
                    <x-ui.button type="submit" variant="primary" size="lg" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">
                            {{ $organisationUser ? 'Save changes' : 'Send invitation' }}
                        </span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Saving…</span>
                    </x-ui.button>

                    <x-ui.button :href="route('tenant.staff.index')" wire:navigate variant="ghost">Cancel</x-ui.button>
                </div>
            </x-ui.card>

            @if ($role === 'admin')
                <x-ui.alert tone="caution" title="Administrator access">
                    This person will be able to manage every {{ strtolower($organisation->term('club_singular')) }},
                    confirm payments, record expenses, and change organisation settings.
                </x-ui.alert>
            @endif
        </div>
    </form>
</div>
