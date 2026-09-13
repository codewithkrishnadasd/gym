<?php

declare(strict_types=1);

namespace App\Enums;

enum OrganisationStatus: string
{
    case Active = 'active';
    case Suspended = 'suspended';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Suspended => 'Suspended',
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
            self::Suspended => 'caution',
            self::Archived => 'neutral',
        };
    }
}
