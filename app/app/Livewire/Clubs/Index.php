<?php

declare(strict_types=1);

namespace App\Livewire\Clubs;

use App\Enums\AttendanceAction;
use App\Enums\ClubAssignmentStatus;
use App\Enums\ClubStatus;
use App\Enums\ConfirmationStatus;
use App\Enums\MemberStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Club;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use ResolvesMembership, WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = '';

    public function mount(): void
    {
        $this->authorize('viewAny', Club::class);
    }

    public function archive(Club $club): void
    {
        $this->authorize('archive', $club);

        $club->update(['status' => ClubStatus::Archived]);
    }

    public function restore(Club $club): void
    {
        $this->authorize('restore', $club);

        $club->update(['status' => ClubStatus::Active]);
    }

    /**
     * @return LengthAwarePaginator<int, Club>
     */
    protected function clubs(): LengthAwarePaginator
    {
        $membership = $this->currentMembership();

        $monthStart = Carbon::today($this->organisation()->timezone)->startOfMonth()->toDateString();

        $query = Club::query()
            // List-level KPIs required by MEP.md 6.4, computed as subqueries
            // so the list stays one round trip regardless of club count.
            ->withCount([
                'members as members_count' => fn ($members) => $members->where('status', MemberStatus::Active),
                'userAssignments as active_staff_count' => fn ($assignments) => $assignments
                    ->where('status', ClubAssignmentStatus::Active),
                'attendances as attendance_count' => fn ($attendances) => $attendances
                    ->whereDate('attendance_date', '>=', $monthStart)
                    ->whereIn('action', [AttendanceAction::Present, AttendanceAction::Late]),
            ])
            ->withSum([
                'feePayments as revenue_minor' => fn ($payments) => $payments
                    ->where('confirmation_status', ConfirmationStatus::Confirmed)
                    ->whereDate('payment_date', '>=', $monthStart),
            ], 'amount_minor')
            ->when(! $membership->isAdmin(), fn ($query) => $query->whereHas(
                'userAssignments',
                fn ($assignment) => $assignment->where('organisation_user_id', $membership->id)
                    ->where('status', ClubAssignmentStatus::Active)
            ))
            ->when($this->search !== '', fn ($query) => $query->where(
                fn ($query) => $query->where('name', 'ilike', "%{$this->search}%")
                    ->orWhere('code', 'ilike', "%{$this->search}%")
            ))
            // Removed rows only appear when explicitly filtered for.
            ->when(
                $this->status !== '',
                fn ($query) => $query->where('status', $this->status),
                fn ($query) => $query->where('status', '!=', ClubStatus::Archived),
            )
            ->orderBy('name');

        return $query->paginate(15);
    }

    public function updated(string $property): void
    {
        if ($property !== 'page') {
            $this->resetPage();
        }
    }

    public function render(): View
    {
        $organisation = $this->organisation();

        return view('livewire.clubs.index', [
            'clubs' => $this->clubs(),
            'organisation' => $organisation,
            'statuses' => ClubStatus::cases(),
        ])->layout('components.layouts.app', [
            'heading' => $organisation->term('club_plural'),
        ]);
    }
}
