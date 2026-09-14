<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Livewire\Concerns\ResolvesMembership;
use App\Models\AuditEvent;
use App\Models\TaskCategory;
use App\Models\TaskItem;
use App\Models\TaskStatus;
use App\Support\Theme\AccentPalette;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Set-up for tasks: categories, each with its statuses and sub-categories,
 * and each sub-category with statuses of its own. Admin-only.
 *
 * Statuses in use are never deleted from under a task — the editor refuses
 * and says how many tasks or parts would be left without a state.
 */
class TaskCategories extends Component
{
    use ResolvesMembership;

    #[Url(as: 'category')]
    public ?int $selectedId = null;

    // Category modal
    public ?int $categoryEditingId = null;

    public string $categoryName = '';

    // Sub-category modal
    public ?int $subEditingId = null;

    public string $subName = '';

    // Status modal — owned by the category or by one sub-category.
    public ?int $statusEditingId = null;

    public string $statusOwner = 'category';

    public ?int $statusOwnerId = null;

    public string $statusName = '';

    public string $statusColor = '#0e7490';

    public bool $statusCompletes = false;

    public function mount(): void
    {
        $this->authorize('manage', TaskCategory::class);

        if ($this->selectedId !== null && ! TaskCategory::query()->whereKey($this->selectedId)->exists()) {
            $this->selectedId = null;
        }

        $this->selectedId ??= TaskCategory::query()->orderBy('position')->orderBy('id')->value('id');
    }

    public function select(int $categoryId): void
    {
        $this->selectedId = TaskCategory::query()->whereKey($categoryId)->exists() ? $categoryId : null;
    }

    // ---- Categories -----------------------------------------------------

    public function startCreateCategory(): void
    {
        $this->authorize('manage', TaskCategory::class);
        $this->categoryEditingId = null;
        $this->categoryName = '';
        $this->resetErrorBag();
        $this->dispatch('open-modal', 'task-category');
    }

    public function startEditCategory(int $categoryId): void
    {
        $this->authorize('manage', TaskCategory::class);
        $category = TaskCategory::query()->findOrFail($categoryId);
        $this->categoryEditingId = $category->id;
        $this->categoryName = $category->name;
        $this->resetErrorBag();
        $this->dispatch('open-modal', 'task-category');
    }

    public function saveCategory(): void
    {
        $this->authorize('manage', TaskCategory::class);

        $validated = $this->validate(['categoryName' => ['required', 'string', 'max:120']], [], ['categoryName' => 'name']);

        if ($this->categoryEditingId) {
            $category = TaskCategory::query()->findOrFail($this->categoryEditingId);
            $before = ['name' => $category->name];
            $category->update(['name' => $validated['categoryName']]);
            AuditEvent::record($category, 'task_category.updated', $this->currentMembership(), $before, ['name' => $category->name]);
        } else {
            $category = TaskCategory::create([
                'name' => $validated['categoryName'],
                'status' => 'active',
                'position' => (int) TaskCategory::query()->max('position') + 1,
            ]);

            // A fresh category starts with a sensible three-step flow so the
            // first task can be created straight away; all of it is editable.
            foreach ([['To do', '#64748b', false], ['In progress', '#0e7490', false], ['Done', '#047857', true]] as $position => [$name, $color, $completes]) {
                $category->statuses()->create([
                    'organisation_id' => $category->organisation_id,
                    'name' => $name,
                    'color' => $color,
                    'completes' => $completes,
                    'position' => $position,
                ]);
            }

            AuditEvent::record($category, 'task_category.created', $this->currentMembership(), null, ['name' => $category->name]);
            $this->selectedId = $category->id;
        }

        $this->dispatch('close-modal', 'task-category');
        session()->flash('status', 'Category saved.');
    }

    public function removeCategory(int $categoryId): void
    {
        $this->authorize('manage', TaskCategory::class);
        $category = TaskCategory::query()->findOrFail($categoryId);
        $category->update(['status' => 'archived']);
        AuditEvent::record($category, 'task_category.archived', $this->currentMembership(), ['status' => 'active'], ['status' => 'archived']);
    }

    public function restoreCategory(int $categoryId): void
    {
        $this->authorize('manage', TaskCategory::class);
        $category = TaskCategory::query()->findOrFail($categoryId);
        $category->update(['status' => 'active']);
        AuditEvent::record($category, 'task_category.restored', $this->currentMembership(), ['status' => 'archived'], ['status' => 'active']);
    }

    // ---- Sub-categories -------------------------------------------------

