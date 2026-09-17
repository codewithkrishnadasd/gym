@php
    $iconFor = fn (string $action): string => match (true) {
        str_starts_with($action, 'fee_payment.confirmed') => 'check-badge',
        str_starts_with($action, 'fee_payment.rejected') => 'x-circle',
        str_starts_with($action, 'fee_payment') => 'banknotes',
        str_starts_with($action, 'expense') => 'receipt-percent',
        str_starts_with($action, 'subscription') => 'rectangle-stack',
        str_starts_with($action, 'member') => 'user-group',
        default => 'shield-check',
    };

    $toneFor = fn (string $action): string => match (true) {
        str_contains($action, 'confirmed'), str_contains($action, 'created') => 'positive',
        str_contains($action, 'rejected'), str_contains($action, 'reversed') => 'critical',
        str_contains($action, 'updated'), str_contains($action, 'changed') => 'info',
        default => 'neutral',
    };
@endphp

<div>
    <x-ui.page-header title="Audit log"
        description="An append-only record of every consequential change. Entries can never be edited or deleted." />

    <x-ui.card :padded="false">
        <x-ui.filters search="search" placeholder="Search actions…">
            <label class="block">
                <span class="mb-1 block text-xs font-medium text-ink-soft">From</span>
                <x-ui.date-input bare wire:model.live="from" aria-label="From date" class="w-full" />
            </label>
            <label class="block">
                <span class="mb-1 block text-xs font-medium text-ink-soft">To</span>
                <x-ui.date-input bare wire:model.live="to" aria-label="To date" class="w-full" />
            </label>

            <x-ui.filter-select wire:model.live="entityType" label="Record type">
                <option value="">All record types</option>
                @foreach ($entityTypes as $type)
                    <option value="{{ $type }}">{{ \Illuminate\Support\Str::of($type)->replace('_', ' ')->ucfirst() }}</option>
                @endforeach
            </x-ui.filter-select>

            <x-ui.filter-select wire:model.live="actor" label="Actor">
                <option value="">Anyone</option>
                @foreach ($actors as $person)
                    <option value="{{ $person->id }}">{{ $person->user?->name }}</option>
                @endforeach
            </x-ui.filter-select>
        </x-ui.filters>

        <x-ui.list-loader />

        @if ($events->isEmpty())
            <x-ui.empty icon="shield-check" title="No audit entries"
                description="Actions such as payment confirmations and expense reversals will be recorded here." />
        @else
            <ol class="divide-y divide-[var(--c-hairline)]">
                @foreach ($events as $event)
                    <li class="flex items-start gap-3 px-4 py-3">
                        <span @class([
                            'mt-0.5 grid h-8 w-8 shrink-0 place-items-center rounded-lg',
                            'bg-positive-soft text-positive' => $toneFor($event->action) === 'positive',
                            'bg-critical-soft text-critical' => $toneFor($event->action) === 'critical',
                            'bg-info-soft text-info' => $toneFor($event->action) === 'info',
                            'bg-sunken text-ink-muted' => $toneFor($event->action) === 'neutral',
                        ])>
                            <x-dynamic-component :component="'heroicon-o-'.$iconFor($event->action)" class="h-4 w-4" />
                        </span>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-baseline gap-x-2">
                                <p class="text-sm font-medium text-ink">
                                    {{ \Illuminate\Support\Str::of($event->action)->replace(['.', '_'], ' ')->ucfirst() }}
                                </p>
                                <p class="text-xs text-ink-muted">
                                    {{ \Illuminate\Support\Str::of($event->entity_type)->replace('_', ' ') }} #{{ $event->entity_id }}
                                </p>
                            </div>

                            <p class="numeric mt-0.5 text-xs text-ink-muted">
                                {{ $event->actor?->user?->name ?? ($event->actor_role === \App\Models\AuditEvent::PLATFORM_ROLE ? 'Platform admin' : 'system') }}
                                <span class="opacity-60">({{ $event->actor_role }})</span>
                                &middot; {{ $event->created_at?->timezone($organisation->timezone)->format('d M Y H:i') }}
                            </p>

                            @if (! empty($event->metadata['reason']))
                                <p class="mt-1 rounded-md bg-raised px-2 py-1 text-xs text-ink-soft">
                                    Reason: {{ $event->metadata['reason'] }}
                                </p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>

            <x-ui.load-more :list="$events" noun="entries" />
        @endif
    </x-ui.card>
</div>
