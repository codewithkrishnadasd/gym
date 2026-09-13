<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\MembershipStatus;
use App\Models\Organisation;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Runs after `auth:web` on every protected tenant route. A valid session is
 * not enough — the user must also have an active `organisation_users` row
 * for the resolved tenant (MEP.md Section 3.2/4.3). Binds the resolved
 * membership into the container (`app('membership')`) so controllers,
 * Livewire components, and policies don't each re-derive it.
 */
class EnsureActiveMembership
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User $user */
        $user = Auth::guard('web')->user();

        /** @var Organisation $organisation */
        $organisation = app('tenant');

        $membership = $user->membershipFor($organisation);

        if (! $membership || $membership->status !== MembershipStatus::Active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('tenant.login')->withErrors([
                'email' => 'Your access to this organisation is no longer active.',
            ]);
        }

        app()->instance('membership', $membership);

        return $next($request);
    }
}
