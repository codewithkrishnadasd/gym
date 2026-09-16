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
                <x-ui.card title="Status" :description="$items->isNotEmpty() ? (int) round($doneCount / $items->count() * 100).'% of parts done' : null" collapsible>
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

            @if ($task->description)
                <x-ui.card title="Description" collapsible :open="false">
                    {{-- Rendered server-side with raw HTML stripped (Task::descriptionHtml). --}}
                    <div class="prose-task text-sm text-ink-soft">{!! $task->descriptionHtml() !!}</div>
                </x-ui.card>
            @endif

            @if ($items->isNotEmpty())
                @php $percent = (int) round($doneCount / max(1, $items->count()) * 100); @endphp
                <x-ui.card :padded="false" title="Parts" :description="$percent.'% complete · '.$doneCount.' of '.$items->count().' done'" collapsible :open="false">
                    <x-slot:actions>
                        <div class="flex w-36 items-center gap-2">
                            <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-sunken">
                                <div class="h-full rounded-full bg-positive transition-all duration-500" style="width: {{ $percent }}%"></div>
                            </div>
                            <span class="numeric text-sm font-semibold text-ink">{{ $percent }}%</span>
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
                                        {{-- One person per part; the select shows an avatar chip
                                             for whoever holds it and saves as soon as it changes. --}}
                                        @can('update', $task)
                                            <label class="mt-1 inline-flex items-center gap-1.5 text-xs">
                                                @if ($item->assignee)
                                                    <x-ui.avatar :name="$item->assignee->user?->name ?? '?'" size="xs" tone="accent" />
                                                @else
                                                    <x-heroicon-o-user-plus class="h-3.5 w-3.5 text-ink-muted" />
                                                @endif
                                                <span class="sr-only">Assigned to</span>
                                                <select wire:change="setItemAssignee({{ $item->id }}, $event.target.value || null)"
                                                    class="max-w-[11rem] cursor-pointer appearance-none rounded-md border border-transparent bg-transparent py-0.5 pl-1 pr-5 text-xs font-medium text-ink-soft transition hover:border-hairline-strong hover:bg-surface focus:border-accent focus:outline-none"
                                                    style="background-image: none;">
                                                    <option value="" @selected($item->assignee_id === null)>Unassigned</option>
                                                    @foreach ($people as $person)
                                                        <option value="{{ $person->id }}" @selected($item->assignee_id === $person->id)>{{ $person->user?->name }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        @elseif ($item->assignee)
                                            <p class="mt-1 inline-flex items-center gap-1.5 text-xs text-ink-soft">
                                                <x-ui.avatar :name="$item->assignee->user?->name ?? '?'" size="xs" tone="accent" /> {{ $item->assignee->user?->name }}
                                            </p>
                                        @endcan
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

            <x-ui.card title="Activity" :description="$activity->count().' '.($activity->count() === 1 ? 'entry' : 'entries').' — comments and every change, with who made it'" collapsible :open="false">
                @if ($activity->isEmpty())
                    <p class="text-sm text-ink-muted">Nothing yet.</p>
                @else
                    <ol class="relative space-y-4 border-l border-hairline pl-5">
                        @foreach ($activity as $entry)
                            <li class="relative" @if ($entry['comment_id']) wire:key="comment-{{ $entry['comment_id'] }}" @endif>
                                {{-- Marker on the rail: filled for comments, hollow for changes. --}}
                                <span @class(['absolute -left-[26px] top-1 h-3 w-3 rounded-full border-2 border-surface', 'bg-accent' => $entry['kind'] === 'comment', 'bg-hairline-strong' => $entry['kind'] !== 'comment'])></span>
                                <div class="flex flex-wrap items-baseline gap-x-2 text-sm">
                                    <span class="font-medium text-ink">{{ $entry['actor'] }}</span>
                                    @if ($entry['kind'] === 'change')
                                        <span class="text-ink-soft">{{ $entry['text'] }}</span>
                                    @else
                                        <span class="text-ink-muted">commented</span>
                                    @endif
                                    <span class="numeric text-xs text-ink-muted" title="{{ $entry['at']->timezone($organisation->timezone)->format('d M Y, H:i') }}">{{ $entry['at']->diffForHumans() }}</span>
                                    @if ($entry['kind'] === 'comment' && ($me->isAdmin() || $entry['author_id'] === $me->id))
                                        <button type="button" wire:click="deleteComment({{ $entry['comment_id'] }})" class="ml-auto text-xs text-ink-muted hover:text-critical"
                                            data-confirm-title="Delete this comment?" data-confirm-action="Delete" data-confirm="Delete this comment? This cannot be undone.">Delete</button>
                                    @endif
                                </div>
                                @if ($entry['kind'] === 'comment')
                                    <div class="mt-1.5 rounded-lg border border-hairline bg-raised px-3 py-2 text-sm leading-relaxed text-ink">{!! $entry['html'] !!}</div>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                @endif

            </x-ui.card>

            {{-- Commenting stays in reach even while the history above is
                 folded away. --}}
            <x-ui.card title="Comment" description="Type @ to mention a colleague — mentioned people see this task on their list.">
            @can('view', $task)
                <form wire:submit="addComment">
                    {{-- "@" opens the colleague picker; see resources/js/mention-box.js.
                         State lives on a plain div: @js() inside an x-component
                         attribute is not compiled by Livewire's Blade pass. --}}
                    <div x-data="mentionBox({ people: @js($mentionable) })" class="relative">
                        <x-ui.field name="comment">
                            <textarea x-ref="box" wire:model="comment" rows="3" placeholder="Write a comment… use @ to mention someone"
                                x-on:input="onInput" x-on:keydown="onKeydown" x-on:blur="setTimeout(() => close(), 150)"
                                class="min-h-[80px] w-full rounded-lg border border-hairline-strong bg-surface px-3 py-2 text-sm text-ink placeholder:text-ink-muted focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25"></textarea>
                        </x-ui.field>

                        <ul x-show="open" x-cloak class="absolute left-0 z-20 mt-1 w-72 overflow-hidden rounded-lg border border-hairline bg-surface py-1 elevate-lg" role="listbox">
                            <template x-for="(person, i) in matches" :key="person.id">
                                <li>
                                    <button type="button" x-on:mousedown.prevent="pick(person)" role="option" :aria-selected="i === index"
                                        :class="i === index ? 'bg-accent-soft text-accent-ink' : 'text-ink hover:bg-list-hover'"
                                        class="flex w-full items-center gap-2 px-3 py-1.5 text-left text-sm">
                                        <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-sunken text-[10px] font-semibold uppercase text-ink-soft" x-text="person.name.slice(0, 1)"></span>
                                        <span x-text="person.name"></span>
                                    </button>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <div class="mt-2 flex justify-end">
                        <x-ui.button type="submit" variant="primary" size="sm" icon="chat-bubble-left" wire:loading.attr="disabled" wire:target="addComment">
                            <span wire:loading.remove wire:target="addComment">Comment</span>
                            <span wire:loading wire:target="addComment" class="inline-flex items-center gap-1.5"><x-ui.spinner size="xs" /> Posting…</span>
                        </x-ui.button>
                    </div>
                </form>
            @endcan
            </x-ui.card>


        </div>

        <div class="space-y-5">
            @php
                $dueReminders = $task->reminders->filter(fn ($reminder) => $reminder->isDue($today));
                $nextReminder = $task->reminders->first(fn ($reminder) => ! $reminder->isDue($today));
            @endphp
            <x-ui.card title="Reminders"
                :description="$task->reminders->isEmpty() ? 'None set' : ($dueReminders->isNotEmpty() ? $dueReminders->count().' due now' : 'Next: '.$nextReminder?->remind_on->format('d M Y'))"
                collapsible :open="$dueReminders->isNotEmpty()">
                @if ($task->reminders->isNotEmpty())
                    <ul class="mb-4 divide-y divide-[var(--c-hairline)]">
                        @foreach ($task->reminders as $reminder)
                            @php $due = $reminder->isDue($today); @endphp
                            <li class="flex items-start justify-between gap-3 py-2" wire:key="reminder-{{ $reminder->id }}">
                                <div class="flex min-w-0 items-start gap-2.5">
                                    <span @class(['mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full', 'bg-caution-soft text-caution' => $due && ! $task->isDone(), 'bg-sunken text-ink-muted' => ! $due || $task->isDone()])>
                                        <x-heroicon-o-bell-alert class="h-3.5 w-3.5" />
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-ink">{{ $reminder->label }}</p>
                                        <p class="numeric text-xs text-ink-muted">
                                            {{ $reminder->remind_on->format('d M Y') }}
                                            @if ($due && ! $task->isDone())
                                                · <span class="font-medium text-caution">{{ $reminder->remind_on->isToday() ? 'today' : 'since '.$reminder->remind_on->diffForHumans() }}</span>
                                            @endif
                                            · by {{ $reminder->createdBy?->user?->name ?? '—' }}
                                        </p>
                                    </div>
                                </div>
                                @can('update', $task)
                                    <button type="button" wire:click="removeReminder({{ $reminder->id }})" class="shrink-0 rounded p-1 text-ink-muted hover:bg-critical-soft hover:text-critical" aria-label="Remove reminder">
                                        <x-heroicon-o-x-mark class="h-4 w-4" />
                                    </button>
                                @endcan
                            </li>
                        @endforeach
                    </ul>
                @endif

                @can('update', $task)
                    <form wire:submit="addReminder" class="space-y-2">
                        <x-ui.input wire:model="reminderLabel" name="reminderLabel" label="Remind about" placeholder="e.g. Chase the supplier" />
                        <div class="flex items-end gap-2">
                            <x-ui.input class="flex-1" wire:model="reminderDate" name="reminderDate" label="On" type="date" />
                            <x-ui.button type="submit" variant="primary" size="md" icon="plus" wire:loading.attr="disabled" wire:target="addReminder">Add</x-ui.button>
                        </div>
                        <p class="text-xs text-ink-muted">From that day until the task is done, everyone involved sees it when they open the app.</p>
                    </form>
                @endcan
            </x-ui.card>

            <x-ui.card title="People" collapsible :open="false">
                <dl class="grid gap-x-6">
                    <x-ui.definition label="Working on this">
                        @php $everyone = $task->people(); @endphp
                        @if ($everyone->isEmpty())
                            <span class="text-ink-muted">Nobody yet</span>
                        @else
                            <ul class="space-y-1.5">
                                @foreach ($everyone as $person)
                                    @php $parts = $task->items->where('assignee_id', $person->id)->map(fn ($item) => $item->subCategory?->name)->filter(); @endphp
                                    <li class="flex items-start gap-2">
                                        <x-ui.avatar :name="$person->user?->name ?? '?'" size="sm" />
                                        <span class="min-w-0">
                                            <span class="block text-sm text-ink">{{ $person->user?->name }}</span>
                                            <span class="block text-xs text-ink-muted">
                                                {{ $task->assignees->contains('id', $person->id) ? 'Whole task' : '' }}{{ $task->assignees->contains('id', $person->id) && $parts->isNotEmpty() ? ' · ' : '' }}{{ $parts->join(', ') }}
                                            </span>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </x-ui.definition>
                    @feature('members')
                    <x-ui.definition :label="$organisation->term('member_singular')">
                        @if ($task->member)
                            <a href="{{ route('tenant.members.show', $task->member) }}" wire:navigate class="inline-flex items-center gap-2 text-accent hover:underline">
                                <x-ui.avatar :name="$task->member->name" size="sm" tone="accent" /> {{ $task->member->name }}
                            </a>
                        @else
                            <span class="text-ink-muted">Not about one {{ strtolower($organisation->term('member_singular')) }}</span>
                        @endif
                    </x-ui.definition>
                    @endfeature
                    @if ($organisation->usesClubs())
                        <x-ui.definition :label="$organisation->term('club_singular')" :value="$task->club?->name ?? 'Not tied to one'" />
                    @endif
                    <x-ui.definition label="Reported by" :value="$task->createdBy?->user?->name ?? '—'" />
                </dl>
            </x-ui.card>

            <x-ui.card title="Details" collapsible :open="false">
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
