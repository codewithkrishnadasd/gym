<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ClubAssignmentStatus;
use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Enums\Permission;
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
 * organisation. A single person (one `users` row / one number+password) may
 * have several `OrganisationUser` rows, one per organisation they belong to
 * — this is what lets one login work across organisations on different
 * domains. See MEP.md 5.3.
 */
#[Fillable(['organisation_id', 'user_id', 'role', 'status', 'permissions', 'navigation_settings', 'club_ids', 'created_by'])]
class OrganisationUser extends Model
{
    /** @use HasFactory<OrganisationUserFactory> */
    use BelongsToOrganisation, HasFactory;

    /**
     * Memoised expansion of `permissions`, invalidated by comparing the raw
     * granted keys — `hasPermission()` is called many times per request from
     * navigation and policies alike.
     *
     * @var array<int, string>
     */
    private array $expandedPermissions = [];

    private ?string $permissionSignature = null;

    protected function casts(): array
    {
        return [
            'role' => MembershipRole::class,
            'status' => MembershipStatus::class,
            'permissions' => 'array',
            'navigation_settings' => 'array',
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

        return in_array($key, $this->effectivePermissions(), true);
    }

    /**
     * The granted keys plus everything they depend on (MEP.md 4.2).
     *
     * Expanding on read rather than trusting the stored row means a set
     * written before a dependency existed — or by a seeder, an import, or a
     * direct database edit — still grants what it needs to work, instead of
     * producing a staff member who can create a member but not open the list
     * they were just added to.
     *
     * @return array<int, string>
     */
    public function effectivePermissions(): array
    {
        $granted = array_keys(array_filter($this->permissions ?? []));
        $signature = implode(',', $granted);

        if ($this->permissionSignature !== $signature) {
            $this->permissionSignature = $signature;
            $this->expandedPermissions = Permission::expand($granted);
        }

        return $this->expandedPermissions;
    }

    /**
     * This person's own phone tab bar, as [route, icon] pairs in order, or
     * null for the built-in choice. Only routes they may see are shown — see Navigation::mobilePrimary().
     *
     * @return array<int, array{route: string, icon: string}>|null
     */
    public function mobileNavigation(): ?array
    {
        /** @var array<int, mixed>|null $items */
        $items = ($this->navigation_settings ?? [])['mobile'] ?? null;

        if (! is_array($items) || $items === []) {
            return null;
        }

        $chosen = [];

        foreach ($items as $item) {
            if (is_array($item) && is_string($item['route'] ?? null) && $item['route'] !== '') {
                $chosen[] = ['route' => $item['route'], 'icon' => is_string($item['icon'] ?? null) ? $item['icon'] : ''];
            }
        }

        return $chosen === [] ? null : array_slice($chosen, 0, 4);
    }

    /**
     * This person's choice of action for the dashboard's floating button, or
     * null for the built-in order.
     */
    public function quickAction(): ?string
    {
        $action = ($this->navigation_settings ?? [])['quick_action'] ?? null;

        return is_string($action) && $action !== '' ? $action : null;
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
