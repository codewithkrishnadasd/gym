<?php

declare(strict_types=1);

namespace App\Livewire\Attendance;

use App\Enums\AttendanceAction;
use App\Enums\AttendanceSubjectType;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

/**
 * One person's attendance, read at a glance: the month as a calendar with
 * each day coloured by what was marked, a few figures above it (visits this
 * month, rate over twelve weeks, current streak, last visit), how visits fall
 * across the week, and the most recent marks. Mounted on a member's page and
 * a staff member's page alike; the month can be stepped back and forth.
 */
class Report extends Component
{
    use ResolvesMembership;

    public string $subjectType = 'member';

    public int $subjectId = 0;

    /** First day of the month on show, ISO. */
    public string $month = '';

    public function mount(string $subjectType, int $subjectId): void
    {
        $this->subjectType = $subjectType === 'user' ? 'user' : 'member';
        $this->subjectId = $subjectId;
        $this->month = $this->today()->startOfMonth()->toDateString();
    }

    public function shiftMonth(int $months): void
    {
        $next = Carbon::parse($this->month)->addMonths($months)->startOfMonth();

        // Nothing is marked in the future; the current month is the limit.
        if ($next->gt($this->today()->startOfMonth())) {
            return;
        }

        $this->month = $next->toDateString();
    }

    public function thisMonth(): void
    {
        $this->month = $this->today()->startOfMonth()->toDateString();
    }

    private function today(): Carbon
    {
        return Carbon::today($this->organisation()->timezone);
    }

    /**
     * @return Builder<Attendance>
     */
    private function marks(): Builder
    {
        return Attendance::query()
            ->where('subject_type', ($this->subjectType === 'user' ? AttendanceSubjectType::User : AttendanceSubjectType::Member)->value)
            ->where('subject_id', $this->subjectId);
    }

    public function render(): View
    {
        $today = $this->today();
        $monthStart = Carbon::parse($this->month)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();
        $twelveWeeksAgo = $today->copy()->subWeeks(12)->startOfDay();

        /** @var Collection<string, Attendance> $recent */
        $recent = $this->marks()
            ->with('club:id,name')
            ->whereDate('attendance_date', '>=', min($twelveWeeksAgo, $monthStart)->toDateString())
            ->orderByDesc('attendance_date')
            ->get()
            ->keyBy(fn (Attendance $mark): string => $mark->attendance_date->toDateString());

        $present = fn (Attendance $mark): bool => in_array($mark->action, [AttendanceAction::Present, AttendanceAction::Late], true);

        $inMonth = $recent->filter(fn (Attendance $mark): bool => $mark->attendance_date->between($monthStart, $monthEnd));
        $inWindow = $recent->filter(fn (Attendance $mark): bool => $mark->attendance_date->gte($twelveWeeksAgo));
        $windowPresent = $inWindow->filter($present)->count();

        // Consecutive marked-present days ending today or yesterday.
        $streak = 0;
        $cursor = $recent->has($today->toDateString()) ? $today->copy() : $today->copy()->subDay();

        while (($mark = $recent->get($cursor->toDateString())) !== null && $present($mark)) {
            $streak++;
            $cursor->subDay();
        }

        // Visits by weekday over the window, Monday first.
        $byWeekday = array_fill(0, 7, 0);

        foreach ($inWindow->filter($present) as $mark) {
            $byWeekday[($mark->attendance_date->dayOfWeekIso) - 1]++;
        }

        $lastVisit = $recent->filter($present)->first();

        return view('livewire.attendance.report', [
            'organisation' => $this->organisation(),
            'today' => $today,
            'monthStart' => $monthStart,
            'canGoForward' => $monthStart->lt($today->copy()->startOfMonth()),
            'marks' => $recent,
            'monthVisits' => $inMonth->filter($present)->count(),
            'monthMarked' => $inMonth->count(),
            'windowRate' => $inWindow->isEmpty() ? null : (int) round($windowPresent / $inWindow->count() * 100),
            'windowVisits' => $windowPresent,
            'streak' => $streak,
            'lastVisit' => $lastVisit,
            'byWeekday' => $byWeekday,
            'weekdayMax' => max(1, max($byWeekday)),
            'recentList' => $recent->take(8),
            'actions' => AttendanceAction::cases(),
        ]);
    }
}
