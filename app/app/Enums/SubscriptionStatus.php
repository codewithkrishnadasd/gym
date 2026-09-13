<?php

declare(strict_types=1);

namespace App\Enums;

enum SubscriptionStatus: string
{
    case Active = 'active';
    case Expired = 'expired';
    case Paused = 'paused';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Expired => 'Expired',
            self::Paused => 'Paused',
            self::Cancelled => 'Cancelled',
        };
    }

    /**
     * Semantic badge tone from the design system, so one status never renders
     * two different colours in two different views.
     */
    public function tone(): string
    {
        return match ($this) {
            self::Active => 'positive',
            self::Expired => 'critical',
            self::Paused => 'caution',
            self::Cancelled => 'neutral',
        };
    }
}
