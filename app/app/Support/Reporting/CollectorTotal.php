<?php

declare(strict_types=1);

namespace App\Support\Reporting;

/**
 * One staff member's confirmed collection total for the period, used by the
 * dashboard leaderboard and the staff-collection report (MEP.md 6.11).
 */
final readonly class CollectorTotal
{
    public function __construct(
        public string $name,
        public int $collected,
        public int $count,
    ) {}
}
