<div>
    <x-ui.flash />

    {{-- Offered after inviting or updating someone (MEP 6.5). --}}
    @if (session('notification_id'))
        <div class="mb-4">
            <livewire:notifications.action-panel :notification-id="session('notification_id')"
                :key="'staff-panel-'.session('notification_id')" />
        </div>
    @endif

    <x-ui.page-header :title="$organisation->term('user_plural')"
        :description="'People who can sign in to '.$organisation->name.'. Each can be assigned to several '.strtolower($organisation->term('club_plural')).'.'">
        <x-slot:actions>
            @can('create', \App\Models\OrganisationUser::class)
                <x-ui.button variant="primary" icon="plus" :href="route('tenant.staff.create')" wire:navigate>
                    Invite {{ $organisation->term('user_singular') }}
                </x-ui.button>
            @endcan
        </x-slot:actions>
    </x-ui.page-header>

    <x-ui.card :padded="false">
        <x-ui.filters search="search" placeholder="Search by name, email, or phone…">
            <x-ui.filter-select wire:model.live="role" label="Role">
                <option value="">All roles</option>
                @foreach ($roles as $case)
                    <option value="{{ $case->value }}">{{ $case->value === 'admin' ? 'Administrator' : $organisation->term('user_singular') }}</option>
                @endforeach
            </x-ui.filter-select>

            <x-ui.filter-select wire:model.live="status" label="Status">
                <option value="">All statuses</option>
                @foreach ($statuses as $case)
                    <option value="{{ $case->value }}">{{ $case->label() }}</option>
                @endforeach
            </x-ui.filter-select>

            <x-ui.filter-select wire:model.live="club" :label="$organisation->term('club_singular')">
                <option value="">All {{ strtolower($organisation->term('club_plural')) }}</option>
                @foreach ($clubs as $clubOption)
                    <option value="{{ $clubOption->id }}">{{ $clubOption->name }}</option>
                @endforeach
            </x-ui.filter-select>
        </x-ui.filters>

        <div wire:loading.delay class="w-full"><x-ui.skeleton :rows="5" /></div>

        <div wire:loading.remove>
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
                        <x-ui.th>{{ $organisation->term('club_plural') }}</x-ui.th>
                        <x-ui.th>Last sign-in</x-ui.th>
                        <x-ui.th>Status</x-ui.th>
                        <x-ui.th align="right"></x-ui.th>
                    </x-slot:head>

                    @foreach ($members as $person)
                        <tr class="transition hover:bg-raised">
                            <x-ui.td>
                                <div class="flex items-center gap-2.5">
                                    <x-ui.avatar :name="$person->user?->name ?? '?'" size="sm" />
                                    <div class="min-w-0">
                                        <p class="font-medium text-ink">{{ $person->user?->name }}</p>
                                        <p class="truncate text-xs text-ink-muted">{{ $person->user?->email }}</p>
                                    </div>
                                </div>
                            </x-ui.td>
                            <x-ui.td>
                                <x-ui.badge :tone="$person->isAdmin() ? 'accent' : 'neutral'" :dot="false">
                                    {{ $person->isAdmin() ? 'Administrator' : $organisation->term('user_singular') }}
                                </x-ui.badge>
                            </x-ui.td>
                            <x-ui.td>
                                @if ($person->isAdmin())
                                    <span class="text-ink-muted">All {{ strtolower($organisation->term('club_plural')) }}</span>
                                @elseif ($person->clubAssignments->isEmpty())
                                    <span class="text-caution">None assigned</span>
                                @else
                                    <span class="text-ink-soft">{{ $person->clubAssignments->pluck('club.name')->filter()->join(', ') }}</span>
                                @endif
                            </x-ui.td>
                            <x-ui.td numeric>{{ $person->last_login_at?->diffForHumans() ?? 'Never' }}</x-ui.td>
                            <x-ui.td><x-ui.badge :tone="$person->status->tone()">{{ $person->status->label() }}</x-ui.badge></x-ui.td>
                            <x-ui.td align="right">
                                <div class="flex items-center justify-end gap-1">
                                    @can('update', $person)
                                        <x-ui.button size="sm" variant="ghost" :href="route('tenant.staff.edit', $person)" wire:navigate>Edit</x-ui.button>

                                        @if ($person->status->value === 'active')
                                            <x-ui.button size="sm" variant="ghost" wire:click="suspend({{ $person->id }})"
                                                wire:confirm="Suspend {{ $person->user?->name }}? They will not be able to sign in.">Suspend</x-ui.button>
                                        @else
                                            <x-ui.button size="sm" variant="ghost" wire:click="reactivate({{ $person->id }})">Reactivate</x-ui.button>
                                        @endif

                                        @if ($person->status->value !== 'deactivated')
                                            <x-ui.button size="sm" variant="ghost" wire:click="deactivate({{ $person->id }})"
                                                wire:confirm="Deactivate {{ $person->user?->name }}? This removes their access permanently.">Deactivate</x-ui.button>
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
                                    <p class="truncate text-xs text-ink-muted">{{ $person->user?->email }}</p>
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
                                    @if ($person->status->value === 'active')
                                        <x-ui.button size="sm" variant="danger" wire:click="suspend({{ $person->id }})">Suspend</x-ui.button>
                                    @else
                                        <x-ui.button size="sm" wire:click="reactivate({{ $person->id }})">Reactivate</x-ui.button>
                                    @endif
                                </div>
                            @endcan
                        </li>
                    @endforeach
                </ul>

                {{ $members->links() }}
            @endif
        </div>
    </x-ui.card>
</div>
