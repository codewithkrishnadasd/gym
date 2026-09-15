<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Feature;
use App\Models\Organisation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards a route group behind one of the organisation's modules. A module
 * that is switched off is simply not there for that organisation, so the
 * response is 404 rather than 403: there is nothing to be denied access to.
 *
 * Route middleware runs on the page load only — Livewire's later requests go
 * to its own endpoint — so every component also authorizes through a policy
 * that denies when the module is off (see EvaluatesMembership::featureEnabled).
 */
class EnsureFeatureEnabled
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        /** @var Organisation $organisation */
        $organisation = app('tenant');

        abort_unless($organisation->hasFeature(Feature::from($feature)), 404);

        return $next($request);
    }
}
