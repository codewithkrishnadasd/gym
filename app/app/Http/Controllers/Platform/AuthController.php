<?php

declare(strict_types=1);

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Support\PhoneNumber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('platform')->check()) {
            return redirect()->route('platform.dashboard');
        }

        return view('platform.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'phone' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
        ], [], ['phone' => 'WhatsApp number']);

        // No tenant is resolved on the platform hostname, so the fallback
        // country from the phone helper applies here.
        $normalised = PhoneNumber::normalise($credentials['phone']);

        $attempt = $normalised !== null && Auth::guard('platform')->attempt(
            ['phone' => $normalised, 'password' => $credentials['password']],
            $request->boolean('remember'),
        );

        if (! $attempt) {
            return back()
                ->withInput($request->only('phone'))
                ->withErrors(['phone' => 'These credentials do not match our records.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('platform.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('platform')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('platform.login');
    }
}
