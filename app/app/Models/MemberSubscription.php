<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SubscriptionHealth;
use App\Enums\SubscriptionStatus;
use App\Models\Concerns\BelongsToOrganisation;
use Carbon\CarbonInterface;
use Database\Factories\MemberSubscriptionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['organisation_id', 'member_id', 'club_id', 'plan_id', 'start_date', 'end_date', 'amount_due_minor', 'amount_paid_minor', 'status'])]
class MemberSubscription extends Model
{
    /** @use HasFactory<MemberSubscriptionFactory> */
    use BelongsToOrganisation, HasFactory;

    protected function casts(): array
    {
        return [
            'status' => SubscriptionStatus::class,
            'start_date' => 'date',
            'end_date' => 'date',
            'amount_due_minor' => 'integer',
            'amount_paid_minor' => 'integer',
        ];
    }

    /**
     * What this term is doing today, which is not the same as the `status`
     * column — nothing writes to that when a term simply runs out. See
     * App\Enums\SubscriptionHealth.
     */
    public function health(CarbonInterface $today): SubscriptionHealth
    {
        return SubscriptionHealth::for($this, $today);
    }

    /**
     * Constrains a query to terms in a given derived state. Kept here so the
     * member list filter and the badges cannot drift apart: both go through
     * SubscriptionHealth::WARNING_DAYS.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeInHealth(Builder $query, SubscriptionHealth $health, CarbonInterface $today): Builder
    {
        $horizon = $today->copy()->addDays(SubscriptionHealth::WARNING_DAYS);

        return match ($health) {
            SubscriptionHealth::Expired => $query
                ->where('status', SubscriptionStatus::Active)
                ->whereDate('end_date', '<', $today),
            SubscriptionHealth::ExpiringSoon => $query
                ->where('status', SubscriptionStatus::Active)
                ->whereDate('end_date', '>=', $today)
                ->whereDate('end_date', '<=', $horizon),
            SubscriptionHealth::Active => $query
                ->where('status', SubscriptionStatus::Active)
                ->whereDate('end_date', '>', $horizon),
            SubscriptionHealth::Paused => $query->where('status', SubscriptionStatus::Paused),
            SubscriptionHealth::Cancelled => $query->where('status', SubscriptionStatus::Cancelled),
            // "No plan" is the absence of a row, so it cannot be expressed as a
            // constraint on one — callers use whereDoesntHave instead.
            SubscriptionHealth::None => $query->whereRaw('1 = 0'),
        };
    }

    /**
     * @return BelongsTo<Member, $this>
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * @return BelongsTo<Club, $this>
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * @return BelongsTo<Plan, $this>
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * @return HasMany<FeePayment, $this>
     */
    public function feePayments(): HasMany
    {
        return $this->hasMany(FeePayment::class, 'subscription_id');
    }

    /**
     * Applies a confirmed payment to this subscription's paid amount. Callers
     * are responsible for wrapping this in a DB::transaction() alongside the
     * payment status change — see MEP.md 5.11.1.
     */
    public function applyPayment(int $amountMinor): void
    {
        $this->increment('amount_paid_minor', $amountMinor);
    }
}
