<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Constrains every query on a tenant-scoped model to the currently resolved
 * organisation, so a query without an explicit tenant filter still cannot
 * cross a tenant boundary. Bound by `ResolveTenant` middleware; there is no
 * bound tenant outside an HTTP request (e.g. console commands), so those
 * call sites must use `withoutGlobalScope` and an explicit filter instead —
 * see technology.md Section 2.2.
 *
 * @implements Scope<Model>
 */
class OrganisationScope implements Scope
{
    /**
     * @param  Builder<covariant Model>  $builder
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (app()->bound('tenant')) {
            $builder->where($model->qualifyColumn('organisation_id'), app('tenant')->id);
        }
    }
}
