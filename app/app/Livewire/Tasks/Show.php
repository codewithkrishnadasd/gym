<?php

declare(strict_types=1);

namespace App\Livewire\Tasks;

use App\Enums\MembershipStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\AuditEvent;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskItem;
use App\Models\TaskStatus;
use App\Models\TaskSubCategory;
use App\Support\Tasks\Mentions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

/**
 * One task: its details, and the board where each part's status is moved.
 * Every status change is a single click and is saved immediately.
 */
class Show extends Component
{
    use ResolvesMembership;

    public Task $task;

    public string $comment = '';

    public function mount(Task $task): void
    {
        $this->authorize('view', $task);

        $this->task = $task;
    }

    /**
     * Moves the task itself to another of its category's statuses.
     */
    public function setStatus(int $statusId): void
    {
        $this->authorize('update', $this->task);

        /** @var TaskStatus $status */
        $status = TaskStatus::query()
            ->where('task_category_id', $this->task->task_category_id)
            ->findOrFail($statusId);

        $before = ['task_status_id' => $this->task->task_status_id];

        $this->task->moveTo($status);

        AuditEvent::record($this->task, 'task.status_changed', $this->currentMembership(), $before, ['task_status_id' => $status->id]);

        $this->task->refresh();
    }

    /**
     * Moves one part of the task to another of its sub-category's statuses.
     */
    public function setItemStatus(int $itemId, int $statusId): void
    {
        $this->authorize('update', $this->task);

        /** @var TaskItem $item */
        $item = $this->task->items()->findOrFail($itemId);

        /** @var TaskStatus $status */
        $status = TaskStatus::query()
            ->where('task_sub_category_id', $item->task_sub_category_id)
            ->findOrFail($statusId);

        $before = ['task_status_id' => $item->task_status_id];

        $item->update(['task_status_id' => $status->id]);

        AuditEvent::record(
            $this->task,
            'task.item_status_changed',
            $this->currentMembership(),
            $before,
            ['task_status_id' => $status->id],
            ['task_item_id' => $item->id, 'task_sub_category_id' => $item->task_sub_category_id],
        );

        $this->task->refresh();
    }

    /**
     * Adds a comment. People named with "@" are recorded as mentions, which
     * puts the task on their list.
     */
    public function addComment(): void
    {
        $this->authorize('view', $this->task);

        $validated = $this->validate(['comment' => ['required', 'string', 'max:4000']], [
            'comment.required' => 'Write something first.',
        ]);

        $actor = $this->currentMembership();
        $people = $this->people();
        $mentioned = Mentions::extract($validated['comment'], $people);

        $comment = $this->task->comments()->create([
            'organisation_user_id' => $actor->id,
            'body' => trim($validated['comment']),
        ]);

        foreach ($mentioned as $person) {
            $this->task->mentions()->create([
                'task_comment_id' => $comment->id,
                'organisation_user_id' => $person->id,
            ]);
        }

        AuditEvent::record(
            $this->task,
            'task.commented',
            $actor,
            null,
            ['task_comment_id' => $comment->id, 'mentioned' => $mentioned->pluck('id')->all()],
        );

        $this->comment = '';
        $this->task->refresh();
    }

    public function deleteComment(int $commentId): void
    {
        /** @var TaskComment $comment */
        $comment = $this->task->comments()->findOrFail($commentId);
        $actor = $this->currentMembership();

        abort_unless($actor->isAdmin() || $comment->organisation_user_id === $actor->id, 403);

        $comment->delete();

        AuditEvent::record($this->task, 'task.comment_deleted', $actor, ['task_comment_id' => $commentId], null);

        $this->task->refresh();
    }

    /**
     * Everyone who can be mentioned: the organisation's active people.
     *
     * @return Collection<int, OrganisationUser>
     */
    private function people(): Collection
    {
        return OrganisationUser::query()
            ->with('user:id,name')
            ->where('status', MembershipStatus::Active)
            ->get();
    }

