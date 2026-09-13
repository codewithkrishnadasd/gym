<?php

declare(strict_types=1);

namespace App\Actions\Subscriptions;

use App\Actions\Notifications\CreateActionNotification;
use App\Enums\NotificationActionType;
use App\Enums\NotificationEntityType;
use App\Enums\NotificationRecipientType;
use App\Enums\SubscriptionStatus;
use App\Models\AuditEvent;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Starts or renews a member's plan. A renewal is a new subscription row, not
 * an edit of the previous one, so the member's plan history stays intact and
 * historical payments keep pointing at the term they actually paid for
 * (MEP.md 5.8, 6.6).
 *
 * The end date is derived from the plan's duration rather than accepted from
 * the client, and a renewal starts the day after the current term ends so
 * consecutive terms never overlap.
 */
final class CreateSubscription
{
    public function __construct(private readonly CreateActionNotification $notifications) {}

    public function handle(
        Member $member,
        Plan $plan,
        OrganisationUser $actor,
        ?Carbon $startDate = null,
        ?int $amountDueMinor = null,
    ): MemberSubscription {
        /** @var Organisation $organisation */
        $organisation = app('tenant');

        $current = $member->subscriptions()
            ->where('status', SubscriptionStatus::Active)
            ->orderByDesc('end_date')
            ->first();

        $isRenewal = $current !== null;

        $start = $startDate
            ?? ($current && $current->end_date->isFuture()
                ? $current->end_date->copy()->addDay()
                : Carbon::today($organisation->timezone));

        $subscription = DB::transaction(function () use ($member, $plan, $actor, $start, $amountDueMinor, $current, $isRenewal): MemberSubscription {
            // A renewal supersedes the running term rather than leaving two
            // active subscriptions on the same member.
            if ($current && $current->end_date->isPast()) {
                $current->update(['status' => SubscriptionStatus::Expired]);
            }

            $subscription = MemberSubscription::create([
                'member_id' => $member->id,
                'club_id' => $member->primary_club_id,
                'plan_id' => $plan->id,
                'start_date' => $start->toDateString(),
                'end_date' => $start->copy()->addDays($plan->duration_days - 1)->toDateString(),
                'amount_due_minor' => $amountDueMinor ?? $plan->price_minor,
                'amount_paid_minor' => 0,
                'status' => SubscriptionStatus::Active,
            ]);

            AuditEvent::record(
                $subscription,
                $isRenewal ? 'subscription.renewed' : 'subscription.created',
                $actor,
                null,
                [
                    'plan_id' => $plan->id,
                    'start_date' => $subscription->start_date->toDateString(),
                    'end_date' => $subscription->end_date->toDateString(),
                    'amount_due_minor' => $subscription->amount_due_minor,
                ],
                ['member_id' => $member->id],
            );

            return $subscription;
        });

        $this->notifications->handle(
            organisation: $organisation,
            type: $isRenewal ? NotificationActionType::MemberPlanRenewed : NotificationActionType::MemberPlanCreated,
            recipientType: NotificationRecipientType::Member,
            recipientId: $member->id,
            recipientName: $member->name,
            recipientPhone: $member->phone,
            entityType: NotificationEntityType::Subscription,
            entityId: $subscription->id,
            actor: $actor,
            operationId: 'subscription.create.'.$subscription->id,
            context: [
                'planAction' => $isRenewal ? 'renewed' : 'active',
                'planName' => $plan->name,
                'clubName' => $member->primaryClub?->name,
                'startDate' => $subscription->start_date->format('d M Y'),
                'endDate' => $subscription->end_date->format('d M Y'),
            ],
        );

        return $subscription;
    }
}
