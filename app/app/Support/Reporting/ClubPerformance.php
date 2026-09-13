<?php

declare(strict_types=1);

namespace App\Support\Reporting;

/**
 * One club's figures for a reporting period. A named type rather than an
 * anonymous object so views, exports, and the PDF renderer all agree on the
 * shape.
 */
final readonly class ClubPerformance
{
    public function __construct(
        public string $club,
        public int $revenue,
        public int $expenses,
        public int $members,
        public int $attendance,
    ) {}

    public function net(): int
    {
        return $this->revenue - $this->expenses;
    }
}