    public function startCreateSub(): void
    {
        $this->authorize('manage', TaskCategory::class);
        $this->subEditingId = null;
        $this->subName = '';
        $this->resetErrorBag();
        $this->dispatch('open-modal', 'task-sub-category');
    }

    public function startEditSub(int $subCategoryId): void
    {
        $this->authorize('manage', TaskCategory::class);
        $sub = $this->selected()?->subCategories()->findOrFail($subCategoryId);
        $this->subEditingId = $sub?->id;
        $this->subName = (string) $sub?->name;
        $this->resetErrorBag();
        $this->dispatch('open-modal', 'task-sub-category');
    }

    public function saveSub(): void
    {
        $this->authorize('manage', TaskCategory::class);
        $category = $this->selected();
        abort_unless($category !== null, 404);

        $validated = $this->validate(['subName' => ['required', 'string', 'max:120']], [], ['subName' => 'name']);

        if ($this->subEditingId) {
            $sub = $category->subCategories()->findOrFail($this->subEditingId);
            $before = ['name' => $sub->name];
            $sub->update(['name' => $validated['subName']]);
            AuditEvent::record($category, 'task_sub_category.updated', $this->currentMembership(), $before, ['name' => $sub->name], ['task_sub_category_id' => $sub->id]);
        } else {
            $sub = $category->subCategories()->create([
                'organisation_id' => $category->organisation_id,
                'name' => $validated['subName'],
                'position' => (int) $category->subCategories()->max('position') + 1,
            ]);

            foreach ([['Pending', '#64748b', false], ['Done', '#047857', true]] as $position => [$name, $color, $completes]) {
                $sub->statuses()->create([
                    'organisation_id' => $category->organisation_id,
                    'name' => $name,
                    'color' => $color,
                    'completes' => $completes,
                    'position' => $position,
                ]);
            }

            AuditEvent::record($category, 'task_sub_category.created', $this->currentMembership(), null, ['name' => $sub->name], ['task_sub_category_id' => $sub->id]);
        }

        $this->dispatch('close-modal', 'task-sub-category');
        session()->flash('status', 'Sub-category saved.');
    }

    public function deleteSub(int $subCategoryId): void
    {
        $this->authorize('manage', TaskCategory::class);
        $category = $this->selected();
        $sub = $category?->subCategories()->findOrFail($subCategoryId);

        if ($category === null || $sub === null) {
            return;
        }

        // Deleting would take the part off every task that has it. Refuse
        // while any does; the parts are the record of the work.
        $inUse = TaskItem::query()->where('task_sub_category_id', $sub->id)->count();

        if ($inUse > 0) {
            $this->addError('sub', "“{$sub->name}” is part of {$inUse} ".($inUse === 1 ? 'task' : 'tasks').' and cannot be removed. Rename it instead.');

            return;
        }

        $sub->delete();
        AuditEvent::record($category, 'task_sub_category.deleted', $this->currentMembership(), ['name' => $sub->name], null, ['task_sub_category_id' => $subCategoryId]);
    }

    // ---- Statuses -------------------------------------------------------

    public function startCreateStatus(string $owner, int $ownerId): void
    {
        $this->authorize('manage', TaskCategory::class);
        $this->statusEditingId = null;
        $this->statusOwner = $owner === 'sub' ? 'sub' : 'category';
        $this->statusOwnerId = $ownerId;
        $this->statusName = '';
        $this->statusColor = TaskStatus::PALETTE[array_rand(TaskStatus::PALETTE)];
        $this->statusCompletes = false;
        $this->resetErrorBag();
        $this->dispatch('open-modal', 'task-status');
    }

    public function startEditStatus(int $statusId): void
    {
        $this->authorize('manage', TaskCategory::class);
        $status = $this->ownedStatuses()->firstWhere('id', $statusId);

        if ($status === null) {
            return;
        }

        $this->statusEditingId = $status->id;
        $this->statusOwner = $status->task_sub_category_id ? 'sub' : 'category';
        $this->statusOwnerId = $status->task_sub_category_id ?? $status->task_category_id;
        $this->statusName = $status->name;
        $this->statusColor = $status->color;
        $this->statusCompletes = $status->completes;
        $this->resetErrorBag();
        $this->dispatch('open-modal', 'task-status');
    }

