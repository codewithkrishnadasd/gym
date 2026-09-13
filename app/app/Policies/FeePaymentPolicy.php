<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\ConfirmationStatus;
use App\Models\FeePayment;
use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * The payment lifecycle is the sharpest permission boundary in the product
 * (MEP.md Sections 5.11 and 8.3):
 *
 *   - staff with `fees.collect` may submit payments for their assigned clubs,
 *     which are always created pending;
 *   - only an admin may confirm, reject, or reverse one;
 *   - nobody may edit a payment that has left the pending state — corrections
 *     are made by reversing and recording a replacement.
 */
class FeePaymentPolicy
{
    use EvaluatesMembership;

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user)
            || $this->hasPermission($user, 'fees.collect')
            || $this->hasPermission($user, 'fees.view_own');
    }

    /**
     * A staff user sees their own collections; an admin sees everything in
     * the organisation.
     */
    public function view(User $user, FeePayment $payment): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        $membership = $this->membership($user);

        return $membership !== null
            && $payment->collected_by === $membership->id
            && $this->hasPermission($user, 'fees.view_own');
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user) || $this->hasPermission($user, 'fees.collect');
    }

    public function createForClub(User $user, ?int $clubId): bool
    {
        return $this->create($user) && $this->clubAllowed($user, $clubId);
    }

    public function confirm(User $user, FeePayment $payment): bool
    {
        return $this->isAdmin($user)
            && $payment->confirmation_status === ConfirmationStatus::PendingAdminConfirmation;
    }

    public function reject(User $user, FeePayment $payment): bool
    {
        return $this->confirm($user, $payment);
    }

    public function reverse(User $user, FeePayment $payment): bool
    {
        return $this->isAdmin($user)
            && $payment->confirmation_status === ConfirmationStatus::Confirmed;
    }

    /**
     * Deliberately narrow: a submitted payment's amount is immutable, so
     * there is no general update path at all once it leaves pending.
     */
    public function update(User $user, FeePayment $payment): bool
    {
        return $this->isAdmin($user)
            && $payment->confirmation_status === ConfirmationStatus::PendingAdminConfirmation;
    }

    public function notify(User $user, FeePayment $payment): bool
    {
        return $this->isAdmin($user) && $payment->isConfirmed();
    }
}
