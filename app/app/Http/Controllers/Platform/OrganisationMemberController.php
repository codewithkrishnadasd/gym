<?php

declare(strict_types=1);

namespace App\Http\Controllers\Platform;

use App\Actions\Auth\IssuePasswordResetLink;
use App\Http\Controllers\Controller;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\PlatformAdmin;
use App\Models\PlatformAuditEvent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class OrganisationMemberController extends Controller
{
    /**
     * Issues a single-use link the organisation's admin can follow to set
     * their own password, and shows it to the platform admin once to relay.
     *
     * This replaces generating a password here. The old flow changed the
     * person's password immediately — locking them out until the new one
     * reached them — left the platform admin knowing a working credential
     * indefinitely, and did so across every organisation the person belongs
     * to, since `users` is one shared login identity (MEP.md 5.3). A link
     * changes nothing until its owner uses it, expires on its own, and is
     * never known to two people at once.
     */
    public function resetPassword(Organisation $organisation, OrganisationUser $organisationUser): RedirectResponse
    {
        abort_unless($organisationUser->organisation_id === $organisation->id, 404);

        /** @var PlatformAdmin $platformAdmin */
        $platformAdmin = Auth::guard('platform')->user();

        /** @var User $user */
        $user = $organisationUser->user;

        $issued = app(IssuePasswordResetLink::class)->handle($organisation, $user, $platformAdmin);

        // The URL is deliberately absent from the audit record: it is a
        // credential until used, and the audit log is read by more people and
        // kept far longer than the link is valid.
        PlatformAuditEvent::record(
            actor: $platformAdmin,
            action: 'user.password_reset_link_issued',
            entityType: 'user',
            entityId: $user->id,
            metadata: [
                'organisation_id' => $organisation->id,
                'phone' => $user->phone,
                'expires_at' => $issued->link->expires_at->toIso8601String(),
            ],
        );

        return redirect()->route('platform.organisations.edit', ['organisation' => $organisation, 'tab' => 'people'])
            ->with('reset_link', $issued->url)
            ->with('reset_link_for', $user->name.' ('.$user->phone.')')
            ->with('reset_link_expires', $issued->expiresLabel());
    }
}
