<?php

declare(strict_types=1);

use App\Enums\NotificationActionType;
use App\Livewire\Notifications\ActionPanel;
use App\Livewire\Notifications\ComposeMenu;
use App\Livewire\Settings\OrganisationSettings;
use App\Models\Club;
use App\Models\CustomMessageTemplate;
use App\Models\Domain;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\User;
use App\Models\WhatsappActionNotification;
use App\Support\WhatsApp\RecipientContext;
use Livewire\Livewire;

/**
 * The WhatsApp button on a person's page is a menu: a hello, the expired-plan
 * nudge when their plan has run out, the organisation's own templates, and a
 * blank message — each composed for that person and previewed before sending.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['name' => 'FitZone', 'currency_code' => 'INR', 'timezone' => 'Asia/Kolkata']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'compose.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $user = User::factory()->create();
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'North']);
    $this->member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id, 'name' => 'Riya Menon', 'phone' => '919876500011']);
});

it('offers the expired-plan follow-up only when the plan has run out, filled in for the member', function (): void {
    $plan = Plan::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Quarterly']);

    Livewire::test(ComposeMenu::class, ['recipientType' => 'member', 'recipientId' => $this->member->id])
        ->assertSee('Say hi')
        ->assertSee('New message')
        ->assertDontSee('Plan expired follow-up');

    MemberSubscription::factory()->create([
        'organisation_id' => $this->organisation->id,
        'member_id' => $this->member->id,
        'club_id' => $this->club->id,
        'plan_id' => $plan->id,
        'start_date' => now()->subDays(100)->toDateString(),
        'end_date' => now()->subDays(10)->toDateString(),
        'amount_due_minor' => 400000,
        'amount_paid_minor' => 400000,
    ]);

    Livewire::test(ComposeMenu::class, ['recipientType' => 'member', 'recipientId' => $this->member->id])
        ->assertSee('Plan expired follow-up')
        ->call('compose', 'plan_expired')
        ->assertDispatched('notification-created');

    $notification = WhatsappActionNotification::query()->latest('id')->firstOrFail();
    expect($notification->action_type)->toBe(NotificationActionType::MemberPlanExpired)
        ->and($notification->message_snapshot)->toContain('Riya Menon')->toContain('Quarterly')->toContain('ended on')->toContain('North')
        ->and($notification->recipient_phone)->toBe('919876500011');

    // The member page carries the menu and the panel that shows the result.
    $this->get('http://compose.test/members/'.$this->member->id)->assertOk()->assertSee('Send on WhatsApp')->assertSee('Plan expired follow-up');
});

it('composes from the organisation\'s own template, and a blank message opens in the editor', function (): void {
    CustomMessageTemplate::factory()->create([
        'organisation_id' => $this->organisation->id,
        'audience' => 'member',
        'name' => 'Class reminder',
        'body' => "Hi {memberName}, class at {clubName} tomorrow 7am.\nSee you, {organisationName}.",
    ]);
    CustomMessageTemplate::factory()->create(['organisation_id' => $this->organisation->id, 'audience' => 'user', 'name' => 'Staff only']);

    $menu = Livewire::test(ComposeMenu::class, ['recipientType' => 'member', 'recipientId' => $this->member->id])
        ->assertSee('Class reminder')
        ->assertDontSee('Staff only');

    $template = CustomMessageTemplate::query()->where('name', 'Class reminder')->firstOrFail();

    $menu->call('compose', 'template:'.$template->id)->assertDispatched('notification-created');

    $sent = WhatsappActionNotification::query()->latest('id')->firstOrFail();
    expect($sent->message_snapshot)->toBe("Hi Riya Menon, class at North tomorrow 7am.\nSee you, FitZone.")
        ->and($sent->message_template_version)->toBe('custom:'.$template->id)
        ->and($sent->action_type)->toBe(NotificationActionType::CustomMessage);

    $menu->call('compose', 'new')->assertDispatched('notification-created', edit: true);
    expect(WhatsappActionNotification::query()->latest('id')->value('message_snapshot'))->toBe('Hi Riya Menon,');
});

it('saves a written message as a template with the person\'s details turned into placeholders', function (): void {
    Livewire::test(ComposeMenu::class, ['recipientType' => 'member', 'recipientId' => $this->member->id])->call('compose', 'hi');
    $notification = WhatsappActionNotification::query()->latest('id')->firstOrFail();

    Livewire::test(ActionPanel::class, ['notificationId' => $notification->id])
        ->assertSee('Save as template')
        ->call('saveAsTemplate', 'Welcome back', 'Hi Riya Menon, welcome back to FitZone at North! Your ID is MEM-'.$this->member->id.'.')
        ->assertDispatched('template-saved');

    $template = CustomMessageTemplate::query()->where('name', 'Welcome back')->firstOrFail();
    expect($template->body)->toBe('Hi {memberName}, welcome back to {organisationName} at {clubName}! Your ID is {memberId}.')
        ->and($template->audience->value)->toBe('member');

    // Saving under the same name updates rather than duplicates.
    Livewire::test(ActionPanel::class, ['notificationId' => $notification->id])
        ->call('saveAsTemplate', 'Welcome back', 'Hi Riya Menon, good to see you.');

    expect(CustomMessageTemplate::query()->where('name', 'Welcome back')->count())->toBe(1)
        ->and(CustomMessageTemplate::query()->where('name', 'Welcome back')->value('body'))->toBe('Hi {memberName}, good to see you.');
});

it('manages custom templates under Message templates and rejects unknown variables', function (): void {
    Livewire::test(OrganisationSettings::class, ['tab' => 'templates'])
        ->set('customTemplateName', 'Festive greeting')
        ->set('customTemplateAudience', 'member')
        ->set('customTemplateBody', 'Happy Diwali {memberName}, from all of us at {organisationName}!')
        ->call('addCustomTemplate')
        ->assertHasNoErrors()
        ->assertSee('Festive greeting');

    Livewire::test(OrganisationSettings::class, ['tab' => 'templates'])
        ->set('customTemplateName', 'Broken')
        ->set('customTemplateBody', 'Hi {memberName}, your {password} is …')
        ->call('addCustomTemplate')
        ->assertHasErrors(['customTemplateBody']);

    $template = CustomMessageTemplate::query()->where('name', 'Festive greeting')->firstOrFail();

    Livewire::test(OrganisationSettings::class, ['tab' => 'templates'])
        ->call('deleteCustomTemplate', $template->id)
        ->assertDontSee('Festive greeting');

    expect(CustomMessageTemplate::query()->count())->toBe(0);
});

it('turns values back into placeholders longest first', function (): void {
    $values = ['memberName' => 'Ann', 'clubName' => 'Ann Arbor Club', 'organisationName' => 'FitZone', 'planName' => null];

    expect(RecipientContext::templatize('Hi Ann, see you at Ann Arbor Club — FitZone', $values))
        ->toBe('Hi {memberName}, see you at {clubName} — {organisationName}');
});
