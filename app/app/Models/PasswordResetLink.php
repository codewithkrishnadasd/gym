<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A single-use password reset link. Deliberately not tenant-scoped by the
 * global scope: the link is redeemed by someone who is not signed in, on a
 * request where no membership has been resolved yet, so every lookup here
 * filters on `organisation_id` explicitly.
 */
#[Fillable([
    'organisation_id', 'user_id', 'token_hash', 'expires_at',
    'issued_by_organisation_user_id', 'issued_by_platform_admin_id',
])]
class PasswordResetLink extends Model
{
    use MassPrunable;

    const UPDATED_AT = null;

    /**
     * Spent and expired links are deleted a week after they stop working. They
     * cannot be redeemed, the audit trail records that a link was issued
     * independently of this table, and keeping hashes of dead tokens around
     * only grows the row a support query has to sift through.
     *
     * @return Builder<static>
     */
    public function prunable(): Builder
    {
        return static::query()
            ->where('expires_at', '<', now()->subWeek())
            ->orWhere('used_at', '<', now()->subWeek());
    }

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    public function isRedeemable(): bool
    {
        return $this->used_at === null && $this->expires_at->isFuture();
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeOutstanding(Builder $query): Builder
    {
        return $query->whereNull('used_at')->where('expires_at', '>', now());
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Organisation, $this>
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
}
