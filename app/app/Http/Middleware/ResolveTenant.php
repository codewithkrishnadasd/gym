<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\DomainStatus;
use App\Enums\OrganisationStatus;
use App\Models\Domain;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolves the current request's hostname to exactly one active
 * organisation before any tenant-scoped data is read or written. See
 * MEP.md Section 3.1.
 *
 * The platform hostname (config('platform.hostname')) is architecturally
 * separate from every tenant domain and is deliberately never looked up
 * here — the platform route group authenticates and authorizes itself.
 */
class ResolveTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower(rtrim($request->getHost(), '.'));

        if ($host === strtolower((string) config('platform.hostname'))) {
            return $next($request);
        }

        $domain = Domain::query()
            ->where('hostname', $host)
            ->where('status', DomainStatus::Active)
            ->with('organisation')
            ->first();

        $organisation = $domain?->organisation;

        if (! $organisation || $organisation->status !== OrganisationStatus::Active) {
            return response()->view('tenant.not-found', status: 404);
        }

        app()->instance('tenant', $organisation);
        View::share('tenant', $organisation);

        return $next($request);
    }
}
