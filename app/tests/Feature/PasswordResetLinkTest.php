<?php

declare(strict_types=1);

use App\Actions\Auth\IssuedResetLink;
use App\Actions\Auth\IssuePasswordResetLink;
use App\Enums\MembershipStatus;
use App\Enums\NotificationActionType;
use App\Livewire\Staff\Index as StaffIndex;
use App\Models\AuditEvent;
use App\Models\Domain;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\PasswordResetLink;
use App\Models\PlatformAdmin;
use App\Models\User;
use App\Models\WhatsappActionNotification;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

/**
 * Reset links replace admin-generated passwords (MEP.md 3.2). The properties
 * that make that an improvement rather than a rename are all here: the link
 * expires, works once, changes nothing until it is used, and signs the person
 * in so there is no second credential prompt.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['name' => 'FitZone']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'reset.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->admin = OrganisationUser::factory()->admin()->create([
        'organisation_id' => $this->organisation->id,
    ]);

    $this->user = User::factory()->create(['password' => Hash::make('the-old-password')]);

    $this->membership = OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $this->user->id,
        'status' => MembershipStatus::Active,
    ]);
});

function issueLink(): IssuedResetLink
{
    return app(IssuePasswordResetLink::class)->handle(
        test()->organisation,
        test()->user,
        test()->admin,
    );
}

it('stores only the hash of the token', function (): void {
    $issued = issueLink();

    $token = str($issued->url)->afterLast('/')->toString();

    expect($issued->link->token_hash)->toBe(hash('sha256', $token))
        ->and($issued->link->token_hash)->not->toContain($token);
});

it('points the link at the organisation own domain, not the current host', function (): void {
    expect(issueLink()->url)->toContain('reset.test/set-password/');
});

it('leaves the current password working until the link is used', function (): void {
    issueLink();

    expect(Hash::check('the-old-password', $this->user->fresh()?->password))->toBeTrue();
});

it('sets the password and signs the person in', function (): void {
    $token = str(issueLink()->url)->afterLast('/')->toString();

    $this->get('http://reset.test/set-password/'.$token)
        ->assertOk()
        ->assertSee($this->user->name);

    $this->post('http://reset.test/set-password/'.$token, [
        'password' => 'a-brand-new-password',
        'password_confirmation' => 'a-brand-new-password',
    ])->assertRedirect(route('tenant.dashboard'));

    $this->assertAuthenticatedAs($this->user->fresh(), 'web');

    expect(Hash::check('a-brand-new-password', $this->user->fresh()?->password))->toBeTrue();
});

it('refuses to work a second time', function (): void {
    $token = str(issueLink()->url)->afterLast('/')->toString();

    $this->post('http://reset.test/set-password/'.$token, [
        'password' => 'first-password-set',
        'password_confirmation' => 'first-password-set',
    ]);

    $this->post('http://reset.test/set-password/'.$token, [
        'password' => 'second-attempt-here',
        'password_confirmation' => 'second-attempt-here',
    ])->assertSee('no longer works');

    expect(Hash::check('first-password-set', $this->user->fresh()?->password))->toBeTrue();
});

it('refuses an expired link', function (): void {
    $issued = issueLink();
    $token = str($issued->url)->afterLast('/')->toString();

    $issued->link->forceFill(['expires_at' => now()->subMinute()])->save();

    $this->get('http://reset.test/set-password/'.$token)->assertSee('no longer works');

    $this->post('http://reset.test/set-password/'.$token, [
        'password' => 'should-not-apply',
        'password_confirmation' => 'should-not-apply',
    ]);

    expect(Hash::check('the-old-password', $this->user->fresh()?->password))->toBeTrue();
});

it('retires an earlier link when a new one is issued', function (): void {
    $first = issueLink();
    issueLink();

    expect($first->link->fresh()?->isRedeemable())->toBeFalse()
        ->and(PasswordResetLink::query()->outstanding()->count())->toBe(1);
});

it('will not redeem a link on another organisation domain', function (): void {
    $token = str(issueLink()->url)->afterLast('/')->toString();

    $other = Organisation::factory()->create();
    Domain::factory()->create([
        'organisation_id' => $other->id,
        'hostname' => 'elsewhere.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    $this->get('http://elsewhere.test/set-password/'.$token)->assertSee('no longer works');
});

it('refuses when the membership was suspended after the link was issued', function (): void {
    $token = str(issueLink()->url)->afterLast('/')->toString();

    $this->membership->update(['status' => MembershipStatus::Suspended]);

    $this->post('http://reset.test/set-password/'.$token, [
        'password' => 'should-not-apply',
        'password_confirmation' => 'should-not-apply',
    ])->assertSee('no longer active');

    expect(Hash::check('the-old-password', $this->user->fresh()?->password))->toBeTrue();
});

it('lets an organisation admin issue a link for staff, carried on WhatsApp', function (): void {
    $this->actingAs($this->admin->user);

    Livewire::test(StaffIndex::class)->call('sendResetLink', $this->membership->id);

    $notification = WhatsappActionNotification::query()->latest('id')->first();

    expect($notification?->action_type)->toBe(NotificationActionType::PasswordResetLink)
        ->and($notification?->message_snapshot)->toContain('reset.test/set-password/')
        // Recorded as having happened, without the link itself.
        ->and(AuditEvent::query()->where('action', 'user.password_reset_link_issued')->exists())->toBeTrue();

    $audit = AuditEvent::query()->where('action', 'user.password_reset_link_issued')->first();

    expect(json_encode($audit?->after))->not->toContain('set-password/');
});

it('stops an admin issuing a reset link for their own account', function (): void {
    $this->actingAs($this->admin->user);

    Livewire::test(StaffIndex::class)
        ->call('sendResetLink', $this->admin->id)
        ->assertForbidden();
});

it('sends the reset link even when the organisation has notifications switched off', function (): void {
    // Suppressing this one would leave an admin holding a link with no way to
    // pass it on.
    $this->organisation->update(['notification_settings' => ['enabled' => false]]);

    $this->actingAs($this->admin->user);

    Livewire::test(StaffIndex::class)->call('sendResetLink', $this->membership->id);

    expect(WhatsappActionNotification::query()->count())->toBe(1);
});

it('lets a platform admin issue one without changing the current password', function (): void {
    $platformAdmin = PlatformAdmin::factory()->create();

    $this->actingAs($platformAdmin, 'platform')
        ->post('http://'.config('platform.hostname').'/organisations/'.$this->organisation->id.'/members/'.$this->membership->id.'/reset-password')
        ->assertRedirect()
        ->assertSessionHas('reset_link');

    expect(Hash::check('the-old-password', $this->user->fresh()?->password))->toBeTrue()
        ->and(PasswordResetLink::query()->outstanding()->count())->toBe(1);
});

it('requires the two passwords to match', function (): void {
    $token = str(issueLink()->url)->afterLast('/')->toString();

    $this->from('http://reset.test/set-password/'.$token)
        ->post('http://reset.test/set-password/'.$token, [
            'password' => 'one-password-here',
            'password_confirmation' => 'different-password',
        ])
        ->assertSessionHasErrors('password');

    expect(Hash::check('the-old-password', $this->user->fresh()?->password))->toBeTrue();
});
