<div>
    <x-ui.page-header :title="$task ? 'Edit task' : 'New task'" :back="$task ? route('tenant.tasks.show', $task) : route('tenant.tasks.index')"
        :back-label="$task ? 'Task' : 'Tasks'" />

    <form wire:submit="save" class="grid gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-ui.card title="Task">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input class="sm:col-span-2" wire:model="title" name="title" label="Title" required
                        placeholder="What needs doing?" autofocus />

                    @if ($task)
                        <x-ui.field label="Category" class="sm:col-span-2">
                            <p class="text-sm text-ink">{{ $category?->name ?? '—' }}</p>
                            <p class="mt-0.5 text-xs text-ink-muted">Fixed once the task exists — its statuses and parts come from it.</p>
                        </x-ui.field>
                    @else
                        <x-ui.select class="sm:col-span-2" wire:model.live="categoryId" name="categoryId" label="Category" required>
                            <option value="">Choose a category…</option>
                            @foreach ($categories as $option)
                                <option value="{{ $option->id }}">{{ $option->name }}</option>
                            @endforeach
                        </x-ui.select>
                    @endif

                    @if ($category && $category->statuses->isNotEmpty())
                        <div class="sm:col-span-2">
                            <x-ui.field label="Status" name="statusId">
                                {{-- Statuses as their own chips rather than a select, so the
                                     colour that identifies each one is part of the choice. --}}
                                <div class="flex flex-wrap gap-1.5" role="radiogroup" aria-label="Status">
                                    @foreach ($category->statuses as $status)
                                        <button type="button" wire:click="$set('statusId', {{ $status->id }})" role="radio"
                                            aria-checked="{{ $statusId === $status->id ? 'true' : 'false' }}"
                                            @class(['rounded-full px-3 py-1.5 text-sm font-medium transition ring-offset-2 ring-offset-[var(--c-surface)]',
                                                'ring-2 ring-[var(--c-ink)] shadow-sm' => $statusId === $status->id,
                                                'opacity-60 hover:opacity-100' => $statusId !== $status->id])
                                            style="{{ $status->style() }}">{{ $status->name }}</button>
                                    @endforeach
                                </div>
                            </x-ui.field>
                        </div>
                    @endif

                    <x-ui.markdown-editor class="sm:col-span-2" wire:model="description" name="description" label="Description"
                        hint="Optional. Markdown — switch to Preview to see it laid out; you can type in either view."
                        placeholder="Context, links, what done looks like…" rows="7" />

                    <x-ui.input wire:model="startDate" name="startDate" label="Start date" type="date" hint="Optional." />
                    <x-ui.input wire:model="dueDate" name="dueDate" label="Due date" type="date" hint="Optional." />

                    @if ($organisation->usesClubs() && $clubs->isNotEmpty())
                        <x-ui.select wire:model="clubId" name="clubId" :label="$organisation->term('club_singular')"
                            hint="Optional. Where this task belongs, so the list can be narrowed to a location.">
                            <option value="">Not tied to one {{ strtolower($organisation->term('club_singular')) }}</option>
                            @foreach ($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </x-ui.select>
                    @endif
                </div>
            </x-ui.card>

            <x-ui.card title="People" :description="$organisation->hasFeature('members')
                ? 'Who is doing this, and which '.strtolower($organisation->term('member_singular')).' it concerns. Both optional. Assignees and the person who raised it can see the task.'
                : 'Who is doing this. Optional. Assignees and the person who raised it can see the task.'">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-ui.field label="Assigned to" name="assigneeIds" :hint="$assignees->isEmpty() ? 'Optional. Add as many people as needed.' : null">
                        @if (! $hasPeople)
                            <p class="text-sm text-ink-muted">No active {{ strtolower($organisation->term('user_plural')) }} to assign.</p>
                        @else
                            {{-- Same picker as the member: search, choose, and each
                                 person drops into the list below with a remove. --}}
                            <div class="relative">
                                <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-muted" />
                                <input type="search" wire:model.live.debounce.300ms="assigneeSearch" placeholder="Search by name or phone…"
                                    class="min-h-[40px] w-full rounded-lg border border-hairline-strong bg-surface py-2 pl-9 pr-3 text-sm text-ink placeholder:text-ink-muted focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25">
                                <div wire:loading wire:target="assigneeSearch" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-muted"><x-ui.spinner size="xs" /></div>
                            </div>

                            @if ($assigneeResults->isNotEmpty())
                                <ul class="mt-1.5 divide-y divide-[var(--c-hairline)] overflow-hidden rounded-lg border border-hairline">
                                    @foreach ($assigneeResults as $result)
                                        <li>
                                            <button type="button" wire:click="addAssignee({{ $result->id }})" class="flex w-full items-center gap-2.5 p-2.5 text-left transition hover:bg-list-hover">
                                                <x-ui.avatar :name="$result->user?->name ?? '?'" size="sm" />
                                                <span class="min-w-0 flex-1">
                                                    <span class="block truncate text-sm font-medium text-ink">{{ $result->user?->name }}</span>
                                                    <span class="block truncate text-xs text-ink-muted">{{ $result->isAdmin() ? 'Administrator' : $organisation->term('user_singular') }}{{ $result->user?->phone ? ' · '.$result->user->phone : '' }}</span>
                                                </span>
                                                <x-heroicon-o-plus class="h-4 w-4 shrink-0 text-accent" />
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            @elseif ($assigneeSearch !== '')
                                <p class="mt-1.5 text-xs text-ink-muted">No one matches “{{ $assigneeSearch }}”.</p>
                            @endif

                            @if ($assignees->isNotEmpty())
                                <ul class="mt-2 space-y-1.5">
                                    @foreach ($assignees as $person)
                                        <li class="flex items-center justify-between gap-3 rounded-lg border border-hairline bg-raised p-2.5" wire:key="assignee-{{ $person->id }}">
                                            <div class="flex min-w-0 items-center gap-2.5">
                                                <x-ui.avatar :name="$person->user?->name ?? '?'" size="sm" tone="accent" />
                                                <div class="min-w-0">
                                                    <p class="truncate text-sm font-medium text-ink">{{ $person->user?->name }}</p>
                                                    <p class="truncate text-xs text-ink-muted">{{ $organisation->reference('staff', $person->id) }} · {{ $person->isAdmin() ? 'Administrator' : $organisation->term('user_singular') }}</p>
                                                </div>
                                            </div>
                                            <x-ui.button size="sm" variant="ghost" type="button" icon="x-mark" wire:click="removeAssignee({{ $person->id }})" aria-label="Remove {{ $person->user?->name }}">Remove</x-ui.button>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif
                    </x-ui.field>

                    @feature('members')
                    <x-ui.field :label="$organisation->term('member_singular')" name="memberId">
                        @if ($selectedMember)
                            <div class="flex items-center justify-between gap-3 rounded-lg border border-hairline bg-raised p-2.5">
                                <div class="flex min-w-0 items-center gap-2.5">
                                    <x-ui.avatar :name="$selectedMember->name" size="sm" tone="accent" />
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-medium text-ink">{{ $selectedMember->name }}</p>
                                        <p class="truncate text-xs text-ink-muted">{{ collect([$organisation->reference('member', $selectedMember->id), $organisation->usesClubs() ? ($selectedMember->primaryClub?->name ?? 'No club') : null])->filter()->join(' · ') }}</p>
                                    </div>
                                </div>
                                <x-ui.button size="sm" variant="ghost" type="button" wire:click="clearMember">Change</x-ui.button>
                            </div>
                        @else
                            <div class="relative">
                                <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-muted" />
                                <input type="search" wire:model.live.debounce.300ms="memberSearch" placeholder="Search by name or phone…"
                                    class="min-h-[40px] w-full rounded-lg border border-hairline-strong bg-surface py-2 pl-9 pr-3 text-sm text-ink placeholder:text-ink-muted focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25">
                            </div>
                            @if ($memberResults->isNotEmpty())
                                <ul class="mt-1.5 divide-y divide-[var(--c-hairline)] overflow-hidden rounded-lg border border-hairline">
                                    @foreach ($memberResults as $result)
                                        <li>
                                            <button type="button" wire:click="selectMember({{ $result->id }})" class="flex w-full items-center gap-2.5 p-2.5 text-left transition hover:bg-list-hover">
                                                <x-ui.avatar :name="$result->name" size="sm" />
                                                <span class="min-w-0">
                                                    <span class="block truncate text-sm font-medium text-ink">{{ $result->name }}</span>
                                                    <span class="block truncate text-xs text-ink-muted">{{ collect([$result->phone, $organisation->usesClubs() ? ($result->primaryClub?->name ?? 'No club') : null])->filter()->join(' · ') }}</span>
                                                </span>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            @elseif (mb_strlen($memberSearch) >= 2)
                                <p class="mt-1.5 text-xs text-ink-muted">No {{ strtolower($organisation->term('member_plural')) }} match “{{ $memberSearch }}”.</p>
                            @else
                                <p class="mt-1.5 text-xs text-ink-muted">Leave empty if this is not about one {{ strtolower($organisation->term('member_singular')) }}.</p>
                            @endif
                        @endif
                    </x-ui.field>
                    @endfeature
                </div>
            </x-ui.card>

            @if (! $task && $category && $category->subCategories->isNotEmpty())
                <x-ui.card title="Parts of this task"
                    description="Each sub-category of the chosen category becomes a line on the task with its own status. They start in the first status shown.">
                    <ul class="divide-y divide-[var(--c-hairline)]">
                        @foreach ($category->subCategories as $subCategory)
                            <li class="flex flex-wrap items-center justify-between gap-2 py-2.5">
                                <span class="text-sm font-medium text-ink">{{ $subCategory->name }}</span>
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($subCategory->statuses as $index => $status)
                                        <x-tasks.status-chip :status="$status" size="xs" :class="$index === 0 ? '' : 'opacity-50'" />
                                    @empty
                                        <span class="text-xs text-ink-muted">No statuses set</span>
                                    @endforelse
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </x-ui.card>
            @endif
        </div>

        <div class="space-y-5">
            <x-ui.card title="Save">
                <div class="flex flex-col gap-2">
                    <x-ui.button type="submit" variant="primary" size="lg" wire:loading.attr="disabled" wire:target="save"
                        :disabled="$categories->isEmpty()">
                        <span wire:loading.remove wire:target="save">{{ $task ? 'Save changes' : 'Create task' }}</span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Saving…</span>
                    </x-ui.button>
                    <x-ui.button :href="$task ? route('tenant.tasks.show', $task) : route('tenant.tasks.index')" wire:navigate variant="ghost">Cancel</x-ui.button>
                </div>

                @if ($categories->isEmpty())
                    <div class="mt-3">
                        <x-ui.alert tone="caution" title="No task categories yet">
                            An administrator needs to add at least one category under Settings → Tasks before tasks can be created.
                        </x-ui.alert>
                    </div>
                @endif
            </x-ui.card>
        </div>
    </form>
</div>
