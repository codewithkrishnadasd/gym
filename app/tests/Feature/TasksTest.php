<?php

declare(strict_types=1);

use App\Actions\Tasks\CreateTask;
use App\Livewire\Settings\TaskCategories;
use App\Livewire\Tasks\Form as TaskForm;
use App\Livewire\Tasks\Index as TaskIndex;
use App\Livewire\Tasks\Show as TaskShow;
use App\Models\Club;
use App\Models\Domain;
use App\Models\Member;
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

it('assigns people and tags a member, and gives every task a reference', function (): void {
    $admin = signIn($this->organisation, admin: true);
    $helper = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create(['name' => 'Helper Hari'])->id]);
    $club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    $member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $club->id, 'name' => 'Alex Morgan']);

    Livewire::test(TaskForm::class)
        ->set('categoryId', $this->category->id)
        ->set('title', 'Call about renewal')
        // Search-and-add, one person at a time; the same design as the member picker.
        ->set('assigneeSearch', 'Hari')
        ->assertSee('Helper Hari')
        ->call('addAssignee', $helper->id)
        ->call('addAssignee', $admin->id)
        ->call('addAssignee', $helper->id)
        ->assertSet('assigneeIds', [$helper->id, $admin->id])
        ->assertSee('Remove')
        ->call('selectMember', $member->id)
        ->assertSet('memberId', $member->id)
        // A title already typed is kept; only an empty one takes the name.
        ->assertSet('title', 'Call about renewal')
        ->call('save')
        ->assertHasNoErrors();

    $task = Task::query()->where('title', 'Call about renewal')->firstOrFail();

    expect($task->assignees()->pluck('organisation_users.id')->sort()->values()->all())->toBe(collect([$helper->id, $admin->id])->sort()->values()->all())
        ->and($task->member_id)->toBe($member->id);

    $this->get('http://tasks.test/tasks')->assertOk()->assertSee('TSK-'.$task->id)->assertSee('for Alex Morgan')->assertSee('Helper Hari');
    $this->get('http://tasks.test/tasks/'.$task->id)->assertOk()->assertSee('TSK-'.$task->id)->assertSee('Helper Hari')->assertSee('Alex Morgan');
});

it('shows staff only the tasks they reported or were assigned', function (): void {
    $admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id]);
    $me = signIn($this->organisation, admin: false);

    $mine = app(CreateTask::class)->handle($this->category, ['title' => 'Handed to Priya', 'assignee_ids' => [$me->id]], $admin);
    $reported = app(CreateTask::class)->handle($this->category, ['title' => 'Opened by Priya'], $me);
    $other = app(CreateTask::class)->handle($this->category, ['title' => 'Somebody else’s'], $admin);

    // Their list opens on what is assigned to them; "All my tasks" shows what they raised too.
    $this->get('http://tasks.test/tasks')->assertOk()->assertSee('Handed to Priya')->assertDontSee('Opened by Priya')->assertDontSee('Somebody else’s');
    $this->get('http://tasks.test/tasks?who=')->assertOk()->assertSee('Handed to Priya')->assertSee('Opened by Priya')->assertDontSee('Somebody else’s');

    // A task they raise starts assigned to them; they can still hand it on.
    Livewire::test(TaskForm::class)
        ->assertSet('assigneeIds', [$me->id])
        ->call('removeAssignee', $me->id)
        ->assertSet('assigneeIds', []);

    // The people filter needs a view of the team; the list stays theirs either way.
    $me->update(['permissions' => ['staff.view' => true]]);
    app()->forgetInstance('membership');

    $this->get('http://tasks.test/tasks?who=mine')->assertOk()->assertSee('Handed to Priya')->assertDontSee('Opened by Priya');
    $this->get('http://tasks.test/tasks?who=reported')->assertOk()->assertSee('Opened by Priya')->assertDontSee('Handed to Priya');

    $this->get('http://tasks.test/tasks/'.$mine->id)->assertOk();
    $this->get('http://tasks.test/tasks/'.$other->id)->assertForbidden();

    // Admins still see everything, and their new tasks start unassigned.
    $this->actingAs($admin->user);
    app()->forgetInstance('membership');
    $this->get('http://tasks.test/tasks')->assertOk()->assertSee('Somebody else’s');
    Livewire::test(TaskForm::class)->assertSet('assigneeIds', []);
});

