<div>
    <x-ui.flash />

    <x-ui.page-header title="Plans"
        description="Pricing and duration for the memberships you sell. Archiving a plan hides it from new sales without touching existing subscriptions.">
        <x-slot:actions>
            @can('create', \App\Models\Plan::class)
                <x-ui.button variant="primary" icon="plus" :href="route('tenant.plans.create')" wire:navigate>New plan</x-ui.button>
            @endcan
        </x-slot:actions>
    </x-ui.page-header>

    <x-ui.card :padded="false">
        <x-ui.filters search="search" placeholder="Search plans…">
            <x-ui.filter-select wire:model.live="status" label="Status">
                <option value="">All statuses</option>
                @foreach ($statuses as $case)
                    <option value="{{ $case->value }}">{{ ucfirst($case->value) }}</option>
                @endforeach
            </x-ui.filter-select>
        </x-ui.filters>

        <div wire:loading.delay.long class="w-full">
            <x-ui.skeleton :rows="4" />
        </div>

        <div wire:loading.remove>
            @if ($plans->isEmpty())
                <x-ui.empty icon="rectangle-stack" title="No plans yet"
                    description="Create your first plan to start selling memberships and collecting fees.">
                    <x-slot:actions>
                        @can('create', \App\Models\Plan::class)
                            <x-ui.button variant="primary" icon="plus" :href="route('tenant.plans.create')" wire:navigate>New plan</x-ui.button>
                        @endcan
                    </x-slot:actions>
                </x-ui.empty>
            @else
                {{-- Desktop: dense table. --}}
                <x-ui.table class="hidden lg:block">
                    <x-slot:head>
                        <x-ui.th>Plan</x-ui.th>
                        <x-ui.th align="right">Price</x-ui.th>
                        <x-ui.th align="right">Duration</x-ui.th>
                        <x-ui.th align="right">Sessions</x-ui.th>
                        <x-ui.th align="right">Active</x-ui.th>
                        <x-ui.th>Status</x-ui.th>
                        <x-ui.th align="right">Actions</x-ui.th>
                    </x-slot:head>

                    @foreach ($plans as $plan)
                        <tr class="transition hover:bg-raised">
                            <x-ui.td>
                                <p class="font-medium text-ink">{{ $plan->name }}</p>
                                @if ($plan->description)
                                    <p class="max-w-md truncate text-xs text-ink-muted">{{ $plan->description }}</p>
                                @endif
                            </x-ui.td>
                            <x-ui.td align="right" numeric class="font-medium text-ink">{{ $organisation->money($plan->price_minor) }}</x-ui.td>
                            <x-ui.td align="right" numeric>{{ $plan->duration_days }} days</x-ui.td>
                            <x-ui.td align="right" numeric>{{ $plan->session_limit ?? 'Unlimited' }}</x-ui.td>
                            <x-ui.td align="right" numeric>{{ $plan->active_subscriptions_count }}</x-ui.td>
                            <x-ui.td>
                                <x-ui.badge :tone="$plan->status->value === 'active' ? 'positive' : 'neutral'">
                                    {{ ucfirst($plan->status->value) }}
                                </x-ui.badge>
                            </x-ui.td>
                            <x-ui.td align="right">
                                <div class="flex items-center justify-end gap-1">
                                    @can('update', \App\Models\Plan::class)
                                        <x-ui.button size="sm" variant="ghost" :href="route('tenant.plans.edit', $plan)" wire:navigate>Edit</x-ui.button>
                                    @endcan
                                    @can('archive', \App\Models\Plan::class)
                                        @if ($plan->status->value === 'archived')
                                            <x-ui.button size="sm" variant="ghost" wire:click="restore({{ $plan->id }})">Restore</x-ui.button>
                                        @else
                                            <x-ui.button size="sm" variant="ghost" wire:click="archive({{ $plan->id }})"
                                                wire:confirm="Archive “{{ $plan->name }}”? It will no longer be available for new subscriptions.">Archive</x-ui.button>
                                        @endif
                                    @endcan
                                </div>
                            </x-ui.td>
                        </tr>
                    @endforeach
                </x-ui.table>

                {{-- Mobile: stacked cards, so nothing is lost to horizontal scroll. --}}
                <ul class="divide-y divide-[var(--c-hairline)] lg:hidden">
                    @foreach ($plans as $plan)
                        <li class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-medium text-ink">{{ $plan->name }}</p>
                                    <p class="numeric mt-0.5 text-sm text-ink-soft">
                                        {{ $organisation->money($plan->price_minor) }} &middot; {{ $plan->duration_days }} days
                                    </p>
                                </div>
                                <x-ui.badge :tone="$plan->status->value === 'active' ? 'positive' : 'neutral'">
                                    {{ ucfirst($plan->status->value) }}
                                </x-ui.badge>
                            </div>

                            <div class="mt-3 flex items-center gap-2">
                                @can('update', \App\Models\Plan::class)
                                    <x-ui.button size="sm" :href="route('tenant.plans.edit', $plan)" wire:navigate>Edit</x-ui.button>
                                @endcan
                                @can('archive', \App\Models\Plan::class)
                                    @if ($plan->status->value === 'archived')
                                        <x-ui.button size="sm" wire:click="restore({{ $plan->id }})">Restore</x-ui.button>
                                    @else
                                        <x-ui.button size="sm" variant="danger" wire:click="archive({{ $plan->id }})"
                                            wire:confirm="Archive “{{ $plan->name }}”?">Archive</x-ui.button>
                                    @endif
                                @endcan
                            </div>
                        </li>
                    @endforeach
                </ul>

                {{ $plans->links() }}
            @endif
        </div>
    </x-ui.card>
</div>
