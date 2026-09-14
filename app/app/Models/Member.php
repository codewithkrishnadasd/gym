<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ConfirmationStatus;
use App\Enums\MemberStatus;
use App\Enums\PaymentPurpose;
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