it('renders the description as markdown with raw html stripped', function (): void {
    $admin = signIn($this->organisation, admin: true);

    $task = app(CreateTask::class)->handle($this->category, [
        'title' => 'Formatted',
        'description' => "## Checklist\n\n- [ ] Order belts\n- **Call** the supplier\n\n<script>alert(1)</script>",
    ], $admin);

    $html = $task->descriptionHtml();

    expect($html)->toContain('<h2>Checklist</h2>')
        ->toContain('<strong>Call</strong>')
        ->toContain('<li>')
        ->not->toContain('<script');

    $this->get('http://tasks.test/tasks/'.$task->id)->assertOk()->assertSee('<strong>Call</strong>', false)->assertDontSee('alert(1)');
    $this->get('http://tasks.test/tasks/'.$task->id.'/edit')->assertOk()->assertSee('markdownEditor(', false)->assertSee('Preview');
});

it('takes comments, mentions colleagues with @, and shows the task to whoever was mentioned', function (): void {
    $admin = signIn($this->organisation, admin: true);
    $priya = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create(['name' => 'Priya Nair'])->id]);
    OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create(['name' => 'Priya'])->id]);

    $task = app(CreateTask::class)->handle($this->category, ['title' => 'Belt order'], $admin);

    Livewire::test(TaskShow::class, ['task' => $task])
        ->set('comment', "Can @Priya Nair chase the supplier?\nThanks")
        ->call('addComment')
        ->assertHasNoErrors()
        ->assertSet('comment', '')
        ->assertSee('Can <span', false)
        ->assertSee('@Priya Nair</span>', false)
        ->assertSee('Thanks');

    // Longest name wins: "Priya Nair" is one mention, not also "Priya".
    expect($task->mentions()->count())->toBe(1)
        ->and($task->mentions()->first()?->organisation_user_id)->toBe($priya->id)
        ->and($task->fresh()?->involves($priya))->toBeTrue();

    // Priya can now see and open the task, and it is under "Mentioned me".
    // (Her list opens on what is assigned to her, so "All my tasks" is asked for.)
    $this->actingAs($priya->user);
    $this->get('http://tasks.test/tasks?who=')->assertOk()->assertSee('Belt order');
    $this->get('http://tasks.test/tasks?who=mentioned')->assertOk()->assertSee('Belt order');
    $this->get('http://tasks.test/tasks/'.$task->id)->assertOk()->assertSee('Priya Nair');

    // An empty comment is refused.
    Livewire::test(TaskShow::class, ['task' => $task])->set('comment', '  ')->call('addComment')->assertHasErrors(['comment']);
});

it('shows who changed what, in order, as activity', function (): void {
    $admin = signIn($this->organisation, admin: true);
    $helper = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create(['name' => 'Helper Hari'])->id]);

    $task = app(CreateTask::class)->handle($this->category, ['title' => 'Service rowers'], $admin);
    $item = $task->items()->firstOrFail();

    Livewire::test(TaskShow::class, ['task' => $task])
        ->call('setItemStatus', $item->id, $this->subDone->id)
        ->call('setStatus', $this->done->id)
        ->set('comment', 'All done here.')
        ->call('addComment');

    Livewire::test(TaskForm::class, ['task' => $task])
        ->set('title', 'Service rowers and bikes')
        ->set('dueDate', '2026-10-01')
        ->call('addAssignee', $helper->id)
        ->call('save')
        ->assertHasNoErrors();

    $page = $this->get('http://tasks.test/tasks/'.$task->id)->assertOk();

    $page->assertSee('created the task')
        ->assertSee('set Treadmill belt to Done')
        ->assertSee('moved the task to Done')
        ->assertSee('commented')
        ->assertSee('All done here.')
        ->assertSee('changed the title to “Service rowers and bikes”')
        ->assertSee('changed the due date to 01 Oct 2026')
        ->assertSee('assigned Helper Hari');

    // Oldest first: creation comes before the status change.
    $html = $page->getContent();
    expect(strpos($html, 'created the task'))->toBeLessThan((int) strpos($html, 'moved the task to Done'));
});

