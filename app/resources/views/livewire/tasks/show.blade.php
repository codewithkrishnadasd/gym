<div>
    <x-ui.flash />

    @php $overdue = $task->isOverdue($today); @endphp

    <x-ui.page-header :title="$task->title" :back="route('tenant.tasks.index')" back-label="Tasks"
        :description="collect([
            $organisation->reference('task', $task->id),
            $task->category?->name,
            $task->start_date ? 'from '.$task->start_date->format('d M Y') : null,
            $task->due_date ? ($overdue ? 'overdue since ' : 'due ').$task->due_date->format('d M Y') : null,
        ])->filter()->join(' · ')">
        <x-slot:actions>
            @can('update', $task)
                <x-ui.button icon="pencil-square" :href="route('tenant.tasks.edit', $task)" wire:navigate>Edit</x-ui.button>
            @endcan
            @can('delete', $task)
                <x-ui.button variant="danger" icon="trash" wire:click="delete"
                    data-confirm-title="Delete this task?" data-confirm-action="Delete"
                    data-confirm="Delete “{{ $task->title }}” and its parts? This cannot be undone.">Delete</x-ui.button>
            @endcan
        </x-slot:actions>
    </x-ui.page-header>

    <div class="grid gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            {{-- The task's own status, as a row of coloured chips. The current
                 one is lifted with a ring; the rest are dimmed until hovered. --}}
            @if ($task->category && $task->category->statuses->isNotEmpty())
                <x-ui.card title="Status">
                    <div class="flex flex-wrap gap-2" role="radiogroup" aria-label="Task status">
                        @foreach ($task->category->statuses as $status)
                            @php $current = $task->task_status_id === $status->id; @endphp
                            <button type="button" wire:click="setStatus({{ $status->id }})" role="radio" aria-checked="{{ $current ? 'true' : 'false' }}"
                                wire:loading.attr="disabled" wire:target="setStatus"
                                @class(['inline-flex items-center gap-1.5 rounded-full px-3.5 py-2 text-sm font-medium transition duration-200 ring-offset-2 ring-offset-[var(--c-surface)]',
                                    'ring-2 ring-[var(--c-ink)] shadow-md scale-[1.03]' => $current,
                                    'opacity-55 hover:opacity-100 hover:scale-[1.02]' => ! $current])
                                style="{{ $status->style() }}">
                                @if ($current)<x-heroicon-s-check class="h-4 w-4" />@endif
                                {{ $status->name }}
                            </button>
                        @endforeach
                    </div>
                    @if ($task->isDone())
                        <p class="mt-3 text-xs text-ink-muted">Completed {{ $task->completed_at?->timezone($organisation->timezone)->format('d M Y, H:i') }}.</p>
                    @endif
                </x-ui.card>
            @endif

            @if ($items->isNotEmpty())
                <x-ui.card :padded="false" title="Parts" :description="$doneCount.' of '.$items->count().' done'">
                    <x-slot:actions>
                        <div class="flex w-28 items-center gap-2">
                            <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-sunken">
                                <div class="h-full rounded-full bg-positive transition-all duration-500" style="width: {{ (int) round($doneCount / max(1, $items->count()) * 100) }}%"></div>
                            </div>
                        </div>
                    </x-slot:actions>

                    <ul class="divide-y divide-[var(--c-hairline)]">
                        @foreach ($items as $item)
                            @php $statuses = $item->subCategory?->statuses ?? collect(); @endphp
                            {{-- The row is tinted with its current status so the board
                                 reads as a colour map before a single word is read. --}}
                            <li class="relative flex flex-col gap-3 px-4 py-3.5 transition-colors duration-300 sm:flex-row sm:items-center sm:justify-between"
                                wire:key="item-{{ $item->id }}-{{ $item->task_status_id }}"
                                style="{{ $item->status ? 'background: color-mix(in srgb, '.$item->status->color.' 9%, transparent);' : '' }}">
                                <span class="absolute inset-y-0 left-0 w-1 rounded-r transition-colors duration-300"
                                    style="background: {{ $item->status?->color ?? 'var(--c-hairline-strong)' }}"></span>

                                <div class="flex min-w-0 items-center gap-2.5 pl-2">
                                    @if ($item->isDone())
                                        <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full" style="{{ $item->status?->style() }}">
                                            <x-heroicon-s-check class="h-3.5 w-3.5" />
                                        </span>
                                    @else
                                        <span class="h-6 w-6 shrink-0 rounded-full border-2" style="border-color: {{ $item->status?->color ?? 'var(--c-hairline-strong)' }}"></span>
                                    @endif
                                    <div class="min-w-0">
                                        <p @class(['text-sm font-medium', 'text-ink' => ! $item->isDone(), 'text-ink-soft' => $item->isDone()])>{{ $item->subCategory?->name }}</p>
                                        <p class="text-xs text-ink-muted">{{ $item->status?->name ?? 'No status' }}</p>
                                    </div>
                                </div>

                                @if ($statuses->isNotEmpty())
                                    @can('update', $task)
                                        {{-- Segmented control: one tap moves the part. The
                                             selected segment carries the status colour; the
                                             others show only a dot of theirs, so the row stays
                                             calm and the current state is unmistakable. --}}
                                        <div class="inline-flex max-w-full items-center gap-0.5 self-start overflow-x-auto rounded-full border border-hairline bg-surface p-0.5 shadow-sm scrollbar-none sm:self-auto"
                                            role="radiogroup" aria-label="Status for {{ $item->subCategory?->name }}">
                                            @foreach ($statuses as $status)
                                                @php $current = $item->task_status_id === $status->id; @endphp
                                                <button type="button" wire:click="setItemStatus({{ $item->id }}, {{ $status->id }})"
                                                    role="radio" aria-checked="{{ $current ? 'true' : 'false' }}" title="{{ $status->name }}"
                                                    wire:loading.attr="disabled" wire:target="setItemStatus({{ $item->id }}, {{ $status->id }})"
                                                    @class(['inline-flex min-h-[32px] items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 text-xs font-medium transition-all duration-200',
                                                        'shadow-sm' => $current,
                                                        'text-ink-soft hover:bg-sunken' => ! $current])
                                                    style="{{ $current ? $status->style() : '' }}">
                                                    @unless ($current)
                                                        <span class="h-2 w-2 rounded-full" style="background: {{ $status->color }}"></span>
                                                    @endunless
                                                    <span wire:loading.remove wire:target="setItemStatus({{ $item->id }}, {{ $status->id }})">{{ $status->name }}</span>
                                                    <span wire:loading wire:target="setItemStatus({{ $item->id }}, {{ $status->id }})"><x-ui.spinner size="xs" /></span>
                                                </button>
                                            @endforeach
                                        </div>
                                    @else
                                        <x-tasks.status-chip :status="$item->status" />
                                    @endcan
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </x-ui.card>
            @endif

            @if ($task->description)
                <x-ui.card title="Description">
                    {{-- Rendered server-side with raw HTML stripped (Task::descriptionHtml). --}}
                    <div class="prose-task text-sm text-ink-soft">{!! $task->descriptionHtml() !!}</div>
                </x-ui.card>
            @endif
        </div>

        <div class="space-y-5">
            <x-ui.card title="People">
                <dl class="grid gap-x-6">
                    <x-ui.definition label="Assigned to">
                        @if ($task->assignees->isEmpty())
                            <span class="text-ink-muted">Nobody yet</span>
                        @else
                            <ul class="space-y-1.5">
                                @foreach ($task->assignees as $person)
                                    <li class="flex items-center gap-2">
                                        <x-ui.avatar :name="$person->user?->name ?? '?'" size="sm" />
                                        <span class="text-sm text-ink">{{ $person->user?->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </x-ui.definition>
                    <x-ui.definition :label="$organisation->term('member_singular')">
                        @if ($task->member)
                            <a href="{{ route('tenant.members.show', $task->member) }}" wire:navigate class="inline-flex items-center gap-2 text-accent hover:underline">
                                <x-ui.avatar :name="$task->member->name" size="sm" tone="accent" /> {{ $task->member->name }}
                            </a>
                        @else
                            <span class="text-ink-muted">Not about one {{ strtolower($organisation->term('member_singular')) }}</span>
                        @endif
                    </x-ui.definition>
                    <x-ui.definition label="Reported by" :value="$task->createdBy?->user?->name ?? '—'" />
                </dl>
            </x-ui.card>

            <x-ui.card title="Details">
                <dl class="grid gap-x-6">
                    <x-ui.definition label="Reference" :value="$organisation->reference('task', $task->id)" />
                    <x-ui.definition label="Category" :value="$task->category?->name ?? '—'" />
                    <x-ui.definition label="Start date" :value="$task->start_date?->format('d M Y') ?? 'Not set'" />
                    <x-ui.definition label="Due date">
                        @if ($task->due_date)
                            <span @class(['font-medium text-critical' => $overdue])>{{ $task->due_date->format('d M Y') }}</span>
                            @if ($overdue)
                                <x-ui.badge tone="critical" class="ml-1">Overdue</x-ui.badge>
                            @endif
                        @else
                            Not set
                        @endif
                    </x-ui.definition>
                    <x-ui.definition label="Created" :value="$task->created_at?->timezone($organisation->timezone)->format('d M Y, H:i') ?? '—'" />
                </dl>
            </x-ui.card>
        </div>
    </div>
</div>
