<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Invoice;
use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * Invoices follow the same club boundary as the members they are raised
 * against: staff see and raise invoices for their own clubs, an admin for all.
 *
 * Voiding stays admin-only. It is the one billing action that can make money
 * already collected look uncollected, so it sits with the same people who can
 * reverse a payment.
 */
class InvoicePolicy
{
    use EvaluatesMembership;

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user) || $this->hasPermission($user, Permission::BillingView->value);
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $this->viewAny($user) && $this->clubAllowed($user, $invoice->club_id);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user) || $this->hasPermission($user, Permission::BillingCreate->value);
    }

    public function createForClub(User $user, ?int $clubId): bool
    {
        return $this->create($user) && $this->clubAllowed($user, $clubId);
    }

    public function void(User $user, Invoice $invoice): bool
    {
        return $this->isAdmin($user) && $invoice->isOpen();
    }
}
