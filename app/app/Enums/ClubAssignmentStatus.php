<?php

declare(strict_types=1);

namespace App\Enums;

enum ClubAssignmentStatus: string
{
    case Active = 'active';
    case Ended = 'ended';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Ended => 'Ended',
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
            self::Ended => 'neutral',
        };
    }
}
