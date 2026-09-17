<?php

declare(strict_types=1);

namespace App\Support\WhatsApp;

use App\Enums\SubscriptionStatus;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;

/**
 * The values a hand-written message about a person may mention, looked up
 * fresh at the moment of composing — and the reverse: turning a rendered
 * message back into a template by swapping those values for placeholders.
 */
final class RecipientContext
{
    /**
     * @return array<string, string|null>
     */
    public static function forMember(Member $member, Organisation $organisation): array
    {
        $latest = $member->subscriptions()
            ->with('plan:id,name')
            ->whereIn('status', [SubscriptionStatus::Active, SubscriptionStatus::Expired])
            ->orderByDesc('end_date')
            ->first();

        $outstanding = $member->outstandingMinor();

        return [
            ...MessageComposer::baseValues($organisation),
            'memberName' => $member->name,
            'memberId' => $organisation->reference('member', $member->id),
            'clubName' => $member->primaryClub?->name,
            'planName' => $latest?->plan?->name,
            'endDate' => $latest?->end_date->format('d M Y'),
            'balanceDue' => $outstanding > 0 ? $organisation->money($outstanding) : null,
        ];
    }

    /**
     * @return array<string, string|null>
     */
    public static function forStaff(OrganisationUser $staff, Organisation $organisation): array
    {
        return [
            ...MessageComposer::baseValues($organisation),
            'memberName' => (string) $staff->user?->name,
            'memberId' => $organisation->reference('staff', $staff->id),
            'clubName' => null,
            'planName' => null,
            'endDate' => null,
            'balanceDue' => null,
        ];
    }

    /**
     * Swaps a person's actual values back to placeholders so a message
     * written for them can be kept as a template for everyone. Longer
     * values go first so a name never eats part of a longer figure.
     *
     * @param  array<string, string|null>  $values
     */
    public static function templatize(string $message, array $values): string
    {
        $pairs = collect($values)
            ->filter(fn (?string $value): bool => is_string($value) && trim($value) !== '' && mb_strlen($value) >= 2)
            ->sortByDesc(fn (string $value): int => mb_strlen($value))
            ->mapWithKeys(fn (string $value, string $name): array => [$value => '{'.$name.'}'])
            ->all();

        return strtr($message, $pairs);
    }
}
