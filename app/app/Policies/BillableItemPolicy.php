<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * The price list is organisation configuration, alongside plans and financial
 * accounts, and is managed by admins. Staff who can raise invoices pick from
 * it; they do not edit it.
 */
class BillableItemPolicy
{
    use EvaluatesMembership;

    public function manage(User $user): bool
    {
        return $this->isAdmin($user);
    }
}