it('lets the author or an admin delete a comment, nobody else', function (): void {
    $admin = signIn($this->organisation, admin: true);
    $task = app(CreateTask::class)->handle($this->category, ['title' => 'Belt order'], $admin);

    $author = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create()->id]);
    $task->assignees()->attach($author->id);
    $comment = $task->comments()->create(['organisation_user_id' => $author->id, 'body' => 'Mine']);

    $other = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create()->id]);
    $task->assignees()->attach($other->id);

    $this->actingAs($other->user);
    Livewire::test(TaskShow::class, ['task' => $task])->call('deleteComment', $comment->id)->assertForbidden();

    $this->actingAs($author->user);
    Livewire::test(TaskShow::class, ['task' => $task])->call('deleteComment', $comment->id);

    expect($task->comments()->count())->toBe(0);
});

it('has a help guide for writing descriptions', function (): void {
    signIn($this->organisation, admin: true);

    $this->get('http://tasks.test/tasks/create')
        ->assertOk()
        ->assertSee('How to format the description')
        ->assertSee('## Sub title')
        ->assertSee('- [ ] To do')
        ->assertSee('Checkbox, ticked');
});

it('shows completion as a percentage, overall and per task, following the filters', function (): void {
    $admin = signIn($this->organisation, admin: true);
    $other = TaskCategory::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Events']);
    // First status is where new tasks start, so it must be an open one.
    TaskStatus::factory()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => $other->id, 'name' => 'Planned', 'position' => 0]);
    TaskStatus::factory()->completes()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => $other->id, 'position' => 1]);

    $done = app(CreateTask::class)->handle($this->category, ['title' => 'Finished one'], $admin);
    $done->items()->update(['task_status_id' => $this->subDone->id]);
    $done->moveTo($this->done);
    $half = app(CreateTask::class)->handle($this->category, ['title' => 'Half way'], $admin);
    app(CreateTask::class)->handle($other, ['title' => 'Elsewhere'], $admin);

    // Everything: 1 of 3 done.
    $this->get('http://tasks.test/tasks?show=all')->assertOk()->assertSee('33%')->assertSee('1 of 3 tasks');

    // Filtered to one category: 1 of 2, and its parts are half done.
    $this->get('http://tasks.test/tasks?category='.$this->category->id.'&show=all')
        ->assertOk()->assertSee('50%')->assertSee('1 of 2 tasks')->assertSee('parts 50% done')
        // Per task: the open one shows 0% · 0/1, the finished one 100%.
        ->assertSee('0%')->assertSee('100%');

    // The open/done switch does not change the share: still 1 of 2.
    $this->get('http://tasks.test/tasks?category='.$this->category->id.'&show=open')->assertOk()->assertSee('1 of 2 tasks');

    $this->get('http://tasks.test/tasks/'.$half->id)->assertOk()->assertSee('0% complete');
});

it('removes an assignee from the list before saving', function (): void {
    signIn($this->organisation, admin: true);
    $a = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create(['name' => 'Anu'])->id]);
    $b = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create(['name' => 'Bala'])->id]);

    Livewire::test(TaskForm::class)
        ->call('addAssignee', $a->id)
        ->call('addAssignee', $b->id)
        ->call('removeAssignee', $a->id)
        ->assertSet('assigneeIds', [$b->id])
        ->assertSee('Bala')
        ->assertDontSee('Anu');
});

it('draws one coloured segment per part in the list, and keeps commenting outside the folded activity', function (): void {
    $admin = signIn($this->organisation, admin: true);
    $second = TaskSubCategory::factory()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => $this->category->id, 'name' => 'Rower chains']);
    TaskStatus::factory()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => null, 'task_sub_category_id' => $second->id, 'name' => 'Waiting', 'color' => '#b45309', 'position' => 0]);

    $task = app(CreateTask::class)->handle($this->category, ['title' => 'Segmented'], $admin);
    $task->items()->where('task_sub_category_id', $this->sub->id)->update(['task_status_id' => $this->subDone->id]);

    $this->get('http://tasks.test/tasks')
        ->assertOk()
        // First part done (green from the factory's completes state), second waiting (amber), in order.
        ->assertSeeInOrder(['background: #047857', 'background: #b45309'])
        ->assertSee('Treadmill belt: Done, Rower chains: Waiting');

    // The comment box is its own card, outside the folded Activity history.
    $html = $this->get('http://tasks.test/tasks/'.$task->id)->assertOk()->getContent();

    expect(strpos($html, 'Write a comment'))->toBeGreaterThan((int) strpos($html, 'comments and every change'))
        ->and($html)->toContain('Type @ to mention a colleague');
});