    public function saveStatus(): void
    {
        $this->authorize('manage', TaskCategory::class);
        $category = $this->selected();
        abort_unless($category !== null, 404);

        $validated = $this->validate([
            'statusName' => ['required', 'string', 'max:60'],
            'statusColor' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'statusCompletes' => ['boolean'],
        ], ['statusColor.regex' => 'Pick a colour.'], ['statusName' => 'name', 'statusColor' => 'colour']);

        $owner = $this->statusOwner === 'sub'
            ? $category->subCategories()->findOrFail($this->statusOwnerId)
            : $category;

        $attributes = [
            'name' => $validated['statusName'],
            'color' => strtolower($validated['statusColor']),
            'completes' => (bool) $validated['statusCompletes'],
        ];

        if ($this->statusEditingId) {
            $status = $owner->statuses()->findOrFail($this->statusEditingId);
            $before = $status->only(['name', 'color', 'completes']);
            $status->update($attributes);
            AuditEvent::record($category, 'task_status.updated', $this->currentMembership(), $before, $attributes, ['task_status_id' => $status->id]);
        } else {
            $status = $owner->statuses()->create([
                ...$attributes,
                'organisation_id' => $category->organisation_id,
                'position' => (int) $owner->statuses()->max('position') + 1,
            ]);
            AuditEvent::record($category, 'task_status.created', $this->currentMembership(), null, $attributes, ['task_status_id' => $status->id]);
        }

        $this->dispatch('close-modal', 'task-status');
        session()->flash('status', 'Status saved.');
    }

    public function deleteStatus(int $statusId): void
    {
        $this->authorize('manage', TaskCategory::class);
        $category = $this->selected();
        $status = $this->ownedStatuses()->firstWhere('id', $statusId);

        if ($category === null || $status === null) {
            return;
        }

        $inUse = $status->task_category_id
            ? $category->tasks()->where('task_status_id', $status->id)->count()
            : TaskItem::query()->where('task_status_id', $status->id)->count();

        if ($inUse > 0) {
            $this->addError('status', "“{$status->name}” is the current status of {$inUse} ".($status->task_category_id ? 'task' : 'part').($inUse === 1 ? '' : 's').'. Move them to another status first.');

            return;
        }

        $status->delete();
        AuditEvent::record($category, 'task_status.deleted', $this->currentMembership(), $status->only(['name', 'color']), null, ['task_status_id' => $statusId]);
    }

    /**
     * Swaps a status with its neighbour so the order — and with it which
     * status new records start in — can be arranged.
     */
    public function moveStatus(int $statusId, int $direction): void
    {
        $this->authorize('manage', TaskCategory::class);
        $status = $this->ownedStatuses()->firstWhere('id', $statusId);

        if ($status === null) {
            return;
        }

        $siblings = $status->task_sub_category_id
            ? TaskStatus::query()->where('task_sub_category_id', $status->task_sub_category_id)
            : TaskStatus::query()->where('task_category_id', $status->task_category_id)->whereNull('task_sub_category_id');

        $ordered = $siblings->orderBy('position')->orderBy('id')->get()->values();
        $index = $ordered->search(fn (TaskStatus $candidate): bool => $candidate->id === $status->id);
        $target = $index + ($direction < 0 ? -1 : 1);

        if ($index === false || $target < 0 || $target >= $ordered->count()) {
            return;
        }

        $ordered->splice($index, 1);
        $ordered->splice($target, 0, [$status]);

        foreach ($ordered as $position => $sibling) {
            $sibling->update(['position' => $position]);
        }
    }

    private function selected(): ?TaskCategory
    {
        return $this->selectedId ? TaskCategory::query()->find($this->selectedId) : null;
    }

    /**
     * Every status that belongs to the selected category or its sub-categories.
     *
     * @return Collection<int, TaskStatus>
     */
    private function ownedStatuses(): Collection
    {
        $category = $this->selected();

        if ($category === null) {
            return new Collection;
        }

        return TaskStatus::query()
            ->where(fn ($query) => $query
                ->where('task_category_id', $category->id)
                ->orWhereIn('task_sub_category_id', $category->subCategories()->select('id')))
            ->get();
    }

    public function render(): View
    {
        $selected = $this->selected()?->load(['statuses', 'subCategories.statuses']);

        return view('livewire.settings.task-categories', [
            'categories' => TaskCategory::query()->withCount('tasks')->orderBy('position')->orderBy('id')->get(),
            'selected' => $selected,
            'palette' => TaskStatus::PALETTE,
            'previewText' => AccentPalette::readableOn(AccentPalette::isValid($this->statusColor) ? $this->statusColor : '#0e7490'),
        ]);
    }
}
