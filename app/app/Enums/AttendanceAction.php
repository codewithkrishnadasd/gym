<?php

declare(strict_types=1);

namespace App\Enums;

enum AttendanceAction: string
{
    case Present = 'present';
    case Absent = 'absent';
    case Late = 'late';
    case Excused = 'excused';

    public function label(): string
    {
        return match ($this) {
            self::Present => 'Present',
            self::Absent => 'Absent',
            self::Late => 'Late',
            self::Excused => 'Excused',
        };
    }

    /**
     * Semantic badge tone from the design system, so one status never renders
     * two different colours in two different views.
     */
    public function tone(): string
    {
        return match ($this) {
            self::Present => 'positive',
            self::Absent => 'critical',
            self::Late => 'caution',
            self::Excused => 'info',
        };
    }
}
