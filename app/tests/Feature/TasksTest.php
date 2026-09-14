<?php

declare(strict_types=1);

use App\Actions\Tasks\CreateTask;
use App\Livewire\Settings\TaskCategories;
use App\Livewire\Tasks\Form as TaskForm;
use App\Livewire\Tasks\Show as TaskShow;
use App\Models\Domain;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\TaskStatus;
use App\Models\TaskSubCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;

/**
 * Tasks for the whole team: categories with coloured statuses and
 * sub-categories, a task carrying one status and one item per sub-category,
 * and a board where each item is moved with a click.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['timezone' => 'Asia/Kolkata']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'tasks.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->category = TaskCategory::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Maintenance']);
    $this->todo = TaskStatus::factory()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => $this->category->id, 'name' => 'To do', 'color' => '#64748b', 'position' => 0]);
    $this->done = TaskStatus::factory()->completes()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => $this->category->id, 'position' => 1]);

    $this->sub = TaskSubCategory::factory()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => $this->category->id, 'name' => 'Treadmill belt']);
    $this->subPending = TaskStatus::factory()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => null, 'task_sub_category_id' => $this->sub->id, 'name' => 'Pending', 'color' => '#fef08a', 'position' => 0]);
    $this->subDone = TaskStatus::factory()->completes()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => null, 'task_sub_category_id' => $this->sub->id, 'position' => 1]);
});

function signIn(Organisation $organisation, bool $admin): OrganisationUser
{
    $user = User::factory()->create();
    $factory = OrganisationUser::factory();
    $membership = ($admin ? $factory->admin() : $factory)->create(['organisation_id' => $organisation->id, 'user_id' => $user->id]);
    test()->actingAs($user);

    return $membership;
}

it('creates a task in the first status with one item per sub-category', function (): void {
    signIn($this->organisation, admin: false);

    Livewire::test(TaskForm::class)
        ->set('categoryId', $this->category->id)
        ->assertSet('statusId', $this->todo->id)
        ->set('title', 'Service the treadmills')
        ->set('startDate', '2026-09-15')
        ->set('dueDate', '2026-09-20')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();

    $task = Task::query()->where('title', 'Service the treadmills')->firstOrFail();

    expect($task->task_status_id)->toBe($this->todo->id)
        ->and($task->isDone())->toBeFalse()
        ->and($task->items()->count())->toBe(1)
        ->and($task->items()->first()?->task_status_id)->toBe($this->subPending->id);
});

it('requires a title and refuses a due date before the start', function (): void {
    signIn($this->organisation, admin: false);

    Livewire::test(TaskForm::class)
        ->set('categoryId', $this->category->id)
        ->set('startDate', '2026-09-20')
        ->set('dueDate', '2026-09-10')
        ->call('save')
        ->assertHasErrors(['title', 'dueDate']);
});

it('moves the task and its parts between coloured statuses with a click', function (): void {
    $admin = signIn($this->organisation, admin: true);
    $task = app(CreateTask::class)->handle($this->category, ['title' => 'Fix the rowers'], $admin);
    $item = $task->items()->firstOrFail();

    $page = $this->get('http://tasks.test/tasks/'.$task->id)->assertOk();
    $page->assertSee('Treadmill belt')->assertSee('background-color:#64748b', false)
        // Pale yellow gets dark text, chosen by luminance.
        ->assertSee('background-color:#fef08a;color:#0f172a', false);

    Livewire::test(TaskShow::class, ['task' => $task])
        ->call('setItemStatus', $item->id, $this->subDone->id)
        ->call('setStatus', $this->done->id);

    $task->refresh();

    expect($task->items()->first()?->task_status_id)->toBe($this->subDone->id)
        ->and($task->task_status_id)->toBe($this->done->id)
        ->and($task->isDone())->toBeTrue();

    // Back to an open status clears completion.
    Livewire::test(TaskShow::class, ['task' => $task])->call('setStatus', $this->todo->id);

    expect($task->fresh()?->isDone())->toBeFalse();
});

it('will not accept a status from another category or sub-category', function (): void {
    $admin = signIn($this->organisation, admin: true);
    $task = app(CreateTask::class)->handle($this->category, ['title' => 'Fix the rowers'], $admin);
    $item = $task->items()->firstOrFail();

    expect(fn () => Livewire::test(TaskShow::class, ['task' => $task])->call('setItemStatus', $item->id, $this->todo->id))
        ->toThrow(ModelNotFoundException::class);

    expect($item->fresh()?->task_status_id)->toBe($this->subPending->id);
});

it('lists open work first and hides done tasks until asked', function (): void {
    $admin = signIn($this->organisation, admin: true);
    $open = app(CreateTask::class)->handle($this->category, ['title' => 'Open task', 'due_date' => '2020-01-01'], $admin);
    $finished = app(CreateTask::class)->handle($this->category, ['title' => 'Finished task'], $admin);
    $finished->moveTo($this->done);

    $this->get('http://tasks.test/tasks')->assertOk()->assertSee('Open task')->assertDontSee('Finished task')->assertSee('Overdue');
    $this->get('http://tasks.test/tasks?show=done')->assertOk()->assertSee('Finished task')->assertDontSee('Open task');
});

it('is open to staff without any special permission, while categories are admin-only', function (): void {
    signIn($this->organisation, admin: false);

    $this->get('http://tasks.test/tasks')->assertOk()->assertSee('New task');
    $this->get('http://tasks.test/tasks/create')->assertOk();

    Livewire::test(TaskCategories::class)->assertForbidden();
});

it('lets an admin build categories, sub-categories and statuses, and protects what is in use', function (): void {
    $admin = signIn($this->organisation, admin: true);

    Livewire::test(TaskCategories::class)
        ->call('startCreateCategory')
        ->set('categoryName', 'Onboarding')
        ->call('saveCategory')
        ->assertHasNoErrors()
        ->assertDispatched('close-modal', 'task-category');

    $category = TaskCategory::query()->where('name', 'Onboarding')->firstOrFail();

    // A new category comes with a starter flow.
    expect($category->statuses()->count())->toBe(3)
        ->and($category->statuses()->where('completes', true)->count())->toBe(1);

    $component = Livewire::test(TaskCategories::class, ['selectedId' => $category->id])
        ->call('startCreateSub')
        ->set('subName', 'Paperwork')
        ->call('saveSub')
        ->assertHasNoErrors();

    $sub = $category->subCategories()->firstOrFail();

    $component
        ->call('startCreateStatus', 'sub', $sub->id)
        ->set('statusName', 'Waiting')
        ->set('statusColor', '#B45309')
        ->call('saveStatus')
        ->assertHasNoErrors();

    $waiting = $sub->statuses()->where('name', 'Waiting')->firstOrFail();

    expect($waiting->color)->toBe('#b45309')->and($waiting->textColor())->toBe('#ffffff');

    // Reorder: move Waiting to the front so new items start there.
    $component->call('moveStatus', $waiting->id, -1)->call('moveStatus', $waiting->id, -1);

    expect($sub->fresh()?->defaultStatus()?->id)->toBe($waiting->id);

    // Something in use cannot be removed.
    $task = app(CreateTask::class)->handle($category, ['title' => 'Welcome Priya'], $admin);

    $component->call('deleteSub', $sub->id)->assertHasErrors(['sub']);
    $component->call('deleteStatus', $waiting->id)->assertHasErrors(['status']);

    expect($sub->fresh())->not->toBeNull()
        ->and($task->items()->count())->toBe(1);
});

it('appears in the navigation for everyone and in settings for admins', function (): void {
    signIn($this->organisation, admin: true);

    $this->get('http://tasks.test/dashboard')->assertOk()->assertSee('Tasks');
    $this->get('http://tasks.test/settings/organisation?tab=tasks')->assertOk()->assertSee('Categories')->assertSee('Maintenance');
});