it('filters the list by member', function (): void {
    $admin = signIn($this->organisation, admin: true);
    $club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    $alex = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $club->id, 'name' => 'Alex Morgan']);
    $priya = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $club->id, 'name' => 'Priya Nair']);

    app(CreateTask::class)->handle($this->category, ['title' => 'Call Alex back', 'member_id' => $alex->id], $admin);
    app(CreateTask::class)->handle($this->category, ['title' => 'Renew Priya', 'member_id' => $priya->id], $admin);
    app(CreateTask::class)->handle($this->category, ['title' => 'Nobody in particular'], $admin);

    $this->get('http://tasks.test/tasks')->assertOk()->assertSee('Any member')->assertSee('Priya Nair');
    $this->get('http://tasks.test/tasks?member='.$alex->id)->assertOk()->assertSee('Call Alex back')->assertDontSee('Renew Priya')->assertDontSee('Nobody in particular');
});

it('keeps reminders on the task and shows the due ones on opening the app until the task is done', function (): void {
    $this->travelTo('2026-09-15 10:00');
    $admin = signIn($this->organisation, admin: true);
    $task = app(CreateTask::class)->handle($this->category, ['title' => 'Order belts'], $admin);

    Livewire::test(TaskShow::class, ['task' => $task])
        ->set('reminderLabel', 'Chase the supplier')
        ->set('reminderDate', '2026-09-14')
        ->call('addReminder')
        ->assertHasNoErrors()
        ->set('reminderLabel', 'Check delivery')
        ->set('reminderDate', '2026-09-30')
        ->call('addReminder')
        ->assertHasNoErrors()
        ->assertSee('Chase the supplier')
        ->assertSee('Check delivery')
        ->assertSee('1 due now');

    expect($task->reminders()->count())->toBe(2);

    // Opening any page lists the reminder whose day has come, linking to the task.
    $this->get('http://tasks.test/dashboard')
        ->assertOk()
        ->assertSee('1 reminder')
        ->assertSee('Chase the supplier')
        ->assertSee('/tasks/'.$task->id, false)
        ->assertDontSee('Check delivery')
        ->assertSee('__taskRemindersShown', false);

    // Done tasks stop reminding.
    $task->moveTo($this->done);
    $this->get('http://tasks.test/dashboard')->assertOk()->assertDontSee('Chase the supplier');

    // Removing works and is recorded.
    $task->moveTo($this->todo);
    $first = $task->reminders()->first();
    Livewire::test(TaskShow::class, ['task' => $task])->call('removeReminder', $first->id)->assertDontSee('wire:key="reminder-'.$first->id.'"', false);
    expect($task->reminders()->count())->toBe(1);
    $this->get('http://tasks.test/tasks/'.$task->id)->assertOk()->assertSee('removed the reminder');
});

it('shows staff only reminders on tasks they are involved in', function (): void {
    $this->travelTo('2026-09-15 10:00');
    $admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id]);
    $me = signIn($this->organisation, admin: false);

    $mine = app(CreateTask::class)->handle($this->category, ['title' => 'Mine', 'assignee_ids' => [$me->id]], $admin);
    $other = app(CreateTask::class)->handle($this->category, ['title' => 'Theirs'], $admin);
    $mine->reminders()->create(['label' => 'Mine reminder', 'remind_on' => '2026-09-15', 'created_by' => $admin->id]);
    $other->reminders()->create(['label' => 'Their reminder', 'remind_on' => '2026-09-15', 'created_by' => $admin->id]);

    $this->get('http://tasks.test/dashboard')->assertOk()->assertSee('Mine reminder')->assertDontSee('Their reminder');
});

