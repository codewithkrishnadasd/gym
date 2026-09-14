<?php

declare(strict_types=1);

use App\Actions\Payments\ConfirmFeePayment;
use App\Actions\Payments\RecordFeePayment;
use App\Actions\Payments\RejectFeePayment;
use App\Actions\Payments\ReverseFeePayment;
use App\Enums\ConfirmationStatus;
use App\Enums\NotificationStatus;
use App\Exceptions\LifecycleViolation;
use App\Models\AuditEvent;
use App\Models\Club;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\WhatsappActionNotification;
use App\Support\Reporting\OrganisationMetrics;
use App\Support\Reporting\ReportPeriod;

/**
 * The payment lifecycle rules from MEP.md 5.11 and the payment-specific
 * acceptance tests in MEP.md 13.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['currency_code' => 'INR', 'timezone' => 'Asia/Kolkata']);
    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id]);
    $this->staff = OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'permissions' => ['fees.collect' => true],
    ]);
    $this->member = Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $this->club->id,
        'phone' => '9876543210',
    ]);
    $this->plan = Plan::factory()->create(['organisation_id' => $this->organisation->id]);
    // Every collection has to name the account it was received into.
    $this->account = FinancialAccount::factory()->create(['organisation_id' => $this->organisation->id]);
});

function makeSubscription(int $dueMinor = 10000): MemberSubscription
{
    return MemberSubscription::factory()->create([
        'organisation_id' => test()->organisation->id,
        'member_id' => test()->member->id,
        'club_id' => test()->club->id,
        'plan_id' => test()->plan->id,
        'amount_due_minor' => $dueMinor,
        'amount_paid_minor' => 0,
    ]);
}

function makePendingPayment(int $amountMinor = 10000, ?MemberSubscription $subscription = null): FeePayment
{
    return FeePayment::factory()->create([
        'organisation_id' => test()->organisation->id,
        'club_id' => test()->club->id,
        'member_id' => test()->member->id,
        'subscription_id' => $subscription?->id,
        'collected_by' => test()->staff->id,
        'amount_minor' => $amountMinor,
        'currency_code' => 'INR',
    ]);
}

it('creates a staff submission as pending and keeps it out of revenue', function (): void {
    $result = app(RecordFeePayment::class)->handle([
        'club_id' => $this->club->id,
        'member_id' => $this->member->id,
        'subscription_id' => null,
        'payer_name' => $this->member->name,
        'amount_minor' => 5000,
        'currency_code' => 'INR',
        'payment_method' => 'cash',
        'financial_account_id' => $this->account->id,
        'transaction_reference' => null,
        'payment_date' => now()->toDateString(),
        'notes' => null,
    ], $this->staff);

    expect($result->payment->confirmation_status)->toBe(ConfirmationStatus::PendingAdminConfirmation);

    $metrics = new OrganisationMetrics(
        $this->organisation,
        [$this->club->id],
        ReportPeriod::fromStrings(null, null, $this->organisation->timezone),
    );

    expect($metrics->revenueCollected())->toBe(0)
        ->and($metrics->pendingConfirmations())->toBe(1);
});

it('does not confirm a staff submission even when the staff user asks for it', function (): void {
    // `confirmImmediately` is a counter-workflow convenience, never authority.
    $result = app(RecordFeePayment::class)->handle([
        'club_id' => $this->club->id,
        'member_id' => $this->member->id,
        'subscription_id' => null,
        'payer_name' => $this->member->name,
        'amount_minor' => 5000,
        'currency_code' => 'INR',
        'payment_method' => 'cash',
        'financial_account_id' => $this->account->id,
        'transaction_reference' => null,
        'payment_date' => now()->toDateString(),
        'notes' => null,
    ], $this->staff, confirmImmediately: true);

    expect($result->payment->confirmation_status)->toBe(ConfirmationStatus::PendingAdminConfirmation);
});

it('applies the payment to the subscription exactly once on confirmation', function (): void {
    $subscription = makeSubscription(10000);
    $payment = makePendingPayment(4000, $subscription);

    app(ConfirmFeePayment::class)->handle($payment, $this->admin);

    expect($subscription->fresh()->amount_paid_minor)->toBe(4000);
});

it('does not double-apply the credit when confirmation is repeated', function (): void {
    $subscription = makeSubscription(10000);
    $payment = makePendingPayment(4000, $subscription);

    app(ConfirmFeePayment::class)->handle($payment, $this->admin);

    expect(fn () => app(ConfirmFeePayment::class)->handle($payment->fresh(), $this->admin))
        ->toThrow(LifecycleViolation::class);

    expect($subscription->fresh()->amount_paid_minor)->toBe(4000);
});

it('counts only confirmed payments towards revenue', function (): void {
    makePendingPayment(1000);
    $confirmed = makePendingPayment(2000);
    $rejected = makePendingPayment(4000);
    $reversed = makePendingPayment(8000);

    app(ConfirmFeePayment::class)->handle($confirmed, $this->admin);
    app(RejectFeePayment::class)->handle($rejected, $this->admin, 'Amount mismatch');
    app(ConfirmFeePayment::class)->handle($reversed, $this->admin);
    app(ReverseFeePayment::class)->handle($reversed->fresh(), $this->admin, 'Duplicate entry');

    $metrics = new OrganisationMetrics(
        $this->organisation,
        [$this->club->id],
        ReportPeriod::fromStrings(null, null, $this->organisation->timezone),
    );

    expect($metrics->revenueCollected())->toBe(2000);

    $totals = $metrics->paymentStatusTotals();

    expect($totals[ConfirmationStatus::Confirmed->value]['total'])->toBe(2000)
        ->and($totals[ConfirmationStatus::PendingAdminConfirmation->value]['total'])->toBe(1000)
        ->and($totals[ConfirmationStatus::Rejected->value]['total'])->toBe(4000)
        ->and($totals[ConfirmationStatus::Reversed->value]['total'])->toBe(8000);
});

it('preserves the original submission when a payment is rejected', function (): void {
    $subscription = makeSubscription(10000);
    $payment = makePendingPayment(4000, $subscription);

    $rejected = app(RejectFeePayment::class)->handle($payment, $this->admin, 'Receipt does not match');

    expect($rejected->confirmation_status)->toBe(ConfirmationStatus::Rejected)
        ->and($rejected->rejection_reason)->toBe('Receipt does not match')
        ->and($rejected->amount_minor)->toBe(4000)
        ->and($rejected->collected_by)->toBe($this->staff->id)
        // Rejection never touched the subscription balance.
        ->and($subscription->fresh()->amount_paid_minor)->toBe(0);
});

it('withdraws the credit on reversal but keeps the confirmation in history', function (): void {
    $subscription = makeSubscription(10000);
    $payment = makePendingPayment(4000, $subscription);

    app(ConfirmFeePayment::class)->handle($payment, $this->admin);
    $reversed = app(ReverseFeePayment::class)->handle($payment->fresh(), $this->admin, 'Entered twice');

    expect($reversed->confirmation_status)->toBe(ConfirmationStatus::Reversed)
        ->and($reversed->reversal_reason)->toBe('Entered twice')
        ->and($subscription->fresh()->amount_paid_minor)->toBe(0);

    $actions = AuditEvent::query()
        ->where('entity_type', $payment->getMorphClass())
        ->where('entity_id', $payment->id)
        ->pluck('action');

    expect($actions)->toContain('fee_payment.confirmed')
        ->and($actions)->toContain('fee_payment.reversed');
});

it('refuses to reverse a payment that was never confirmed', function (): void {
    $payment = makePendingPayment();

    expect(fn () => app(ReverseFeePayment::class)->handle($payment, $this->admin, 'Nope'))
        ->toThrow(LifecycleViolation::class);
});

it('refuses to confirm a payment that was already rejected', function (): void {
    $payment = makePendingPayment();

    app(RejectFeePayment::class)->handle($payment, $this->admin, 'Wrong amount');

    expect(fn () => app(ConfirmFeePayment::class)->handle($payment->fresh(), $this->admin))
        ->toThrow(LifecycleViolation::class);
});

it('creates one idempotent notification snapshot with a usable WhatsApp number', function (): void {
    $subscription = makeSubscription();
    $payment = makePendingPayment(4000, $subscription);

    $result = app(ConfirmFeePayment::class)->handle($payment, $this->admin);

    expect($result->notification)->not->toBeNull()
        ->and($result->notification->status)->toBe(NotificationStatus::Ready)
        // Normalised to bare international digits for the wa.me path.
        ->and($result->notification->recipient_phone)->toBe('919876543210')
        ->and($result->notification->message_snapshot)->toContain($this->member->name)
        ->and($result->notification->message_snapshot)->toContain($this->plan->name);

    expect(WhatsappActionNotification::query()->count())->toBe(1);
});

it('marks the notification unavailable when the member has no usable number', function (): void {
    $this->member->update(['phone' => 'not-a-number']);
    $payment = makePendingPayment();

    $result = app(ConfirmFeePayment::class)->handle($payment, $this->admin);

    // The action still completed and a copyable message still exists.
    expect($result->payment->confirmation_status)->toBe(ConfirmationStatus::Confirmed)
        ->and($result->notification->status)->toBe(NotificationStatus::Unavailable)
        ->and($result->notification->message_snapshot)->not->toBeEmpty();
});

it('never puts bank details or credentials in the receipt message', function (): void {
    $subscription = makeSubscription();
    $payment = makePendingPayment(4000, $subscription);
    $payment->update(['transaction_reference' => 'UPI-99887766']);

    $result = app(ConfirmFeePayment::class)->handle($payment->fresh(), $this->admin);
    $message = $result->notification->message_snapshot;

    expect($message)->toContain('UPI-99887766')
        ->and($message)->not->toContain('password')
        ->and($message)->not->toContain('token')
        ->and($message)->not->toContain('account_number');
});
