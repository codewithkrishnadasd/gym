<div>
    <x-ui.page-header :title="$organisationUser ? 'Edit '.$name : 'Invite '.$organisation->term('user_singular')"
        :back="route('tenant.staff.index')" :back-label="$organisation->term('user_plural')"
        description="Club assignments and permissions are set here, in the same flow.">
        @if ($organisationUser && $organisationUser->user?->phone)
            @can('sendNotifications', $organisation)
                <x-slot:actions>
                    <livewire:notifications.compose-menu recipient-type="user" :recipient-id="$organisationUser->id" :key="'compose-staff-'.$organisationUser->id" />
                </x-slot:actions>
            @endcan
        @endif
    </x-ui.page-header>

    @can('sendNotifications', $organisation)
        <div class="mb-4">
            <livewire:notifications.action-panel :notification-id="null" key="staff-form-panel" />
        </div>
    @endcan

    <form wire:submit="save" class="grid gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-ui.card title="Person">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input wire:model="name" name="name" label="Full name" required />

                    @if ($organisationUser)
                        <x-ui.input wire:model="phone" name="phone" label="WhatsApp number" type="tel" required
                            inputmode="tel"
                            hint="This is how they sign in. One person is one account across every organisation, so changing it changes their sign-in everywhere." />
                    @else
                        <x-ui.input wire:model="phone" name="phone" label="WhatsApp number" type="tel" required
                            inputmode="tel" placeholder="98765 43210"
                            hint="This is how they sign in. If they already have an account, it is reused." />

                        {{-- No password field: a new account gets a single-use link on
                             WhatsApp and chooses its own, so no password is ever known
                             to both an admin and its owner. --}}
                        <div class="sm:col-span-2">
                            <x-ui.alert tone="info" title="They set their own password">
                                If this number is new, a one-time link is created when you save. Send it to them on
                                WhatsApp from the panel that appears — it expires in
                                {{ \App\Actions\Auth\IssuePasswordResetLink::lifetimeMinutes() }} minutes.
                            </x-ui.alert>
                        </div>
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

            @if ($organisation->usesClubs())
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
            @endif

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
                                        @php
                                            // Ticked because something else needs it. Shown as
                                            // locked rather than silently re-ticking under the
                                            // admin's cursor when they try to turn it off.
                                            $requiredBy = $permission->requiredBy($permissions);
                                        @endphp

                                        <x-ui.checkbox wire:model.live="permissions" value="{{ $permission->value }}"
                                            :label="$permission->label()"
                                            :disabled="$requiredBy !== []"
                                            :description="$requiredBy === []
                                                ? null
                                                : 'Required by '.collect($requiredBy)->map(fn ($dependent) => $dependent->label())->implode(', ')" />
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

    {{-- Only for an existing staff member: an upload needs a row to attach to,
         and this saves itself rather than riding on the form above. --}}
    @if ($organisationUser && $organisation->hasFeature('staff_attendance') && $organisation->hasFeature('staff'))
        <div class="mt-5">
            <h2 class="mb-3 font-[family-name:var(--font-display)] text-base font-semibold text-ink">Attendance</h2>
            <livewire:attendance.report subject-type="user" :subject-id="$organisationUser->id" :key="'attendance-staff-'.$organisationUser->id" />
        </div>
    @endif

    @if ($organisationUser)
        @can('viewAny', \App\Models\Document::class)
            <div class="mt-5 lg:w-2/3">
                <livewire:documents.panel subject-type="user" :subject-id="$organisationUser->id"
                    :key="'documents-staff-'.$organisationUser->id" />
            </div>
        @endcan
    @endif
</div>