it('lets one person hold each part, shows everyone on the ticket, and makes the task visible to them', function (): void {
    $admin = signIn($this->organisation, admin: true);
    $hari = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create(['name' => 'Helper Hari'])->id]);
    $anu = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create(['name' => 'Anu Assignee'])->id]);

    $task = app(CreateTask::class)->handle($this->category, ['title' => 'Split work', 'assignee_ids' => [$anu->id]], $admin);
    $item = $task->items()->firstOrFail();

    Livewire::test(TaskShow::class, ['task' => $task])
        ->call('setItemAssignee', $item->id, $hari->id)
        ->assertSee('Helper Hari')
        ->assertSee('gave Treadmill belt to Helper Hari');

    expect($item->fresh()?->assignee_id)->toBe($hari->id)
        ->and($task->fresh()?->people()->pluck('id')->sort()->values()->all())->toBe(collect([$anu->id, $hari->id])->sort()->values()->all());

    // The list names both the task's assignee and the part's holder.
    $this->get('http://tasks.test/tasks')->assertOk()->assertSee('Anu Assignee, Helper Hari');

    // Holding a part is enough to see the task.
    $this->actingAs($hari->user);
    $this->get('http://tasks.test/tasks')->assertOk()->assertSee('Split work');
    $this->get('http://tasks.test/tasks/'.$task->id)->assertOk()->assertSee('Whole task')->assertSee('Treadmill belt');

    // And it can be handed back.
    Livewire::test(TaskShow::class, ['task' => $task])->call('setItemAssignee', $item->id, null);
    expect($item->fresh()?->assignee_id)->toBeNull();
});

it('filters the list to unassigned tasks or to one person\'s tasks', function (): void {
    $admin = signIn($this->organisation, true);
    $holder = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create(['name' => 'Holder Hana'])->id]);

    $make = fn (string $title): Task => Task::factory()->create([
        'organisation_id' => $this->organisation->id,
        'task_category_id' => $this->category->id,
        'task_status_id' => $this->todo->id,
        'created_by' => $admin->id,
        'title' => $title,
    ]);

    $nobody = $make('Nobody has this');
    $whole = $make('Hana holds the whole thing');
    $whole->assignees()->attach($holder->id);
    $part = $make('Hana holds one part');
    $part->items()->create(['task_sub_category_id' => $this->sub->id, 'task_status_id' => $this->subPending->id, 'assignee_id' => $holder->id, 'position' => 0]);

    $page = fn (string $who) => test()->get('http://tasks.test/tasks?who='.$who)->assertOk();

    $page('unassigned')->assertSee('Nobody has this')->assertDontSee('Hana holds the whole thing')->assertDontSee('Hana holds one part');
    $page('staff:'.$holder->id)->assertSee('Hana holds the whole thing')->assertSee('Hana holds one part')->assertDontSee('Nobody has this');
    $page('')->assertSee('Nobody has this')->assertSee('Hana holds one part');

    // The people filter offers every active team member by name.
    $page('')->assertSee('value="staff:'.$holder->id.'"', false)->assertSee('Holder Hana')->assertSee('value="unassigned"', false);
});

it('narrows the people filter to themselves for staff who cannot see the team, and lists only their own work', function (): void {
    $admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create()->id]);
    $staff = signIn($this->organisation, false);

    Task::factory()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => $this->category->id, 'task_status_id' => $this->todo->id, 'created_by' => $admin->id, 'title' => 'Not theirs']);
    $mine = Task::factory()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => $this->category->id, 'task_status_id' => $this->todo->id, 'created_by' => $admin->id, 'title' => 'Handed to them']);
    $mine->assignees()->attach($staff->id);

    // A people filter in the link naming someone else is ignored rather
    // than widening the list; the choices offered are only about oneself.
    $this->get('http://tasks.test/tasks?who=staff:'.$admin->id)
        ->assertOk()
        ->assertSee('Handed to them')
        ->assertDontSee('Not theirs')
        ->assertDontSee('value="unassigned"', false)
        ->assertDontSee('Everyone')
        ->assertSee('All my tasks')
        ->assertSee('Assigned to me');

    // With a view of the team, the filter is offered.
    $staff->update(['permissions' => ['staff.view' => true]]);
    app()->forgetInstance('membership');

    $this->get('http://tasks.test/tasks')->assertOk()->assertSee('value="unassigned"', false);
});

