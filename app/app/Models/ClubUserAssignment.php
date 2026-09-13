<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ClubAssignmentStatus;
use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\ClubUserAssignmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['organisation_id', 'club_id', 'organisation_user_id', 'permissions_override', 'status', 'assigned_at', 'ended_at'])]
class ClubUserAssignment extends Model
{
    /** @use HasFactory<ClubUserAssignmentFactory> */
    use BelongsToOrganisation, HasFactory;

    protected function casts(): array
    {
        return [
            'status' => ClubAssignmentStatus::class,
            'permissions_override' => 'array',
            'assigned_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Club, $this>
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function organisationUser(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class);
    }
}
