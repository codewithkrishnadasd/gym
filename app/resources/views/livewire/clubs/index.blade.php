<div>
    <x-ui.flash />

    <x-ui.page-header :title="$organisation->term('club_plural')"
        :description="'Locations you operate. Each '.strtolower($organisation->term('member_singular')).' belongs to exactly one, and '.strtolower($organisation->term('user_plural')).' can be assigned to several.'">
        <x-slot:actions>
            @can('create', \App\Models\Club::class)
                <x-ui.button variant="primary" icon="plus" :href="route('tenant.clubs.create')" wire:navigate>
                    New {{ $organisation->term('club_singular') }}
                </x-ui.button>
            @endcan
        </x-slot:actions>
    </x-ui.page-header>

    <x-ui.card :padded="false">
        <x-ui.filters search="search" :placeholder="'Search by name or code…'">
            <x-ui.filter-select wire:model.live="status" label="Status">
                <option value="">All except removed</option>
                @foreach ($statuses as $case)
                    <option value="{{ $case->value }}">{{ $case->label() }}</option>
                @endforeach
            </x-ui.filter-select>
        </x-ui.filters>

        <div wire:loading.delay class="w-full"><x-ui.skeleton :rows="4" /></div>

        <div wire:loading.remove>
            @if ($clubs->isEmpty())
                <x-ui.empty icon="building-office-2" :title="'No '.strtolower($organisation->term('club_plural')).' yet'"
                    :description="'Create your first '.strtolower($organisation->term('club_singular')).' to start adding '.strtolower($organisation->term('member_plural')).'.'">
                    <x-slot:actions>
                        @can('create', \App\Models\Club::class)
                            <x-ui.button variant="primary" icon="plus" :href="route('tenant.clubs.create')" wire:navigate>
                                New {{ $organisation->term('club_singular') }}
                            </x-ui.button>
                        @endcan
                    </x-slot:actions>
                </x-ui.empty>
            @else
                <x-ui.table class="hidden lg:block">
                    <x-slot:head>
                        <x-ui.th>{{ $organisation->term('club_singular') }}</x-ui.th>
                        <x-ui.th>Code</x-ui.th>
                        <x-ui.th align="right">{{ $organisation->term('member_plural') }}</x-ui.th>
                        <x-ui.th align="right">{{ $organisation->term('user_plural') }}</x-ui.th>
                        <x-ui.th align="right">Revenue (MTD)</x-ui.th>
                        <x-ui.th align="right">Attendance (MTD)</x-ui.th>
                        <x-ui.th>Status</x-ui.th>
                        <x-ui.th align="right"></x-ui.th>
                    </x-slot:head>

                    @foreach ($clubs as $club)
                        <tr class="transition hover:bg-raised">
                            <x-ui.td>
                                <a href="{{ route('tenant.clubs.show', $club) }}" wire:navigate
                                    class="font-medium text-ink hover:text-accent">{{ $club->name }}</a>
                                <x-ui.reference :value="$organisation->reference('club', $club->id)" class="ml-1.5" />
                                @if ($club->address['line1'] ?? null)
                                    <p class="max-w-xs truncate text-xs text-ink-muted">{{ $club->address['line1'] }}</p>
                                @endif
                            </x-ui.td>
                            <x-ui.td><span class="rounded bg-sunken px-1.5 py-0.5 font-mono text-xs">{{ $club->code }}</span></x-ui.td>
                            <x-ui.td align="right" numeric>{{ number_format($club->members_count) }}</x-ui.td>
                            <x-ui.td align="right" numeric>{{ number_format($club->active_staff_count) }}</x-ui.td>
                            <x-ui.td align="right" numeric class="font-medium text-ink">{{ $organisation->money((int) ($club->revenue_minor ?? 0)) }}</x-ui.td>
                            <x-ui.td align="right" numeric>{{ number_format($club->attendance_count) }}</x-ui.td>
                            <x-ui.td><x-ui.badge :tone="$club->status->tone()">{{ $club->status->label() }}</x-ui.badge></x-ui.td>
                            <x-ui.td align="right">
                                <div class="flex items-center justify-end gap-1">
                                    <x-ui.button size="sm" variant="ghost" :href="route('tenant.clubs.show', $club)" wire:navigate>View</x-ui.button>
                                    @can('update', $club)
                                        <x-ui.button size="sm" variant="ghost" :href="route('tenant.clubs.edit', $club)" wire:navigate>Edit</x-ui.button>
                                    @endcan
                                    @can('archive', $club)
                                        @if ($club->status->value === 'archived')
                                            <x-ui.button size="sm" variant="ghost" icon="arrow-uturn-left" wire:click="restore({{ $club->id }})">Restore</x-ui.button>
                                        @else
                                            <x-ui.button size="sm" variant="ghost" icon="trash" wire:click="archive({{ $club->id }})"
                                                data-confirm-title="Remove this club?" data-confirm-action="Remove" data-confirm-tone="danger" data-confirm="Remove “{{ $club->name }}”? It stays in historical reports and can be restored.">Remove</x-ui.button>
                                        @endif
                                    @endcan
                                </div>
                            </x-ui.td>
                        </tr>
                    @endforeach
                </x-ui.table>

                <ul class="divide-y divide-[var(--c-hairline)] lg:hidden">
                    @foreach ($clubs as $club)
                        <li class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <a href="{{ route('tenant.clubs.show', $club) }}" wire:navigate
                                        class="font-medium text-ink">{{ $club->name }}</a>
                                    <p class="mt-0.5 flex items-center gap-1.5 font-mono text-xs text-ink-muted">
                                        <x-ui.reference :value="$organisation->reference('club', $club->id)" /> {{ $club->code }}
                                    </p>
                                </div>
                                <x-ui.badge :tone="$club->status->tone()">{{ $club->status->label() }}</x-ui.badge>
                            </div>

                            <dl class="numeric mt-3 grid grid-cols-3 gap-2 text-center">
                                <div class="rounded-lg bg-raised py-1.5">
                                    <dt class="text-[10px] uppercase text-ink-muted">{{ $organisation->term('member_plural') }}</dt>
                                    <dd class="text-sm font-semibold">{{ number_format($club->members_count) }}</dd>
                                </div>
                                <div class="rounded-lg bg-raised py-1.5">
                                    <dt class="text-[10px] uppercase text-ink-muted">{{ $organisation->term('user_plural') }}</dt>
                                    <dd class="text-sm font-semibold">{{ number_format($club->active_staff_count) }}</dd>
                                </div>
                                <div class="rounded-lg bg-raised py-1.5">
                                    <dt class="text-[10px] uppercase text-ink-muted">Revenue</dt>
                                    <dd class="text-sm font-semibold">{{ $organisation->moneyCompact((int) ($club->revenue_minor ?? 0)) }}</dd>
                                </div>
                            </dl>

                            <div class="mt-3 flex items-center gap-2">
                                <x-ui.button size="sm" :href="route('tenant.clubs.show', $club)" wire:navigate>View</x-ui.button>
                                @can('update', $club)
                                    <x-ui.button size="sm" :href="route('tenant.clubs.edit', $club)" wire:navigate>Edit</x-ui.button>
                                @endcan
                            </div>
                        </li>
                    @endforeach
                </ul>

                {{ $clubs->links() }}
            @endif
        </div>
    </x-ui.card>
</div>
