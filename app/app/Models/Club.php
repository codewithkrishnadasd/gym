<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ClubStatus;
use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\ClubFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'organisation_id', 'name', 'code', 'logo_path', 'status', 'phone', 'email',
    'address', 'timezone', 'opening_hours', 'admission_fee_minor', 'created_by',
])]
class Club extends Model
{
    /** @use HasFactory<ClubFactory> */
    use BelongsToOrganisation, HasFactory;

    protected function casts(): array
    {
        return [
            'status' => ClubStatus::class,
            'address' => 'array',
            'opening_hours' => 'array',
            'admission_fee_minor' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'created_by');
    }

    /**
     * @return HasMany<ClubUserAssignment, $this>
     */
    public function userAssignments(): HasMany
    {
        return $this->hasMany(ClubUserAssignment::class);
    }

    /**
     * @return HasMany<Member, $this>
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class, 'primary_club_id');
    }

    /**
     * @return HasMany<Attendance, $this>
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * @return HasMany<FeePayment, $this>
     */
    public function feePayments(): HasMany
    {
        return $this->hasMany(FeePayment::class);
    }

    /**
     * @return HasMany<MemberSubscription, $this>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(MemberSubscription::class);
    }

    /**
     * @return HasMany<ClubPlanDiscount, $this>
     */
    public function planDiscounts(): HasMany
    {
        return $this->hasMany(ClubPlanDiscount::class);
    }

    /**
     * The standing discount this club gives on a plan, in minor units; zero
     * when none is configured. Capped at the plan price so a stale discount
     * can never take the amount due below nothing.
     */
    public function discountFor(Plan $plan): int
    {
        $discount = (int) $this->planDiscounts()->where('plan_id', $plan->id)->value('discount_minor');

        return max(0, min($discount, $plan->price_minor));
    }
}
