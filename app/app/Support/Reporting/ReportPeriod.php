<?php

declare(strict_types=1);

namespace App\Support\Reporting;

use Illuminate\Support\Carbon;

/**
 * A resolved reporting window plus the immediately preceding window of equal
 * length, which is what every "vs previous period" comparison comes from.
 *
 * Day boundaries are computed in the organisation's timezone, because that is
 * what decides which day a payment or attendance mark belongs to
 * (MEP.md 10, 14).
 */
final readonly class ReportPeriod
{
    public function __construct(
        public Carbon $from,
        public Carbon $to,
        public string $timezone,
    ) {}

    public static function fromStrings(?string $from, ?string $to, string $timezone): self
    {
        $end = $to ? Carbon::parse($to, $timezone) : Carbon::today($timezone);
        $start = $from ? Carbon::parse($from, $timezone) : $end->copy()->startOfMonth();

        // A reversed range is corrected rather than returning nothing.
        if ($start->gt($end)) {
            [$start, $end] = [$end, $start];
        }

        return new self($start->startOfDay(), $end->endOfDay(), $timezone);
    }

    /**
     * @return array<string, array{label: string, from: string, to: string}>
     */
    public static function presets(string $timezone): array
    {
        $today = Carbon::today($timezone);

        return [
            'today' => ['label' => 'Today', 'from' => $today->toDateString(), 'to' => $today->toDateString()],
            'week' => ['label' => 'This week', 'from' => $today->copy()->startOfWeek()->toDateString(), 'to' => $today->toDateString()],
            'month' => ['label' => 'This month', 'from' => $today->copy()->startOfMonth()->toDateString(), 'to' => $today->toDateString()],
            'last_month' => [
                'label' => 'Last month',
                'from' => $today->copy()->subMonthNoOverflow()->startOfMonth()->toDateString(),
                'to' => $today->copy()->subMonthNoOverflow()->endOfMonth()->toDateString(),
            ],
            'quarter' => ['label' => 'This quarter', 'from' => $today->copy()->startOfQuarter()->toDateString(), 'to' => $today->toDateString()],
            'year' => ['label' => 'This year', 'from' => $today->copy()->startOfYear()->toDateString(), 'to' => $today->toDateString()],
        ];
    }

    public function days(): int
    {
        return (int) $this->from->diffInDays($this->to) + 1;
    }

    /**
     * The equally-sized window ending the day before this one starts.
     */
    public function previous(): self
    {
        $length = $this->days();

        return new self(
            $this->from->copy()->subDays($length)->startOfDay(),
            $this->from->copy()->subDay()->endOfDay(),
            $this->timezone,
        );
    }

    /**
     * Long ranges are bucketed by month so a trend chart never renders
     * hundreds of unreadable daily points.
     */
    public function grouping(): string
    {
        return $this->days() > 92 ? 'month' : 'day';
    }

    public function label(): string
    {
        return $this->from->format('d M Y').' – '.$this->to->format('d M Y');
    }
}
