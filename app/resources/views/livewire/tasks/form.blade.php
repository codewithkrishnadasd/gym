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

                    <x-ui.textarea class="sm:col-span-2" wire:model="description" name="description" label="Description" rows="4"
                        placeholder="Optional — context, links, what done looks like">{{ $description }}</x-ui.textarea>

                    <x-ui.input wire:model="startDate" name="startDate" label="Start date" type="date" hint="Optional." />
                    <x-ui.input wire:model="dueDate" name="dueDate" label="Due date" type="date" hint="Optional." />
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