it('opens a staff member\'s list on what is assigned to them, until they choose otherwise', function (): void {
    $admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create()->id]);
    $staff = signIn($this->organisation, false);

    Task::factory()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => $this->category->id, 'task_status_id' => $this->todo->id, 'created_by' => $staff->id, 'title' => 'Raised by them']);
    $mine = Task::factory()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => $this->category->id, 'task_status_id' => $this->todo->id, 'created_by' => $admin->id, 'title' => 'Handed to them']);
    $mine->assignees()->attach($staff->id);

    // First visit: only what they hold.
    $this->get('http://tasks.test/tasks')->assertOk()->assertSee('Handed to them')->assertDontSee('Raised by them');

    // A link naming a filter wins.
    $this->get('http://tasks.test/tasks?who=reported')->assertOk()->assertSee('Raised by them')->assertDontSee('Handed to them');

    // Their own choice sticks: widening to everything of theirs is remembered.
    Livewire::test(TaskIndex::class)->set('who', '');
    $this->get('http://tasks.test/tasks')->assertOk()->assertSee('Handed to them')->assertSee('Raised by them');

    // An administrator's list opens on everyone's tasks.
    $this->actingAs($admin->user);
    app()->forgetInstance('membership');
    session()->forget('filters.'.$this->organisation->id.'.'.TaskIndex::class);
    $this->get('http://tasks.test/tasks')->assertOk()->assertSee('Handed to them')->assertSee('Raised by them')->assertSee('Everyone');
});

it('files a task under a club and filters the list by it', function (): void {
    $admin = signIn($this->organisation, true);
    $north = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'North']);
    $south = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'South']);

    Livewire::test(TaskForm::class)
        ->set('categoryId', $this->category->id)
        ->set('title', 'Fix North treadmill')
        ->set('clubId', $north->id)
        ->call('save')
        ->assertHasNoErrors();

    $task = Task::query()->where('title', 'Fix North treadmill')->firstOrFail();
    expect($task->club_id)->toBe($north->id);

    Task::factory()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => $this->category->id, 'task_status_id' => $this->todo->id, 'created_by' => $admin->id, 'title' => 'South open day', 'club_id' => $south->id]);

    // The save above flashed its title; the list itself is what is asserted.
    session()->forget('status');

    $this->get('http://tasks.test/tasks?club='.$north->id)->assertOk()->assertSee('Fix North treadmill')->assertDontSee('South open day');
    $this->get('http://tasks.test/tasks?club='.$south->id)->assertOk()->assertSee('South open day')->assertDontSee('Fix North treadmill');
    $this->get('http://tasks.test/tasks')->assertOk()->assertSee('Fix North treadmill')->assertSee('South open day')->assertSee('· North');
    $this->get('http://tasks.test/tasks/'.$task->id)->assertOk()->assertSee('North');

    // Without the Clubs module there is no club to choose and none is stored.
    $this->organisation->update(['features' => ['tasks', 'members']]);
    app()->instance('tenant', $this->organisation->fresh());

    Livewire::test(TaskForm::class)
        ->set('categoryId', $this->category->id)
        ->set('title', 'Clubless chore')
        ->call('save')
        ->assertHasNoErrors();

    expect(Task::query()->where('title', 'Clubless chore')->value('club_id'))->toBeNull();
    $this->get('http://tasks.test/tasks/create')->assertOk()->assertDontSee('name="clubId"', false);
});

it('lists a member\'s tasks on their page and starts a new one already about them, at their club', function (): void {
    $admin = signIn($this->organisation, true);
    $north = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'North']);
    Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'South']);
    $member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $north->id, 'name' => 'Tasked Tom']);
    $other = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $north->id, 'name' => 'Other Olga']);

    Task::factory()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => $this->category->id, 'task_status_id' => $this->todo->id, 'created_by' => $admin->id, 'title' => 'Renew Tom locker', 'member_id' => $member->id]);
    Task::factory()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => $this->category->id, 'task_status_id' => $this->todo->id, 'created_by' => $admin->id, 'title' => 'Olga induction', 'member_id' => $other->id]);

    $this->get('http://tasks.test/members/'.$member->id.'?tab=tasks')
        ->assertOk()
        ->assertSee('Renew Tom locker')
        ->assertDontSee('Olga induction')
        ->assertSee('/tasks/create?member='.$member->id, false);

    // The new-task form arrives already about Tom, at Tom's club, titled
    // with his name, and links back to his page.
    Livewire::withQueryParams(['member' => $member->id])->test(TaskForm::class)
        ->assertSet('memberId', $member->id)
        ->assertSet('clubId', $north->id)
        ->assertSet('title', $member->name)
        ->assertSee('/members/'.$member->id, false)
        ->assertSee('View');

    // Without the Tasks module the tab is gone.
    $this->organisation->update(['features' => ['members', 'clubs']]);
    app()->instance('tenant', $this->organisation->fresh());

    $this->get('http://tasks.test/members/'.$member->id)->assertOk()->assertDontSee('tab=tasks');
});
