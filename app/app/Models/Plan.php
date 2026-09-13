<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PlanStatus;
use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\PlanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['organisation_id', 'name', 'description', 'price_minor', 'currency_code', 'duration_days', 'session_limit', 'status'])]
class Plan extends Model
{
    /** @use HasFactory<PlanFactory> */
    use BelongsToOrganisation, HasFactory;

    protected function casts(): array
    {
        return [
            'status' => PlanStatus::class,
            'price_minor' => 'integer',
            'duration_days' => 'integer',
            'session_limit' => 'integer',
        ];
    }

    /**
     * @return HasMany<MemberSubscription, $this>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(MemberSubscription::class);
    }
}
