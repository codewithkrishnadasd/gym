<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Support\Navigation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * A page that arrives in two steps: the shell straight away — navigation,
 * heading, a skeleton shaped like the page — and the content on a follow-up
 * request the moment it lands. Navigating between pages therefore never
 * waits on a query; the wait is shown in place, inside the page.
 *
 * Used together with #[Defer] on a routed component. The component's
 * mount() and render() run on the second request, so anything they read
 * from the page's query string goes through App\Support\PageQuery.
 */
trait LazyPage
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function placeholder(array $params = []): View
    {
        $route = (string) request()->route()?->getName();

        return view('livewire.placeholders.page', [
            'kind' => $this->placeholderKind($route),
        ])->layout('components.layouts.app', ['heading' => $this->placeholderHeading($route)]);
    }

    /** list, detail, form or dashboard — which skeleton to draw. */
    protected function placeholderKind(string $route): string
    {
        return match (true) {
            str_ends_with($route, 'dashboard') => 'dashboard',
            str_ends_with($route, '.index'), str_contains($route, 'attendance.'), str_contains($route, 'notifications'), str_contains($route, 'audit'), str_ends_with($route, '.confirmations') => 'list',
            str_ends_with($route, '.create'), str_ends_with($route, '.edit'), str_contains($route, 'settings'), str_contains($route, '.me.') => 'form',
            default => 'detail',
        };
    }

    /**
     * The section's name from the navigation, so the tab title reads right
     * while the page loads; the route's own segment otherwise.
     */
    protected function placeholderHeading(string $route): string
    {
        if (app()->bound('tenant') && Auth::guard('web')->check()) {
            $membership = app()->bound('membership') ? app('membership') : Auth::guard('web')->user()?->membershipFor(app('tenant'));

            foreach (Navigation::forTenant(app('tenant'), $membership) as $section) {
                foreach ($section['items'] as $item) {
                    if (request()->routeIs($item['active'])) {
                        return (string) $item['label'];
                    }
                }
            }
        }

        $segments = explode('.', $route);

        return Str::headline($segments[count($segments) - 2] ?? $segments[0]);
    }
}
