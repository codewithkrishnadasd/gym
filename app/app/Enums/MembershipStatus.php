<?php

declare(strict_types=1);

namespace App\Enums;

enum MembershipStatus: string
{
    case Invited = 'invited';
    case Active = 'active';
    case Suspended = 'suspended';
    case Deactivated = 'deactivated';

    public function label(): string
    {
        return match ($this) {
            self::Invited => 'Invited',
            self::Active => 'Active',
            self::Suspended => 'Suspended',
            self::Deactivated => 'Deactivated',
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
