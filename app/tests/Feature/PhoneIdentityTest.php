<?php

declare(strict_types=1);

use App\Enums\MembershipStatus;
use App\Enums\NotificationActionType;
use App\Models\Club;
use App\Models\Domain;
use App\Models\MessageTemplate;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\PlatformAdmin;
use App\Models\User;
use App\Support\WhatsApp\MessageComposer;
use Illuminate\Support\Facades\Hash;

/**
 * Sign-in is by WhatsApp number rather than email address, and the number is
 * normalised before the lookup so the same account is reached however the
 * operator types it.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['default_country_code' => 'IN']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'phone.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id]);

    $this->user = User::factory()->create([
        'phone' => '919876543210',
        'password' => Hash::make('correct-horse'),
    ]);

    OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $this->user->id,
        'status' => MembershipStatus::Active,
    ]);
});

it('signs a member of staff in however they type their number', function (string $typed): void {
    $this->post('http://phone.test/login', ['phone' => $typed, 'password' => 'correct-horse'])
        ->assertRedirect('http://phone.test/dashboard');

    expect(auth('web')->id())->toBe($this->user->id);
})->with([
    'national' => '9876543210',
    'with spaces' => '98765 43210',
    'with trunk zero' => '098765 43210',
    'international' => '+91 98765 43210',
    'already normalised' => '919876543210',
]);

it('rejects a wrong password without revealing whether the number exists', function (): void {
    $this->post('http://phone.test/login', ['phone' => '9876543210', 'password' => 'wrong'])
        ->assertSessionHasErrors('phone');

    $this->post('http://phone.test/login', ['phone' => '9000000000', 'password' => 'correct-horse'])
        ->assertSessionHasErrors('phone');

    expect(auth('web')->check())->toBeFalse();
});

it('refuses a number that cannot be a phone number at all', function (): void {
    $this->post('http://phone.test/login', ['phone' => 'not-a-number', 'password' => 'correct-horse'])
        ->assertSessionHasErrors('phone');
});

it('refuses someone whose membership is not active', function (): void {
    OrganisationUser::query()->where('user_id', $this->user->id)->update(['status' => MembershipStatus::Suspended]);

    $this->post('http://phone.test/login', ['phone' => '9876543210', 'password' => 'correct-horse'])
        ->assertSessionHasErrors('phone');

    expect(auth('web')->check())->toBeFalse();
});

it('signs a platform operator in by number', function (): void {
    PlatformAdmin::factory()->create([
        'phone' => '919111111111',
        'password' => Hash::make('root-password'),
    ]);

    $this->post('http://localhost/login', ['phone' => '9111111111', 'password' => 'root-password'])
        ->assertRedirect('http://localhost/dashboard');

    expect(auth('platform')->check())->toBeTrue();
});

it('leaves a placeholder account unable to sign in', function (): void {
    // What the migration parks accounts on when they had no usable number.
    User::factory()->create(['phone' => 'pending-99', 'password' => Hash::make('correct-horse')]);

    $this->post('http://phone.test/login', ['phone' => 'pending-99', 'password' => 'correct-horse'])
        ->assertSessionHasErrors('phone');
});

/**
 * Per-organisation message templates (MEP.md 6.8).
 */
it('falls back to the built-in wording when no template is stored', function (): void {
    $body = MessageComposer::bodyFor($this->organisation, NotificationActionType::FeePaymentConfirmed);

    expect($body)->toBe(MessageComposer::defaultBody(NotificationActionType::FeePaymentConfirmed))
        ->and(MessageComposer::versionFor($this->organisation, NotificationActionType::FeePaymentConfirmed))->toBe('v1');
});

it('uses a stored template and reports its own version', function (): void {
    MessageTemplate::query()->create([
        'organisation_id' => $this->organisation->id,
        'action_type' => NotificationActionType::FeePaymentConfirmed,
        'body' => 'Namaste {memberName}, we received {amount}. Ref {reference}.',
        'version' => 3,
    ]);

    $rendered = MessageComposer::render(
        NotificationActionType::FeePaymentConfirmed,
        $this->organisation,
        ['memberName' => 'Aditi', 'amount' => '₹4,000.00', 'reference' => 'REF9'],
    );

    expect($rendered)->toBe('Namaste Aditi, we received ₹4,000.00. Ref REF9.')
        ->and(MessageComposer::versionFor($this->organisation, NotificationActionType::FeePaymentConfirmed))
        ->toBe('custom-v3');
});

it('substitutes only the variables declared for that action', function (): void {
    MessageTemplate::query()->create([
        'organisation_id' => $this->organisation->id,
        'action_type' => NotificationActionType::MemberCreated,
        'body' => 'Hi {memberName}. Amount {amount}.',
        'version' => 1,
    ]);

    // `amount` belongs to the payment template, not this one, so it is left
    // alone rather than resolved against unrelated data.
    $rendered = MessageComposer::render(
        NotificationActionType::MemberCreated,
        $this->organisation,
        ['memberName' => 'Aditi', 'amount' => '₹9,999.00'],
    );

    expect($rendered)->toContain('Hi Aditi.')
        ->and($rendered)->not->toContain('9,999');
});

it('offers payment variables the operator actually needs', function (): void {
    $variables = MessageComposer::variablesFor(NotificationActionType::FeePaymentConfirmed);

    expect($variables)->toHaveKeys(['memberName', 'amount', 'reference', 'endDate', 'planName', 'clubName']);
});

it('drops a template line whose only value is missing', function (): void {
    $rendered = MessageComposer::render(
        NotificationActionType::FeePaymentConfirmed,
        $this->organisation,
        ['memberName' => 'Aditi', 'amount' => '₹100.00'],
    );

    expect($rendered)->not->toContain(': —')
        ->and($rendered)->toContain('Aditi');
});
