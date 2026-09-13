<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ClubAssignmentStatus;
use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\OrganisationUserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Tenant-specific membership and authority for one `users` row inside one
 * organisation. A single person (one `users` row / one email+password) may
 * have several `OrganisationUser` rows, one per organisation they belong to
 * — this is what lets one login work across organisations on different
 * domains. See MEP.md 5.3.
 */
#[Fillable(['organisation_id', 'user_id', 'role', 'status', 'permissions', 'club_ids', 'created_by'])]
class OrganisationUser extends Model
{
    /** @use HasFactory<OrganisationUserFactory> */
    use BelongsToOrganisation, HasFactory;

    protected function casts(): array
    {
        return [
            'role' => MembershipRole::class,
            'status' => MembershipStatus::class,
            'permissions' => 'array',
            'club_ids' => 'array',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<self, $this>
     */
    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(self::class, 'created_by');
    }

    /**
     * @return HasMany<ClubUserAssignment, $this>
     */
    public function clubAssignments(): HasMany
    {
        return $this->hasMany(ClubUserAssignment::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === MembershipRole::Admin;
    }

    public function isActive(): bool
    {
        return $this->status === MembershipStatus::Active;
    }

    public function hasPermission(string $key): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return (bool) ($this->permissions[$key] ?? false);
    }

    /**
     * Active club assignments are the source of truth for club access —
     * `club_ids` is a denormalised read optimisation only. See MEP.md 5.5.
     *
     * @return array<int, int>
     */
    public function activeClubIds(): array
    {
        return $this->clubAssignments()
            ->where('status', ClubAssignmentStatus::Active)
            ->pluck('club_id')
            ->all();
    }

    public function hasClubAccess(int $clubId): bool
    {
        return $this->isAdmin() || in_array($clubId, $this->activeClubIds(), true);
    }

    /**
     * @return MorphMany<Attendance, $this>
     */
    public function attendances(): MorphMany
    {
        return $this->morphMany(Attendance::class, 'subject');
    }
}
