<div>
    @error('sub')
        <div class="mb-4"><x-ui.alert tone="critical">{{ $message }}</x-ui.alert></div>
    @enderror
    @error('status')
        <div class="mb-4"><x-ui.alert tone="critical">{{ $message }}</x-ui.alert></div>
    @enderror

    <div class="grid gap-5 lg:grid-cols-3">
        {{-- Categories --}}
        <x-ui.card :padded="false" title="Categories" description="Each kind of task the team does.">
            <x-slot:actions>
                <x-ui.button size="sm" variant="primary" icon="plus" wire:click="startCreateCategory">Add</x-ui.button>
            </x-slot:actions>

            @if ($categories->isEmpty())
                <x-ui.empty icon="squares-plus" title="No categories yet"
                    description="Add one to define its statuses and the parts every task of that kind is made of." />
            @else
                <ul class="divide-y divide-[var(--c-hairline)]">
                    @foreach ($categories as $category)
                        <li>
                            <button type="button" wire:click="select({{ $category->id }})"
                                @class(['flex w-full items-center justify-between gap-2 px-4 py-3 text-left transition',
                                    'bg-accent-soft' => $selected?->id === $category->id,
                                    'hover:bg-raised' => $selected?->id !== $category->id])>
                                <span class="min-w-0">
                                    <span @class(['block truncate text-sm font-medium', 'text-ink' => $category->isActive(), 'text-ink-muted line-through' => ! $category->isActive()])>{{ $category->name }}</span>
                                    <span class="block text-xs text-ink-muted">{{ $category->tasks_count }} {{ $category->tasks_count === 1 ? 'task' : 'tasks' }}</span>
                                </span>
                                @unless ($category->isActive())
                                    <x-ui.badge tone="neutral" :dot="false">Removed</x-ui.badge>
                                @endunless
                            </button>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.card>

        {{-- Selected category --}}
        <div class="space-y-5 lg:col-span-2">
            @if ($selected)
                <x-ui.card :title="$selected->name" description="Statuses a task of this kind moves through. The first is where new tasks start; a status marked as completing finishes the task.">
                    <x-slot:actions>
                        <div class="flex items-center gap-1">
                            @if ($selected->tasks()->count() > 0)
                                <x-ui.button size="sm" variant="ghost" icon="arrow-up-right" :href="route('tenant.tasks.index', ['category' => $selected->id, 'show' => 'all'])" wire:navigate>
                                    {{ $selected->tasks()->count() }} {{ $selected->tasks()->count() === 1 ? 'task' : 'tasks' }}
                                </x-ui.button>
                            @endif
                            <x-ui.button size="sm" variant="ghost" icon="pencil-square" wire:click="startEditCategory({{ $selected->id }})">Rename</x-ui.button>
                            @if ($selected->isActive())
                                <x-ui.button size="sm" variant="ghost" icon="trash" wire:click="removeCategory({{ $selected->id }})"
                                    data-confirm-title="Remove this category?" data-confirm-action="Remove"
                                    data-confirm="“{{ $selected->name }}” stops being offered for new tasks. Existing tasks keep it and can still be worked.">Remove</x-ui.button>
                            @else
                                <x-ui.button size="sm" variant="ghost" icon="arrow-uturn-left" wire:click="restoreCategory({{ $selected->id }})">Restore</x-ui.button>
                            @endif
                        </div>
                    </x-slot:actions>

                    <x-tasks.status-editor :statuses="$selected->statuses" owner="category" :owner-id="$selected->id" />
                </x-ui.card>

                <x-ui.card title="Sub-categories" description="The parts every task in this category is made of. Each has its own statuses, moved one by one on the task.">
                    <x-slot:actions>
                        <x-ui.button size="sm" variant="primary" icon="plus" wire:click="startCreateSub">Add sub-category</x-ui.button>
                    </x-slot:actions>

                    @if ($selected->subCategories->isEmpty())
                        <p class="text-sm text-ink-muted">None yet. Tasks in this category will have only their overall status.</p>
                    @else
                        <ul class="divide-y divide-[var(--c-hairline)]">
                            @foreach ($selected->subCategories as $sub)
                                <li class="py-3">
                                    <div class="mb-2 flex items-center justify-between gap-2">
                                        <p class="text-sm font-medium text-ink">{{ $sub->name }}</p>
                                        <div class="flex items-center gap-1">
                                            <x-ui.button size="sm" variant="ghost" icon="pencil-square" wire:click="startEditSub({{ $sub->id }})">Rename</x-ui.button>
                                            <x-ui.button size="sm" variant="ghost" icon="trash" wire:click="deleteSub({{ $sub->id }})"
                                                data-confirm-title="Remove this sub-category?" data-confirm-action="Remove"
                                                data-confirm="Remove “{{ $sub->name }}” from this category? Only possible while no task has it as a part.">Remove</x-ui.button>
                                        </div>
                                    </div>
                                    <x-tasks.status-editor :statuses="$sub->statuses" owner="sub" :owner-id="$sub->id" compact />
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </x-ui.card>
            @else
                <x-ui.card>
                    <x-ui.empty icon="cursor-arrow-rays" title="Pick a category" description="Choose a category on the left to edit its statuses and sub-categories." />
                </x-ui.card>
            @endif
        </div>
    </div>

    <x-ui.modal name="task-category" :title="$categoryEditingId ? 'Rename category' : 'New category'"
        :description="$categoryEditingId ? null : 'It starts with To do → In progress → Done; change the statuses however you like afterwards.'">
        <x-ui.input wire:model="categoryName" name="categoryName" label="Name" required placeholder="e.g. Equipment maintenance" />
        <x-slot:footer>
            <x-ui.button variant="ghost" x-on:click="$dispatch('close-modal', 'task-category')">Cancel</x-ui.button>
            <x-ui.button variant="primary" wire:click="saveCategory" wire:loading.attr="disabled" wire:target="saveCategory">Save</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    <x-ui.modal name="task-sub-category" :title="$subEditingId ? 'Rename sub-category' : 'New sub-category'"
        :description="$subEditingId ? null : 'It starts with Pending → Done; adjust the statuses afterwards.'">
        <x-ui.input wire:model="subName" name="subName" label="Name" required placeholder="e.g. Safety check" />
        <x-slot:footer>
            <x-ui.button variant="ghost" x-on:click="$dispatch('close-modal', 'task-sub-category')">Cancel</x-ui.button>
            <x-ui.button variant="primary" wire:click="saveSub" wire:loading.attr="disabled" wire:target="saveSub">Save</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    <x-ui.modal name="task-status" :title="$statusEditingId ? 'Edit status' : 'New status'">
        <div class="space-y-4">
            <x-ui.input wire:model.live.debounce.300ms="statusName" name="statusName" label="Name" required placeholder="e.g. Waiting on parts" />

            <x-ui.field label="Colour" name="statusColor" hint="The background of the status everywhere it appears. Text colour is chosen automatically for contrast.">
                <div class="flex flex-wrap items-center gap-2">
                    @foreach ($palette as $swatch)
                        <button type="button" wire:click="$set('statusColor', '{{ $swatch }}')" aria-label="{{ $swatch }}"
                            @class(['h-8 w-8 rounded-full transition ring-offset-2 ring-offset-[var(--c-surface)]', 'ring-2 ring-[var(--c-ink)] scale-110' => strtolower($statusColor) === $swatch, 'hover:scale-105' => strtolower($statusColor) !== $swatch])
                            style="background: {{ $swatch }}"></button>
                    @endforeach
                    <label class="relative grid h-8 w-8 cursor-pointer place-items-center overflow-hidden rounded-full border border-dashed border-hairline-strong text-ink-muted hover:border-accent" title="Custom colour">
                        <x-heroicon-o-eye-dropper class="h-4 w-4" />
                        <input type="color" wire:model.live="statusColor" class="absolute inset-0 cursor-pointer opacity-0">
                    </label>
                </div>
            </x-ui.field>

            <x-ui.checkbox wire:model.live="statusCompletes" label="Marks the work as done"
                description="A task or part in this status counts as finished." />

            <div class="rounded-lg border border-hairline bg-raised p-3">
                <p class="mb-2 text-xs font-medium uppercase tracking-wide text-ink-muted">Preview</p>
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-sm font-medium"
                    style="background: {{ $statusColor }}; color: {{ $previewText }}">
                    @if ($statusCompletes)<x-heroicon-s-check class="h-4 w-4" />@endif
                    {{ $statusName !== '' ? $statusName : 'Status name' }}
                </span>
            </div>
        </div>
        <x-slot:footer>
            <x-ui.button variant="ghost" x-on:click="$dispatch('close-modal', 'task-status')">Cancel</x-ui.button>
            <x-ui.button variant="primary" wire:click="saveStatus" wire:loading.attr="disabled" wire:target="saveStatus">Save status</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
