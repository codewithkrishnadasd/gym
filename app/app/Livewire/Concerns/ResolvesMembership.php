<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Enums\ClubAssignmentStatus;
use App\Models\Club;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * `EnsureActiveMembership` binds `membership` into the container for a page
 * load, but Livewire's `/livewire/update` endpoint (where every subsequent
 * action on the component runs) doesn't replay that middleware — so the
 * binding is gone by the time a button click or form submit reaches the
 * component. Every Livewire action that needs the acting user's membership
 * must resolve it fresh via this trait rather than reading `app('membership')`
 * directly.
 */
trait ResolvesMembership
{
    protected function currentMembership(): OrganisationUser
    {
        if (app()->bound('membership')) {
            return app('membership');
        }

        /** @var User $user */
        $user = Auth::guard('web')->user();

        /** @var OrganisationUser $membership */
        $membership = $user->membershipFor(app('tenant'));

        return $membership;
    }

    protected function organisation(): Organisation
    {
        /** @var Organisation $organisation */
        $organisation = app('tenant');

        return $organisation;
    }

    /**
     * The clubs the acting user may operate on: every active club for an
     * admin, and only actively-assigned clubs for a staff user. Used to scope
     * both list queries and club pickers, so a staff member can never select
     * a club they have no assignment to.
     *
     * @return Collection<int, Club>
     */
    protected function accessibleClubs(bool $includeArchived = false): Collection
    {
        $membership = $this->currentMembership();

        return Club::query()
            ->when(! $includeArchived, fn ($query) => $query->where('status', 'active'))
            ->when(! $membership->isAdmin(), fn ($query) => $query->whereIn(
                'id',
                $membership->clubAssignments()->where('status', ClubAssignmentStatus::Active)->select('club_id')
            ))
            ->orderBy('name')
            ->get();
    }

    /**
     * @return array<int, int>
     */
    protected function accessibleClubIds(): array
    {
        $membership = $this->currentMembership();

        return $membership->isAdmin()
            ? Club::query()->pluck('id')->all()
            : $membership->activeClubIds();
    }
}
