<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * The single global authentication identity. One `users` row can back
 * memberships (via `organisationUsers`) in many organisations, which is what
 * lets one email/password sign in to any organisation the person belongs to
 * — even organisations on entirely different domains. See MEP.md 5.3.
 */
#[Fillable(['name', 'email', 'phone', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @return HasMany<OrganisationUser, $this>
     */
    public function organisationUsers(): HasMany
    {
        return $this->hasMany(OrganisationUser::class);
    }

    public function membershipFor(Organisation $organisation): ?OrganisationUser
    {
        return $this->organisationUsers()
            ->where('organisation_id', $organisation->id)
            ->first();
    }
}
