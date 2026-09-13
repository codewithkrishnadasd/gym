<?php

declare(strict_types=1);

namespace App\Enums;

enum ConfirmationStatus: string
{
    case PendingAdminConfirmation = 'pending_admin_confirmation';
    case Confirmed = 'confirmed';
    case Rejected = 'rejected';
    case Reversed = 'reversed';

    public function label(): string
    {
        return match ($this) {
            self::PendingAdminConfirmation => 'Pending confirmation',
            self::Confirmed => 'Confirmed',
            self::Rejected => 'Rejected',
            self::Reversed => 'Reversed',
        };
    }

    /**
     * Semantic badge tone from the design system, so one status never renders
     * two different colours in two different views.
     */
    public function tone(): string
    {
        return match ($this) {
            self::PendingAdminConfirmation => 'caution',
            self::Confirmed => 'positive',
            self::Rejected => 'critical',
            self::Reversed => 'neutral',
        };
    }
}
