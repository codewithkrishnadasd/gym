<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Organisation;
use App\Models\Scopes\OrganisationScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Applied to every tenant-scoped Eloquent model. Automatically excludes
 * other tenants' rows from every query, and stamps `organisation_id` on
 * create from the resolved tenant when it isn't explicitly set.
 */
trait BelongsToOrganisation
{
    protected static function bootBelongsToOrganisation(): void
    {
        static::addGlobalScope(new OrganisationScope);

        static::creating(function ($model): void {
            if (! $model->organisation_id && app()->bound('tenant')) {
                $model->organisation_id = app('tenant')->id;
            }
        });
    }

    /**
     * @return BelongsTo<Organisation, $this>
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
}
