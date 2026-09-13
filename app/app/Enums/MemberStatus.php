<?php

declare(strict_types=1);

namespace App\Enums;

enum MemberStatus: string
{
    case Active = 'active';
    case Paused = 'paused';
    case Inactive = 'inactive';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Paused => 'Paused',
            self::Inactive => 'Inactive',
            self::Archived => 'Archived',
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
            self::Paused => 'caution',
            self::Inactive => 'neutral',
            self::Archived => 'neutral',
        };
    }
}
