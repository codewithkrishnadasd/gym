<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PlatformAdminFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * A "root user" — a platform-level operator who can create organisations
 * and assign their first admin. Entirely separate from the tenant `users`
 * table and never reachable through a tenant domain. See MEP.md 3.3.
 */
#[Fillable(['name', 'phone', 'password'])]
#[Hidden(['password', 'remember_token'])]
class PlatformAdmin extends Authenticatable
{
    /** @use HasFactory<PlatformAdminFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * @return HasMany<Organisation, $this>
     */
    public function organisationsCreated(): HasMany
    {
        return $this->hasMany(Organisation::class, 'created_by');
    }
}
