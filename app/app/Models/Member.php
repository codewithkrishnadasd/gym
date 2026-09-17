<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ConfirmationStatus;
use App\Enums\InvoiceStatus;
use App\Enums\MemberStatus;
use App\Enums\PaymentPurpose;
use App\Enums\SubscriptionStatus;
use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\MemberFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Expression;

#[Fillable([
    'organisation_id', 'primary_club_id', 'name', 'phone', 'date_of_birth',
    'gender', 'photo_path', 'address', 'emergency_contact', 'joined_at', 'status',
    'notes', 'admission_fee_minor', 'created_by',
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
            'admission_fee_minor' => 'integer',
            'admission_discount_minor' => 'integer',
            'admission_paid_minor' => 'integer',
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
     * The term that ends last among every plan that was not cancelled —
     * active, expired or paused. A renewal starts the day after it, so
     * consecutive terms never overlap, whichever order they were bought in.
     */
    public function latestTerm(): ?MemberSubscription
    {
        return $this->subscriptions()
            ->with('plan:id,name')
            ->where('status', '!=', SubscriptionStatus::Cancelled)
            ->orderByDesc('end_date')
            ->orderByDesc('id')
            ->first();
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

    /**
     * @return HasMany<Invoice, $this>
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Everything this member still owes, in one figure: balances on live or
     * lapsed plan terms, what is left of the admission fee, and open
     * invoices. The one number the desk needs when the member walks in, and
     * the same one the list column, the member page and messages show.
     */
    public function outstandingMinor(): int
    {
        $plans = (int) $this->subscriptions()
            ->whereIn('status', [SubscriptionStatus::Active, SubscriptionStatus::Expired])
            ->whereColumn('amount_paid_minor', '<', 'amount_due_minor')
            ->selectRaw('COALESCE(SUM(amount_due_minor - amount_paid_minor), 0) AS due')
            ->value('due');

        $invoices = (int) $this->invoices()
            ->whereIn('status', [InvoiceStatus::Issued, InvoiceStatus::PartiallyPaid])
            ->selectRaw('COALESCE(SUM(GREATEST(0, total_minor - discount_minor - paid_minor)), 0) AS due')
            ->value('due');

        return $plans + $invoices + $this->admissionOutstandingMinor();
    }

    /**
     * Loads the parts of outstandingMinor() alongside each row of a list, so
     * listedOutstandingMinor() answers without a query per member.
     *
     * @param  Builder<Member>  $query
     * @return Builder<Member>
     */
    public function scopeWithOutstanding(Builder $query): Builder
    {
        return $query
            ->withSum(['subscriptions as plan_due_minor' => fn ($subscriptions) => $subscriptions
                ->whereIn('status', [SubscriptionStatus::Active, SubscriptionStatus::Expired])
                ->whereColumn('amount_paid_minor', '<', 'amount_due_minor'),
            ], new Expression('amount_due_minor - amount_paid_minor'))
            ->withSum(['invoices as invoice_due_minor' => fn ($invoices) => $invoices
                ->whereIn('status', [InvoiceStatus::Issued, InvoiceStatus::PartiallyPaid]),
            ], new Expression('GREATEST(0, total_minor - discount_minor - paid_minor)'));
    }

    /**
     * Members who owe anything at all — the same three sources as
     * outstandingMinor().
     *
     * @param  Builder<Member>  $query
     * @return Builder<Member>
     */
    public function scopeOwing(Builder $query): Builder
    {
        return $query->where(fn (Builder $members) => $members
            ->whereHas('subscriptions', fn ($subscriptions) => $subscriptions
                ->whereIn('status', [SubscriptionStatus::Active, SubscriptionStatus::Expired])
                ->whereColumn('amount_paid_minor', '<', 'amount_due_minor'))
            ->orWhereHas('invoices', fn ($invoices) => $invoices
                ->whereIn('status', [InvoiceStatus::Issued, InvoiceStatus::PartiallyPaid])
                ->whereRaw('total_minor - discount_minor - paid_minor > 0'))
            ->orWhereRaw('admission_fee_minor - admission_discount_minor - admission_paid_minor > 0'));
    }

    /** outstandingMinor() from the sums scopeWithOutstanding() loaded. */
    public function listedOutstandingMinor(): int
    {
        return (int) ($this->getAttribute('plan_due_minor') ?? 0)
            + (int) ($this->getAttribute('invoice_due_minor') ?? 0)
            + $this->admissionOutstandingMinor();
    }

    /**
     * What is still owed on the admission fee this member joined under.
     */
    public function admissionOutstandingMinor(): int
    {
        return max(0, $this->admission_fee_minor - $this->admission_discount_minor - $this->admission_paid_minor);
    }

    public function owesAdmissionFee(): bool
    {
        return $this->admissionOutstandingMinor() > 0;
    }

    /**
     * Credits a confirmed admission payment: money received and any discount
     * given at the counter, both cumulative.
     */
    public function applyAdmissionPayment(int $amountMinor, int $discountMinor = 0): void
    {
        $this->forceFill([
            'admission_paid_minor' => $this->admission_paid_minor + $amountMinor,
            'admission_discount_minor' => $this->admission_discount_minor + $discountMinor,
        ])->save();
    }

    public function withdrawAdmissionPayment(int $amountMinor, int $discountMinor = 0): void
    {
        $this->forceFill([
            'admission_paid_minor' => max(0, $this->admission_paid_minor - $amountMinor),
            'admission_discount_minor' => max(0, $this->admission_discount_minor - $discountMinor),
        ])->save();
    }

    /**
     * Confirmed money paid without a link to anything.
     */
    public function unlinkedPaidMinor(): int
    {
        return (int) $this->feePayments()
            ->where('confirmation_status', ConfirmationStatus::Confirmed)
            ->where('purpose', PaymentPurpose::Other)
            ->sum('amount_minor');
    }

    /**
     * How much of that unlinked money has since been applied to a plan,
     * invoice or admission fee through later payments.
     */
    public function creditAppliedMinor(): int
    {
        return (int) $this->feePayments()
            ->where('confirmation_status', ConfirmationStatus::Confirmed)
            ->sum('credit_applied_minor');
    }

    /**
     * Unlinked money still available to apply. Pending applications are
     * counted too, so two collections cannot both spend the same credit while
     * the first awaits confirmation.
     */
    public function unlinkedCreditMinor(): int
    {
        $reserved = (int) $this->feePayments()
            ->where('confirmation_status', ConfirmationStatus::PendingAdminConfirmation)
            ->sum('credit_applied_minor');

        return max(0, $this->unlinkedPaidMinor() - $this->creditAppliedMinor() - $reserved);
    }
}
