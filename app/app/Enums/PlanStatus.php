<?php

declare(strict_types=1);

namespace App\Enums;

enum PlanStatus: string
{
    case Active = 'active';
    /** Stored as "archived" so existing rows and audit history stay valid; shown to operators as "Removed". */
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Archived => 'Removed',
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
            self::Archived => 'neutral',
        };
    }
}
