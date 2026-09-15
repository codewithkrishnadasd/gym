<?php

declare(strict_types=1);

namespace App\Actions\Subscriptions;

use App\Actions\Notifications\CreateActionNotification;
use App\Enums\NotificationActionType;
use App\Enums\NotificationEntityType;
use App\Enums\NotificationRecipientType;
use App\Enums\SubscriptionStatus;
use App\Exceptions\LifecycleViolation;
use App\Models\AuditEvent;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;

/**
 * Pauses, resumes, or cancels a running subscription (MEP.md 6.6).
 *
 * Status changes never rewrite the term's dates or amounts — the financial
 * record of what was sold stays as it was, and the payment ledger continues
 * to reconcile against it.
 */
final class ChangeSubscriptionStatus
{
    public function __construct(private readonly CreateActionNotification $notifications) {}

    public function handle(MemberSubscription $subscription, SubscriptionStatus $status, OrganisationUser $actor): MemberSubscription
    {
        if ($subscription->status === SubscriptionStatus::Cancelled) {
            throw LifecycleViolation::subscription('A cancelled plan cannot be changed. Start a new plan instead.');
        }

        /** @var Organisation $organisation */
        $organisation = app('tenant');

        $before = $subscription->status;

        DB::transaction(function () use ($subscription, $status, $actor, $before): void {
            $subscription->update(['status' => $status]);

            AuditEvent::record(
                $subscription,
                'subscription.status_changed',
                $actor,
                ['status' => $before->value],
                ['status' => $status->value],
                ['member_id' => $subscription->member_id],
            );
        });

        $type = match ($status) {
            SubscriptionStatus::Paused => NotificationActionType::MemberPlanPaused,
            SubscriptionStatus::Cancelled => NotificationActionType::MemberPlanCancelled,
            default => NotificationActionType::MemberPlanRenewed,
        };

        /** @var Member $member */
        $member = $subscription->member;
        /** @var Plan $plan */
        $plan = $subscription->plan;

        $this->notifications->handle(
            organisation: $organisation,
            type: $type,
            recipientType: NotificationRecipientType::Member,
            recipientId: $member->id,
            recipientName: $member->name,
            recipientPhone: $member->phone,
            entityType: NotificationEntityType::Subscription,
            entityId: $subscription->id,
            actor: $actor,
            operationId: 'subscription.status.'.$subscription->id.'.'.$status->value,
            context: [
                'planAction' => $status->value,
                'planName' => $plan->name,
                'clubName' => $subscription->club?->name,
                'startDate' => $subscription->start_date->format('d M Y'),
                'endDate' => $subscription->end_date->format('d M Y'),
            ],
        );

        return $subscription;
    }
}
