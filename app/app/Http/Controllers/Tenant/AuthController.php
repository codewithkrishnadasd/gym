<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Enums\MembershipStatus;
use App\Http\Controllers\Controller;
use App\Models\Organisation;
use App\Models\User;
use App\Support\PhoneNumber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant.auth.login');
    }

    /**
     * Authenticates against the shared `users` table by WhatsApp number, then
     * requires an active `organisation_users` membership for the resolved
     * tenant — a valid password alone is never enough to see this
     * organisation's data. See MEP.md Sections 3.2 and 4.3.
     *
     * The submitted number is normalised with the organisation's country
     * before the lookup, so "98765 43210", "+91 98765 43210" and
     * "098765 43210" all reach the same account.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'phone' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
        ], [], ['phone' => 'WhatsApp number']);

        /** @var Organisation $organisation */
        $organisation = app('tenant');

        $normalised = PhoneNumber::normalise($credentials['phone'], $organisation->defaultCountry());

        $user = $normalised === null
            ? null
            : User::query()->where('phone', $normalised)->first();

        $invalid = ! $user
            || ! Hash::check($credentials['password'], $user->password)
            || $user->membershipFor($organisation)?->status !== MembershipStatus::Active;

        if ($invalid) {
            return back()
                ->withInput($request->only('phone'))
                ->withErrors(['phone' => 'These credentials do not match our records.']);
        }

        Auth::guard('web')->login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('tenant.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('tenant.login');
    }
}