    /**
     * What happened on this task and who did it — comments and audited
     * changes in one list, oldest first, described in plain words.
     *
     * @param  Collection<int, OrganisationUser>  $people
     * @return Collection<int, array{at: Carbon, actor: string, kind: string, text: string, html: string|null, comment_id: int|null, author_id: int|null}>
     */
    private function activity(Collection $people): Collection
    {
        $statuses = TaskStatus::query()
            ->where(fn ($query) => $query
                ->where('task_category_id', $this->task->task_category_id)
                ->orWhereIn('task_sub_category_id', TaskSubCategory::query()->where('task_category_id', $this->task->task_category_id)->select('id')))
            ->pluck('name', 'id');
        $subCategories = TaskSubCategory::query()->where('task_category_id', $this->task->task_category_id)->pluck('name', 'id');
        $names = $people->mapWithKeys(fn (OrganisationUser $person): array => [$person->id => (string) $person->user?->name]);
        $organisation = $this->organisation();

        $describe = function (AuditEvent $event) use ($statuses, $subCategories, $names, $organisation): ?string {
            $after = $event->after ?? [];
            $before = $event->before ?? [];
            $meta = $event->metadata ?? [];

            return match ($event->action) {
                'task.created' => 'created the task',
                'task.status_changed' => 'moved the task to '.($statuses[$after['task_status_id'] ?? 0] ?? 'another status'),
                'task.item_status_changed' => 'set '.($subCategories[$meta['task_sub_category_id'] ?? 0] ?? 'a part').' to '.($statuses[$after['task_status_id'] ?? 0] ?? 'another status'),
                'task.updated' => $this->describeEdit($before, $after, $names, $statuses, $organisation),
                'task.comment_deleted' => 'removed a comment',
                'task.commented' => null,
                default => null,
            };
        };

        $entries = AuditEvent::query()
            ->with('actor.user:id,name')
            ->where('entity_type', $this->task->getMorphClass())
            ->where('entity_id', $this->task->id)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get()
            ->map(fn (AuditEvent $event): ?array => ($text = $describe($event)) === null ? null : [
                'at' => $event->created_at,
                'actor' => (string) ($event->actor->user->name ?? 'Someone'),
                'kind' => 'change',
                'text' => $text,
                'html' => null,
                'comment_id' => null,
                'author_id' => null,
            ])
            ->filter();

        $comments = $this->task->comments->map(fn (TaskComment $comment): array => [
            'at' => $comment->created_at,
            'actor' => (string) ($comment->author->user->name ?? 'Someone'),
            'kind' => 'comment',
            'text' => $comment->body,
            'html' => Mentions::render($comment->body, $people),
            'comment_id' => $comment->id,
            'author_id' => $comment->organisation_user_id,
        ]);

        /** @var Collection<int, array{at: Carbon, actor: string, kind: string, text: string, html: string|null, comment_id: int|null, author_id: int|null}> $merged */
        $merged = $entries->concat($comments)->sortBy([['at', 'asc']])->values();

        return $merged;
    }

    /**
     * @param  array<string, mixed>  $before
     * @param  array<string, mixed>  $after
     * @param  Collection<int, string>  $names
     * @param  Collection<int, string>  $statuses
     */
    private function describeEdit(array $before, array $after, Collection $names, Collection $statuses, Organisation $organisation): string
    {
        $changes = [];

        foreach (['title' => 'the title', 'description' => 'the description', 'start_date' => 'the start date', 'due_date' => 'the due date'] as $key => $label) {
            if (($before[$key] ?? null) != ($after[$key] ?? null)) {
                $changes[] = $key === 'description' ? 'edited the description' : 'changed '.$label.' to '.(
                    in_array($key, ['start_date', 'due_date'], true) && ! empty($after[$key])
                        ? Carbon::parse((string) $after[$key])->format('d M Y')
                        : ($after[$key] === null || $after[$key] === '' ? 'none' : '“'.$after[$key].'”')
                );
            }
        }

        if (($before['task_status_id'] ?? null) != ($after['task_status_id'] ?? null)) {
            $changes[] = 'moved the task to '.($statuses[$after['task_status_id'] ?? 0] ?? 'another status');
        }

        if (($before['member_id'] ?? null) != ($after['member_id'] ?? null)) {
            $changes[] = empty($after['member_id'])
                ? 'removed the '.strtolower($organisation->term('member_singular'))
                : 'linked a '.strtolower($organisation->term('member_singular'));
        }

        /** @var array<int, int> $wasAssigned */
        $wasAssigned = is_array($before['assignee_ids'] ?? null) ? array_map('intval', $before['assignee_ids']) : [];
        /** @var array<int, int> $nowAssigned */
        $nowAssigned = is_array($after['assignee_ids'] ?? null) ? array_map('intval', $after['assignee_ids']) : [];
        $added = collect(array_diff($nowAssigned, $wasAssigned))->map(fn (int $id): string => $names[$id] ?? 'someone');
        $removed = collect(array_diff($wasAssigned, $nowAssigned))->map(fn (int $id): string => $names[$id] ?? 'someone');

        if ($added->isNotEmpty()) {
            $changes[] = 'assigned '.$added->join(', ', ' and ');
        }

        if ($removed->isNotEmpty()) {
            $changes[] = 'unassigned '.$removed->join(', ', ' and ');
        }

        return $changes === [] ? 'saved the task' : implode('; ', $changes);
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->task);

        $title = $this->task->title;

        AuditEvent::record($this->task, 'task.deleted', $this->currentMembership(), ['title' => $title], null);

        $this->task->delete();

        session()->flash('status', "\"{$title}\" was deleted.");

        $this->redirect(route('tenant.tasks.index'), navigate: true);
    }

    public function render(): View
    {
        $organisation = $this->organisation();

        $this->task->load([
            'category.statuses',
            'status',
            'items.subCategory.statuses',
            'items.status',
            'createdBy.user:id,name',
            'assignees.user:id,name',
            'member:id,name',
        ]);

        $this->task->load(['comments.author.user:id,name']);

        $items = $this->task->items;
        $people = $this->people();

        return view('livewire.tasks.show', [
            'organisation' => $organisation,
            'today' => Carbon::today($organisation->timezone),
            'items' => $items,
            'doneCount' => $items->filter(fn (TaskItem $item): bool => $item->isDone())->count(),
            'activity' => $this->activity($people),
            // For the "@" picker: names the comment box can complete.
            'mentionable' => $people->map(fn (OrganisationUser $person): array => ['id' => $person->id, 'name' => (string) $person->user?->name])
                ->filter(fn (array $person): bool => $person['name'] !== '')->sortBy('name')->values()->all(),
            'me' => $this->currentMembership(),
        ])->layout('components.layouts.app', ['heading' => $this->task->title]);
    }
}
