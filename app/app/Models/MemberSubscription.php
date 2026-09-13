<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SubscriptionStatus;
use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\MemberSubscriptionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
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
