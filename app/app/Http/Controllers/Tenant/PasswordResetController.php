<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Enums\MembershipStatus;
use App\Http\Controllers\Controller;
use App\Models\AuditEvent;
use App\Models\Organisation;
use App\Models\PasswordResetLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Redeeming a password reset link (MEP.md 3.2).
 *
 * Reached by someone who is not signed in, so every lookup filters on the
 * resolved tenant explicitly rather than relying on a global scope. The
 * expired/used case renders a plain page rather than a 404: telling the person
 * their link has expired is what lets them ask for another one, and it reveals
 * nothing — they already hold the token.
 */
class PasswordResetController extends Controller
{
    public function show(Request $request, string $token): View
    {
        $link = $this->findLink($token);
        $user = $link?->user;

        if ($link === null || $user === null || ! $link->isRedeemable()) {
            return view('tenant.auth.reset-expired');
        }

        return view('tenant.auth.reset', [
            'token' => $token,
            'name' => $user->name,
            'expiresAt' => $link->expires_at,
        ]);
    }

    public function store(Request $request, string $token): RedirectResponse|View
    {
        $link = $this->findLink($token);
        $user = $link?->user;

        if ($link === null || $user === null || ! $link->isRedeemable()) {
            return view('tenant.auth.reset-expired');
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.confirmed' => 'The two passwords do not match.',
        ]);

        /** @var Organisation $organisation */
        $organisation = app('tenant');

        $membership = $user->membershipFor($organisation);

        // The link was valid when issued; the membership may have been
        // suspended since. Setting a password for an account that cannot sign
        // in would be a confusing dead end.
        if ($membership?->status !== MembershipStatus::Active) {
            return view('tenant.auth.reset-expired', [
                'reason' => 'This account is no longer active for '.$organisation->name.'.',
            ]);
        }

        DB::transaction(function () use ($link, $user, $validated, $membership): void {
            $user->forceFill(['password' => Hash::make($validated['password'])])->save();

            $link->forceFill(['used_at' => now()])->save();

            // Recorded without the password and without the token — the event
            // is that the person set their own password, which is the fact
            // worth keeping.
            AuditEvent::record($membership, 'user.password_set_via_link', $membership, null, [
                'user_id' => $user->id,
                'issued_by_organisation_user_id' => $link->issued_by_organisation_user_id,
                'issued_by_platform_admin_id' => $link->issued_by_platform_admin_id,
            ]);
        });

        // Signing them straight in is the point of the flow: the alternative is
        // a login form asking for the password they typed ten seconds ago.
        Auth::guard('web')->login($user);

        $request->session()->regenerate();

        return redirect()->route('tenant.dashboard')
            ->with('status', 'Your password is set. You are signed in.');
    }

    /**
     * Looks the link up by hash — the plain token is never stored, so a
     * database dump cannot be replayed.
     */
    private function findLink(string $token): ?PasswordResetLink
    {
        /** @var Organisation $organisation */
        $organisation = app('tenant');

        return PasswordResetLink::query()
            ->with('user')
            ->where('organisation_id', $organisation->id)
            ->where('token_hash', hash('sha256', $token))
            ->first();
    }
}
