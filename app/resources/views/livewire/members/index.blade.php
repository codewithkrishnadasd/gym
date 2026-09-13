<div>
    <x-ui.flash />

    <x-ui.page-header :title="$organisation->term('member_plural')"
        :description="'Everyone training with you. Each belongs to exactly one '.strtolower($organisation->term('club_singular')).'.'">
        <x-slot:actions>
            <x-ui.button icon="arrow-down-tray" :href="route('tenant.members.export', request()->query())">Export CSV</x-ui.button>
            @can('create', \App\Models\Member::class)
                <x-ui.button variant="primary" icon="plus" :href="route('tenant.members.create')" wire:navigate>
                    Add {{ $organisation->term('member_singular') }}
                </x-ui.button>
            @endcan
        </x-slot:actions>
    </x-ui.page-header>

    <x-ui.card :padded="false">
        <x-ui.filters search="search" placeholder="Search by name, phone, or email…">
            <x-ui.filter-select wire:model.live="club" :label="$organisation->term('club_singular')">
                <option value="">All {{ strtolower($organisation->term('club_plural')) }}</option>
                @foreach ($clubs as $availableClub)
                    <option value="{{ $availableClub->id }}">{{ $availableClub->name }}</option>
                @endforeach
            </x-ui.filter-select>

            <x-ui.filter-select wire:model.live="status" label="Status">
                <option value="">All statuses</option>
                @foreach ($statuses as $case)
                    <option value="{{ $case->value }}">{{ $case->label() }}</option>
                @endforeach
            </x-ui.filter-select>
        </x-ui.filters>

        <div wire:loading.delay class="w-full"><x-ui.skeleton :rows="6" /></div>

        <div wire:loading.remove>
            @if ($members->isEmpty())
                <x-ui.empty icon="user-group"
                    :title="$search !== '' || $status !== '' || $club !== '' ? 'Nothing matches those filters' : 'No '.strtolower($organisation->term('member_plural')).' yet'"
                    :description="$search !== '' || $status !== '' || $club !== '' ? 'Try a different search or clear the filters.' : 'Add your first '.strtolower($organisation->term('member_singular')).' to start tracking plans, attendance, and fees.'">
                    <x-slot:actions>
                        @can('create', \App\Models\Member::class)
                            <x-ui.button variant="primary" icon="plus" :href="route('tenant.members.create')" wire:navigate>
                                Add {{ $organisation->term('member_singular') }}
                            </x-ui.button>
                        @endcan
                    </x-slot:actions>
                </x-ui.empty>
            @else
                <x-ui.table class="hidden lg:block">
                    <x-slot:head>
                        <x-ui.th>Name</x-ui.th>
                        <x-ui.th>Contact</x-ui.th>
                        <x-ui.th>{{ $organisation->term('club_singular') }}</x-ui.th>
                        <x-ui.th>Plan</x-ui.th>
                        <x-ui.th align="right">Outstanding</x-ui.th>
                        <x-ui.th>Status</x-ui.th>
                        <x-ui.th align="right"></x-ui.th>
                    </x-slot:head>

                    @foreach ($members as $member)
                        @php
                            $subscription = $member->subscriptions->first();
                            $due = $subscription ? max(0, $subscription->amount_due_minor - $subscription->amount_paid_minor) : 0;
                        @endphp

                        <tr class="transition hover:bg-raised">
                            <x-ui.td>
                                <div class="flex items-center gap-2.5">
                                    <x-ui.avatar :name="$member->name" size="sm" />
                                    <a href="{{ route('tenant.members.show', $member) }}" wire:navigate
                                        class="font-medium text-ink hover:text-accent">{{ $member->name }}</a>
                                </div>
                            </x-ui.td>
                            <x-ui.td numeric>{{ $member->phone ?: '—' }}</x-ui.td>
                            <x-ui.td>{{ $member->primaryClub?->name ?? 'Unassigned' }}</x-ui.td>
                            <x-ui.td>
                                @if ($subscription)
                                    <p class="text-ink">{{ $subscription->plan->name }}</p>
                                    <p class="numeric text-xs text-ink-muted">to {{ $subscription->end_date->format('d M Y') }}</p>
                                @else
                                    <span class="text-ink-muted">No active plan</span>
                                @endif
                            </x-ui.td>
                            <x-ui.td align="right" numeric class="{{ $due > 0 ? 'font-medium text-caution' : 'text-ink-muted' }}">
                                {{ $due > 0 ? $organisation->money($due) : '—' }}
                            </x-ui.td>
                            <x-ui.td><x-ui.badge :tone="$member->status->tone()">{{ $member->status->label() }}</x-ui.badge></x-ui.td>
                            <x-ui.td align="right">
                                <div class="flex items-center justify-end gap-1">
                                    <x-ui.button size="sm" variant="ghost" :href="route('tenant.members.show', $member)" wire:navigate>View</x-ui.button>
                                    @can('update', $member)
                                        <x-ui.button size="sm" variant="ghost" :href="route('tenant.members.edit', $member)" wire:navigate>Edit</x-ui.button>
                                    @endcan
                                    @can('archive', $member)
                                        @if ($member->status->value === 'archived')
                                            <x-ui.button size="sm" variant="ghost" wire:click="restore({{ $member->id }})">Restore</x-ui.button>
                                        @else
                                            <x-ui.button size="sm" variant="ghost" wire:click="archive({{ $member->id }})"
                                                wire:confirm="Archive {{ $member->name }}?">Archive</x-ui.button>
                                        @endif
                                    @endcan
                                </div>
                            </x-ui.td>
                        </tr>
                    @endforeach
                </x-ui.table>

                <ul class="divide-y divide-[var(--c-hairline)] lg:hidden">
                    @foreach ($members as $member)
                        @php
                            $subscription = $member->subscriptions->first();
                            $due = $subscription ? max(0, $subscription->amount_due_minor - $subscription->amount_paid_minor) : 0;
                        @endphp

                        <li>
                            <a href="{{ route('tenant.members.show', $member) }}" wire:navigate
                                class="flex items-center gap-3 p-4 transition hover:bg-raised">
                                <x-ui.avatar :name="$member->name" />
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-medium text-ink">{{ $member->name }}</p>
                                    <p class="numeric truncate text-xs text-ink-muted">
                                        {{ $member->phone }} · {{ $member->primaryClub?->name ?? 'Unassigned' }}
                                    </p>
                                    <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                                        <x-ui.badge :tone="$member->status->tone()">{{ $member->status->label() }}</x-ui.badge>
                                        @if ($due > 0)
                                            <x-ui.badge tone="caution">{{ $organisation->money($due) }} due</x-ui.badge>
                                        @endif
                                    </div>
                                </div>
                                <x-heroicon-o-chevron-right class="h-4 w-4 shrink-0 text-ink-muted" />
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{ $members->links() }}
            @endif
        </div>
    </x-ui.card>
</div>
