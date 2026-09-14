<?php

declare(strict_types=1);

namespace App\Enums;

enum MembershipStatus: string
{
    case Invited = 'invited';
    case Active = 'active';
    case Suspended = 'suspended';
    /** Stored as "deactivated" so existing rows and audit history stay valid; shown as "Removed". */
    case Deactivated = 'deactivated';

    public function label(): string
    {
        return match ($this) {
            self::Invited => 'Invited',
            self::Active => 'Active',
            self::Suspended => 'Suspended',
            self::Deactivated => 'Removed',
        };
    }

    /**
     * Semantic badge tone from the design system, so one status never renders
     * two different colours in two different views.
     */
    public function tone(): string
    {
        return match ($this) {
            self::Invited => 'info',
            self::Active => 'positive',
            self::Suspended => 'caution',
            self::Deactivated => 'critical',
        };
    }
}
