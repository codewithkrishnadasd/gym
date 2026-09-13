<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MemberStatus;
use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\MemberFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'organisation_id', 'primary_club_id', 'name', 'phone', 'email', 'date_of_birth',
    'gender', 'photo_path', 'address', 'emergency_contact', 'joined_at', 'status',
    'notes', 'created_by',
])]
class Member extends Model
{
    /** @use HasFactory<MemberFactory> */
    use BelongsToOrganisation, HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'status' => MemberStatus::class,
            'date_of_birth' => 'date',
            'joined_at' => 'date',
            'address' => 'array',
            'emergency_contact' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Club, $this>
     */
    public function primaryClub(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'primary_club_id');
    }

    /**
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'created_by');
    }

    /**
     * @return HasMany<MemberClubHistory, $this>
     */
    public function clubHistory(): HasMany
    {
        return $this->hasMany(MemberClubHistory::class);
    }

    /**
     * @return HasMany<MemberSubscription, $this>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(MemberSubscription::class);
    }

    /**
     * @return HasMany<FeePayment, $this>
     */
    public function feePayments(): HasMany
    {
        return $this->hasMany(FeePayment::class);
    }

    /**
     * @return MorphMany<Attendance, $this>
     */
    public function attendances(): MorphMany
    {
        return $this->morphMany(Attendance::class, 'subject');
    }
}
