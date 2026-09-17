<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Support\SettingsTabs;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;

/**
 * Lets a settings component run for the platform admin, from the console,
 * on an organisation they have no membership in.
 *
 * On a tenant domain nothing here applies: `platformOrganisationId` is null,
 * ResolveTenant bound the tenant, and the acting user is a member. On the
 * platform hostname the console binds the tenant for the page load, but
 * Livewire's later requests come without it (ResolveTenant deliberately
 * skips that hostname), so it is rebound here from the id carried in the
 * component — which the client cannot change — for a signed-in platform
 * admin only.
 *
 * Components using this must also use ResolvesMembership.
 */
trait ActsForOrganisation
{
    #[Locked]
    public ?int $platformOrganisationId = null;

    /**
     * Runs ahead of mount and of every later request's action, once the
     * property is in place (Livewire assigns passed parameters before the
     * lifecycle hooks fire).
     */
    public function bootActsForOrganisation(): void
    {
        $this->bindPlatformOrganisation();
    }

    protected function bindPlatformOrganisation(): void
    {
        if ($this->platformOrganisationId === null) {
            return;
        }

        abort_unless(Auth::guard('platform')->check(), 403);

        // Authorization (and the layout) read the acting account from the
        // default guard, which is the members' one.
        Auth::shouldUse('platform');

        if (! app()->bound('tenant') || app('tenant')->id !== $this->platformOrganisationId) {
            app()->instance('tenant', Organisation::query()->findOrFail($this->platformOrganisationId));
        }
    }

    protected function actsForPlatform(): bool
    {
        return $this->platformOrganisationId !== null;
    }

    /**
     * The membership to record as the actor of a change — null when the
     * platform admin is acting, who has none.
     */
    protected function actingMembership(): ?OrganisationUser
    {
        return $this->actsForPlatform() ? null : $this->currentMembership();
    }

    /** The URL of a settings tab on whichever page this component is on. */
    protected function settingsUrl(string $tab): string
    {
        return SettingsTabs::url($this->organisation(), $this->actsForPlatform(), $tab);
    }
}
