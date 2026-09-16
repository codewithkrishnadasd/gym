<div>
    <x-ui.flash />

    <x-ui.page-header title="Tasks" description="Work for the team, by category. Open every task to move its parts along.">
        <x-slot:actions>
            @if ($canManageCategories)
                <x-ui.button icon="cog-6-tooth" :href="route('tenant.settings.organisation', ['tab' => 'tasks'])" wire:navigate>Settings</x-ui.button>
            @endif
        </x-slot:actions>
    </x-ui.page-header>

    {{-- The page's one action, as the floating button every page shares. --}}
    @can('create', \App\Models\Task::class)
        <x-ui.fab :href="route('tenant.tasks.create')" label="New task" symbol="+" />
    @endcan

    {{-- All figures follow the filters below (except open/done), so
         narrowing to a category or a person shows that slice's progress. --}}
    <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
        <x-ui.stat label="Completed" :value="$donePercent.'%'" icon="check-badge"
            :tone="$totalCount > 0 && $doneCount === $totalCount ? 'positive' : 'accent'"
            :hint="$doneCount.' of '.$totalCount.' '.($totalCount === 1 ? 'task' : 'tasks').($partsPercent !== null ? ' · parts '.$partsPercent.'% done' : '')" />
        <x-ui.stat label="Open" :value="$openCount" icon="check-circle" tone="neutral" />
        <x-ui.stat label="Due today" :value="$dueTodayCount" icon="calendar" :tone="$dueTodayCount > 0 ? 'caution' : 'neutral'" />
        <x-ui.stat label="Overdue" :value="$overdueCount" icon="exclamation-triangle" :tone="$overdueCount > 0 ? 'critical' : 'positive'" />
    </div>

    <x-ui.card :padded="false">
        <x-ui.filters search="search" placeholder="Search tasks…">
            <x-ui.filter-select wire:model.live="category" label="Category">
                <option value="">All categories</option>
                @foreach ($categories as $categoryOption)
                    <option value="{{ $categoryOption->id }}">{{ $categoryOption->name }}</option>
                @endforeach
            </x-ui.filter-select>

            @if ($clubs->isNotEmpty())
                <x-ui.filter-select wire:model.live="club" :label="$organisation->term('club_singular')">
                    <option value="">All {{ strtolower($organisation->term('club_plural')) }}</option>
                    @foreach ($clubs as $clubOption)
                        <option value="{{ $clubOption->id }}">{{ $clubOption->name }}</option>
                    @endforeach
                </x-ui.filter-select>
            @endif

            @if ($statuses->isNotEmpty())
                <x-ui.filter-select wire:model.live="status" label="Status">
                    <option value="">Any status</option>
                    @foreach ($statuses as $statusOption)
                        <option value="{{ $statusOption->id }}">{{ $statusOption->name }}</option>
                    @endforeach
                </x-ui.filter-select>
            @endif

            @if ($members->isNotEmpty())
                <x-ui.filter-select wire:model.live="member" :label="$organisation->term('member_singular')">
                    <option value="">Any {{ strtolower($organisation->term('member_singular')) }}</option>
                    @foreach ($members as $memberOption)
                        <option value="{{ $memberOption->id }}">{{ $memberOption->name }}</option>
                    @endforeach
                </x-ui.filter-select>
            @endif

            @if ($canFilterByPeople)
            <x-ui.filter-select wire:model.live="who" label="People">
                <option value="">Everyone</option>
                <option value="mine">Assigned to me</option>
                <option value="reported">Reported by me</option>
                <option value="mentioned">Mentioned me</option>
                <option value="unassigned">Unassigned</option>
                @if ($staff->isNotEmpty())
                    <optgroup label="Assigned to">
                        @foreach ($staff as $person)
                            <option value="staff:{{ $person->id }}">{{ $person->user?->name }}</option>
                        @endforeach
                    </optgroup>
                @endif
            </x-ui.filter-select>
            @endif

            <x-ui.filter-select wire:model.live="show" label="Show" default="open">
                <option value="open">Open</option>
                <option value="done">Done</option>
                <option value="all">All</option>
            </x-ui.filter-select>
        </x-ui.filters>

        <x-ui.list-loader />

        @if ($tasks->isEmpty())
            <x-ui.empty icon="check-circle" :title="$show === 'done' ? 'Nothing finished yet' : 'No tasks here'"
                :description="$categories->isEmpty() && $canManageCategories
                    ? 'Start by adding a task category with its statuses and sub-categories under Settings → Tasks.'
                    : ($search !== '' || $category !== '' || $status !== '' ? 'Try a different search or clear the filters.' : 'Add the first task for the team.')">
                <x-slot:actions>
                    @if ($categories->isEmpty() && $canManageCategories)
                        <x-ui.button variant="primary" size="sm" :href="route('tenant.settings.organisation', ['tab' => 'tasks'])" wire:navigate>Set up categories</x-ui.button>
                    @else
                        @can('create', \App\Models\Task::class)
                            <x-ui.button variant="primary" size="sm" :href="route('tenant.tasks.create')" wire:navigate>New task</x-ui.button>
                        @endcan
                    @endif
                </x-slot:actions>
            </x-ui.empty>
        @else
            <ul class="divide-y divide-[var(--c-hairline)]">
                @foreach ($tasks as $task)
                    @php
                        $total = $task->items->count();
                        $done = $task->items->filter(fn ($item) => $item->isDone())->count();
                        $overdue = $task->isOverdue($today);
                    @endphp
                    <li>
                        <a href="{{ route('tenant.tasks.show', $task) }}" wire:navigate
                            class="flex flex-col gap-2 p-4 transition hover:bg-raised sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <x-ui.reference :value="$organisation->reference('task', $task->id)" />
                                    <p @class(['truncate font-medium', 'text-ink' => ! $task->isDone(), 'text-ink-muted line-through' => $task->isDone()])>{{ $task->title }}</p>
                                    <x-tasks.status-chip :status="$task->status" />
                                </div>
                                <p class="mt-0.5 flex flex-wrap items-center gap-x-2 text-xs text-ink-muted">
                                    <span>{{ $task->category?->name }}</span>
                                    @if ($task->club && $organisation->usesClubs())
                                        <span>· {{ $task->club->name }}</span>
                                    @endif
                                    @if ($task->member)
                                        <span>· for {{ $task->member->name }}</span>
                                    @endif
                                    {{-- Everyone on it: task assignees and whoever holds a part. --}}
                                    @php $everyone = $task->people(); @endphp
                                    @if ($everyone->isNotEmpty())
                                        <span class="inline-flex items-center gap-1">
                                            · <span class="inline-flex -space-x-1.5">
                                                @foreach ($everyone->take(4) as $person)
                                                    <x-ui.avatar :name="$person->user?->name ?? '?'" size="xs" class="ring-2 ring-surface" />
                                                @endforeach
                                            </span>
                                            {{ $everyone->map(fn ($person) => $person->user?->name)->filter()->join(', ') }}
                                        </span>
                                    @endif
                                    @if ($task->due_date)
                                        <span @class(['font-medium text-critical' => $overdue])>
                                            · {{ $overdue ? 'Overdue' : 'Due' }} {{ $task->due_date->format('d M') }}
                                        </span>
                                    @endif
                                    @if ($task->start_date)
                                        <span>· from {{ $task->start_date->format('d M') }}</span>
                                    @endif
                                </p>
                            </div>

                            @if ($total > 0)
                                {{-- One segment per part, in order, in the colour of the
                                     part's current status — the whole task's state readable
                                     as a strip without opening it. --}}
                                @php $percent = (int) round($done / $total * 100); @endphp
                                <div class="flex shrink-0 items-center gap-2 sm:w-56">
                                    <div class="flex h-2 flex-1 gap-px overflow-hidden rounded-full bg-sunken" role="img"
                                        aria-label="{{ $task->items->map(fn ($item) => ($item->subCategory?->name ?? 'Part').': '.($item->status?->name ?? 'no status'))->join(', ') }}">
                                        @foreach ($task->items as $item)
                                            <span class="h-full flex-1 transition-colors" title="{{ $item->subCategory?->name }} — {{ $item->status?->name ?? 'No status' }}"
                                                style="background: {{ $item->status?->color ?? 'var(--c-hairline-strong)' }}; opacity: {{ $item->isDone() ? '1' : '0.75' }}"></span>
                                        @endforeach
                                    </div>
                                    <span class="numeric w-20 text-right text-xs text-ink-muted"><span class="font-semibold text-ink">{{ $percent }}%</span> · {{ $done }}/{{ $total }}</span>
                                </div>
                            @elseif ($task->isDone())
                                <span class="numeric shrink-0 text-xs font-semibold text-positive">100%</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="border-t border-hairline p-3">{{ $tasks->links() }}</div>
        @endif
    </x-ui.card>
</div>
