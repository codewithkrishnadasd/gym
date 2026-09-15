<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Club;
use App\Models\Organisation;
use App\Models\OrganisationUser;

/**
 * The club scope a list, export, or report runs under, for code outside
 * Livewire components (which have ResolvesMembership::clubRestriction()).
 *
 * Null means the whole organisation: admins are never restricted, and when
 * the Clubs module is off nothing carries a club to restrict by.
 */
final class ClubScope
{
    /**
     * @return array<int, int>|null
     */
    public static function resolve(Organisation $organisation, OrganisationUser $membership, int $requestedClubId = 0): ?array
    {
        if (! $organisation->usesClubs()) {
            return null;
        }

        $available = $membership->isAdmin()
            ? Club::query()->pluck('id')->all()
            : $membership->activeClubIds();

        if ($requestedClubId > 0 && in_array($requestedClubId, $available, true)) {
            return [$requestedClubId];
        }

        return $membership->isAdmin() ? null : $available;
    }

    /**
     * The scope named for a document header: the clubs, or the organisation.
     *
     * @param  array<int, int>|null  $clubIds
     */
    public static function describe(Organisation $organisation, ?array $clubIds): string
    {
        if ($clubIds === null) {
            return $organisation->usesClubs() ? 'All '.strtolower($organisation->term('club_plural')) : $organisation->name;
        }

        $names = Club::query()->whereIn('id', $clubIds)->orderBy('name')->pluck('name')->implode(', ');

        return $names !== '' ? $names : 'No '.strtolower($organisation->term('club_plural')).' in scope';
    }
}
