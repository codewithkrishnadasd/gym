<div>
    <x-ui.flash />

    {{-- Mounted unconditionally so a message composed by a Livewire action on
         this page has a listener to reach. Rendered with no notification it
         draws nothing; keyed to the page, not the message, so the component
         survives from one action to the next. --}}
    @can('sendNotifications', $organisation)
        <div class="mb-4">
            <livewire:notifications.action-panel :notification-id="session('notification_id')" key="staff-panel" />
        </div>
    @endcan

    <x-ui.page-header :title="$organisation->term('user_plural')"
        :description="'People who can sign in to '.$organisation->name.($organisation->usesClubs() ? '. Each can be assigned to several '.strtolower($organisation->term('club_plural')).'.' : '.')">
        <x-slot:actions>
            @can('mark', [\App\Models\Attendance::class, \App\Enums\AttendanceSubjectType::User, null])
                <x-ui.button icon="clipboard-document-check" :href="route('tenant.attendance.staff')" wire:navigate>
                    Take attendance
                </x-ui.button>
            @endcan
        </x-slot:actions>
    </x-ui.page-header>

    {{-- The page's one action, as the floating button every page shares. --}}
    @can('create', \App\Models\OrganisationUser::class)
        <x-ui.fab :href="route('tenant.staff.create')" label="Invite {{ $organisation->term('user_singular') }}" symbol="+" />
    @endcan

    <x-ui.card :padded="false">
        <x-ui.filters search="search" placeholder="Search by name or WhatsApp number…">
            <x-ui.filter-select wire:model.live="role" label="Role">
                <option value="">All roles</option>
                @foreach ($roles as $case)
                    <option value="{{ $case->value }}">{{ $case->value === 'admin' ? 'Administrator' : $organisation->term('user_singular') }}</option>
                @endforeach
            </x-ui.filter-select>

            <x-ui.filter-select wire:model.live="status" label="Status">
                <option value="">All except removed</option>
                @foreach ($statuses as $case)
                    <option value="{{ $case->value }}">{{ $case->label() }}</option>
                @endforeach
            </x-ui.filter-select>

            @if ($organisation->usesClubs())
                <x-ui.filter-select wire:model.live="club" :label="$organisation->term('club_singular')">
                    <option value="">All {{ strtolower($organisation->term('club_plural')) }}</option>
                    @foreach ($clubs as $clubOption)
                        <option value="{{ $clubOption->id }}">{{ $clubOption->name }}</option>
                    @endforeach
                </x-ui.filter-select>
            @endif
        </x-ui.filters>

        <x-ui.list-loader />

        @if ($members->isEmpty())
            <x-ui.empty icon="identification" :title="'No '.strtolower($organisation->term('user_plural')).' found'"
                :description="'Invite someone to give them access to '.$organisation->name.'.'">
                <x-slot:actions>
                    @can('create', \App\Models\OrganisationUser::class)
                        <x-ui.button variant="primary" icon="plus" :href="route('tenant.staff.create')" wire:navigate>
                            Invite {{ $organisation->term('user_singular') }}
                        </x-ui.button>
                    @endcan
                </x-slot:actions>
            </x-ui.empty>
        @else
            <x-ui.table class="hidden lg:block">
                <x-slot:head>
                    <x-ui.th>Name</x-ui.th>
                    <x-ui.th>Role</x-ui.th>
                    @if ($organisation->usesClubs())
                        <x-ui.th>{{ $organisation->term('club_plural') }}</x-ui.th>
                    @endif
                    <x-ui.th>Last sign-in</x-ui.th>
                    <x-ui.th>Status</x-ui.th>
                    <x-ui.th align="right"></x-ui.th>
                </x-slot:head>

                @foreach ($members as $person)
                    <tr class="transition hover:bg-list-hover">
                        <x-ui.td>
                            <div class="flex items-center gap-2.5">
                                <x-ui.avatar :name="$person->user?->name ?? '?'" size="sm" />
                                <div class="min-w-0">
                                    <p class="font-medium text-ink">{{ $person->user?->name }}
                                        <x-ui.reference :value="$organisation->reference('staff', $person->id)" class="ml-1" /></p>
                                    <p class="numeric truncate text-xs text-ink-muted">{{ $person->user?->phone }}</p>
                                </div>
                            </div>
                        </x-ui.td>
                        <x-ui.td>
                            <x-ui.badge :tone="$person->isAdmin() ? 'accent' : 'neutral'" :dot="false">
                                {{ $person->isAdmin() ? 'Administrator' : $organisation->term('user_singular') }}
                            </x-ui.badge>
                        </x-ui.td>
                        @if ($organisation->usesClubs())
                        <x-ui.td>
                            @if ($person->isAdmin())
                                <span class="text-ink-muted">All {{ strtolower($organisation->term('club_plural')) }}</span>
                            @elseif ($person->clubAssignments->isEmpty())
                                <span class="text-caution">None assigned</span>
                            @else
                                <span class="text-ink-soft">{{ $person->clubAssignments->pluck('club.name')->filter()->join(', ') }}</span>
                            @endif
                        </x-ui.td>
                        @endif
                        <x-ui.td numeric>{{ $person->last_login_at?->diffForHumans() ?? 'Never' }}</x-ui.td>
                        <x-ui.td><x-ui.badge :tone="$person->status->tone()">{{ $person->status->label() }}</x-ui.badge></x-ui.td>
                        <x-ui.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                @can('issuePasswordResetLink', $person)
                                    <x-ui.button size="sm" variant="ghost" icon="key"
                                        wire:click="sendResetLink({{ $person->id }})"
                                        data-confirm-title="Create a password link?" data-confirm-action="Create link" data-confirm-tone="accent" data-confirm="Create a password link for {{ $person->user?->name }}? Any earlier link stops working."
                                        wire:loading.attr="disabled" wire:target="sendResetLink({{ $person->id }})">Reset link</x-ui.button>
                                @endcan

                                @can('update', $person)
                                    <x-ui.button size="sm" variant="ghost" :href="route('tenant.staff.edit', $person)" wire:navigate>Edit</x-ui.button>

                                    {{-- Suspend and Remove are different things: a suspension
                                         is expected to end, removal is not. --}}
                                    @if ($person->status->value === 'deactivated')
                                        <x-ui.button size="sm" variant="ghost" icon="arrow-uturn-left"
                                            wire:click="reactivate({{ $person->id }})">Restore</x-ui.button>
                                    @else
                                        @if ($person->status->value === 'active')
                                            <x-ui.button size="sm" variant="ghost" icon="pause-circle" wire:click="suspend({{ $person->id }})"
                                                data-confirm-title="Suspend this person?" data-confirm-action="Suspend" data-confirm-tone="danger" data-confirm="Suspend {{ $person->user?->name }}? They will not be able to sign in until you reactivate them.">Suspend</x-ui.button>
                                        @else
                                            <x-ui.button size="sm" variant="ghost" icon="play-circle" wire:click="reactivate({{ $person->id }})">Reactivate</x-ui.button>
                                        @endif

                                        <x-ui.button size="sm" variant="ghost" icon="trash" wire:click="deactivate({{ $person->id }})"
                                            data-confirm-title="Remove this person?" data-confirm-action="Remove" data-confirm-tone="danger" data-confirm="Remove {{ $person->user?->name }}? They lose access immediately. They stay in historical reports and can be restored.">Remove</x-ui.button>
                                    @endif
                                @endcan
                            </div>
                        </x-ui.td>
                    </tr>
                @endforeach
            </x-ui.table>

            <ul class="divide-y divide-[var(--c-hairline)] lg:hidden">
                @foreach ($members as $person)
                    <li class="p-4">
                        <div class="flex items-start gap-3">
                            <x-ui.avatar :name="$person->user?->name ?? '?'" />
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-medium text-ink">{{ $person->user?->name }}</p>
                                <p class="numeric truncate text-xs text-ink-muted">{{ $person->user?->phone }}</p>
                                <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                                    <x-ui.badge :tone="$person->status->tone()">{{ $person->status->label() }}</x-ui.badge>
                                    <x-ui.badge :tone="$person->isAdmin() ? 'accent' : 'neutral'" :dot="false">
                                        {{ $person->isAdmin() ? 'Administrator' : $organisation->term('user_singular') }}
                                    </x-ui.badge>
                                </div>
                            </div>
                        </div>

                        @can('update', $person)
                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <x-ui.button size="sm" :href="route('tenant.staff.edit', $person)" wire:navigate>Edit</x-ui.button>
                                @can('issuePasswordResetLink', $person)
                                    <x-ui.button size="sm" icon="key" wire:click="sendResetLink({{ $person->id }})"
                                        data-confirm-title="Create a password link?" data-confirm-action="Create link" data-confirm-tone="accent" data-confirm="Create a password link for {{ $person->user?->name }}? Any earlier link stops working.">Reset link</x-ui.button>
                                @endcan
                                @if ($person->status->value === 'deactivated')
                                    <x-ui.button size="sm" icon="arrow-uturn-left" wire:click="reactivate({{ $person->id }})">Restore</x-ui.button>
                                @elseif ($person->status->value === 'active')
                                    <x-ui.button size="sm" variant="danger" icon="trash" wire:click="deactivate({{ $person->id }})"
                                        data-confirm-title="Remove this person?" data-confirm-action="Remove" data-confirm-tone="danger" data-confirm="Remove {{ $person->user?->name }}? They lose access immediately and can be restored.">Remove</x-ui.button>
                                @else
                                    <x-ui.button size="sm" icon="play-circle" wire:click="reactivate({{ $person->id }})">Reactivate</x-ui.button>
                                @endif
                            </div>
                        @endcan
                    </li>
                @endforeach
            </ul>

            {{ $members->links() }}
        @endif
    </x-ui.card>
</div>
