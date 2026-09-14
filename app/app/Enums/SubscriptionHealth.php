<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\MemberSubscription;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

/**
 * What a member's plan is actually doing today, as opposed to what
 * `member_subscriptions.status` last recorded.
 *
 * The stored status only changes when somebody acts: a renewal supersedes the
 * previous term, an operator pauses or cancels one. Nothing writes to it when
 * a term simply runs out, so a plan that ended in June is still stored as
 * `active`. Deriving the state from `end_date` at read time means the answer is
 * right the moment the clock passes midnight, with no scheduled job to run late
 * or fail quietly.
 *
 * Deliberately separate from SubscriptionStatus: that column is a record of
 * decisions taken, and rewriting it from a date would lose the difference
 * between "cancelled by an admin" and "simply lapsed".
 */
enum SubscriptionHealth: string
{
    case Active = 'active';
    case ExpiringSoon = 'expiring';
    case Expired = 'expired';
    case Paused = 'paused';
    case Cancelled = 'cancelled';
    case None = 'none';

    /**
     * How far ahead a term counts as "expiring soon". One number, used by the
     * badges and by the member filter, so the list and the label can never
     * disagree about which members are at risk.
     */
    public const WARNING_DAYS = 14;

    public static function for(?MemberSubscription $subscription, CarbonInterface $today): self
    {
        if ($subscription === null) {
            return self::None;
        }

        $daysLeft = self::daysUntilEnd($subscription, $today);

        return match ($subscription->status) {
            SubscriptionStatus::Paused => self::Paused,
            SubscriptionStatus::Cancelled => self::Cancelled,
            // A superseded term is history, not a lapse — the member is on the
            // plan that replaced it.
            SubscriptionStatus::Expired => self::Expired,
            SubscriptionStatus::Active => match (true) {
                $daysLeft < 0 => self::Expired,
                $daysLeft <= self::WARNING_DAYS => self::ExpiringSoon,
                default => self::Active,
            },
        };
    }

    /**
     * Whole calendar days from today to the end of the term; negative once it
     * has passed.
     *
     * Both sides are reduced to a bare Y-m-d first. `end_date` is a date cast,
     * so it is midnight in the *application* timezone, while `$today` is
     * midnight in the *organisation's* — comparing those two Carbon instances
     * directly is out by the offset between them, which silently moves every
     * boundary by a day for any organisation not on UTC.
     */
    private static function daysUntilEnd(MemberSubscription $subscription, CarbonInterface $today): int
    {
        $end = CarbonImmutable::parse($subscription->end_date->toDateString());
        $reference = CarbonImmutable::parse($today->toDateString());

        return (int) $reference->diffInDays($end, absolute: false);
    }

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::ExpiringSoon => 'Expiring soon',
            self::Expired => 'Expired',
            self::Paused => 'Paused',
            self::Cancelled => 'Cancelled',
            self::None => 'No plan',
        };
    }

    /**
     * The label with the actual number of days, for places with room for it.
     * "Expiring soon" is a category; "Expires in 3 days" is what makes someone
     * pick up the phone.
     */
    public function detailedLabel(?MemberSubscription $subscription, CarbonInterface $today): string
    {
        if ($subscription === null) {
            return $this->label();
        }

        $days = abs(self::daysUntilEnd($subscription, $today));

        return match ($this) {
            self::ExpiringSoon => $days === 0 ? 'Expires today' : 'Expires in '.$days.' '.str('day')->plural($days),
            self::Expired => $days === 0 ? 'Expired today' : 'Expired '.$days.' '.str('day')->plural($days).' ago',
            default => $this->label(),
        };
    }

    /**
     * Semantic badge tone from the design system, so one state never renders
     * two different colours in two different views.
     */
    public function tone(): string
    {
        return match ($this) {
            self::Active => 'positive',
            self::ExpiringSoon => 'caution',
            self::Expired => 'critical',
            self::Paused => 'caution',
            // Someone with no plan at all is not an error, but they are not
            // earning either — the same amber a lapsing plan gets.
            self::None => 'caution',
            self::Cancelled => 'neutral',
        };
    }

    /**
     * Whether this state is something an operator should act on. Drives
     * whether a badge is drawn at all in dense lists, where marking every
     * healthy row would be noise.
     */
    public function needsAttention(): bool
    {
        return in_array($this, [self::ExpiringSoon, self::Expired], true);
    }

    /**
     * The states offered as a filter, in the order an operator scans them:
     * problems first.
     *
     * @return array<int, self>
     */
    public static function filterable(): array
    {
        return [self::Expired, self::ExpiringSoon, self::Active, self::Paused, self::None];
    }
}
