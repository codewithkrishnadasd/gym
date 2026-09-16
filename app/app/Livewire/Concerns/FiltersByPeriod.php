<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Support\Reporting\ReportPeriod;

/**
 * The date-range control shared by the dashboard, reports and the club page:
 * a row of presets ("This month") plus a custom range. Expects the component
 * to declare `$range`, `$from` and `$to` as URL-bound strings.
 */
trait FiltersByPeriod
{
    public function applyPreset(string $key, bool $resetCustom = true): void
    {
        // "custom" arriving from the URL keeps the dates it came with; with
        // none, the default month is the sane starting point.
        if ($key === 'custom' && ! $resetCustom && $this->from !== '' && $this->to !== '') {
            return;
        }

        $presets = ReportPeriod::presets($this->organisation()->timezone);

        if (! isset($presets[$key])) {
            $key = 'month';
        }

        $this->range = $key;

        if ($resetCustom || $this->from === '' || $this->to === '') {
            $this->from = $presets[$key]['from'];
            $this->to = $presets[$key]['to'];
        }
    }

    /**
     * Switches to a custom range, keeping the current dates as its starting
     * point; the picker then narrows them.
     */
    public function startCustom(): void
    {
        $this->range = 'custom';
    }

    /**
     * Both ends at once, from the range picker, as one request.
     */
    public function setRange(string $from, string $to): void
    {
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
            return;
        }

        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        $this->from = $from;
        $this->to = $to;
        $this->range = 'custom';

        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    public function updatedFrom(): void
    {
        $this->range = 'custom';
    }

    public function updatedTo(): void
    {
        $this->range = 'custom';
    }
}
