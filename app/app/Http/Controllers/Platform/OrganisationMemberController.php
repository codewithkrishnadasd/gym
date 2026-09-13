<?php

declare(strict_types=1);

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\PlatformAdmin;
use App\Models\PlatformAuditEvent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrganisationMemberController extends Controller
{
    /**
     * Generates a new random password for the member's underlying `users`
     * row and shows it to the platform admin once, to relay out of band —
     * there is no email-based reset flow yet. Because `users` is the single
     * shared login identity (MEP.md Section 5.3), this changes the person's
     * password for every organisation they belong to, not just this one.
     */
    public function resetPassword(Organisation $organisation, OrganisationUser $organisationUser): RedirectResponse
    {
        abort_unless($organisationUser->organisation_id === $organisation->id, 404);

        /** @var PlatformAdmin $platformAdmin */
        $platformAdmin = Auth::guard('platform')->user();

        /** @var User $user */
        $user = $organisationUser->user;
        $plainPassword = Str::password(14);

        $user->update(['password' => Hash::make($plainPassword)]);

        PlatformAuditEvent::record(
            actor: $platformAdmin,
            action: 'user.password_reset',
            entityType: 'user',
            entityId: $user->id,
            metadata: ['organisation_id' => $organisation->id, 'email' => $user->email],
        );

        return redirect()->route('platform.organisations.edit', $organisation)
            ->with('generated_password', $plainPassword)
            ->with('generated_password_for', $user->email);
    }
}
