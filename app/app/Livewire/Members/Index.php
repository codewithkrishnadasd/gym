<?php

declare(strict_types=1);

namespace App\Livewire\Members;

use App\Enums\ClubAssignmentStatus;
use App\Enums\MemberStatus;
use App\Enums\SubscriptionStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Member;
use App\Support\PhoneNumber;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

    #[Url]
    public string $club = '';

    public function mount(): void
    {
        $this->authorize('viewAny', Member::class);
    }

    public function updated(string $property): void
    {
        if ($property !== 'page') {
            $this->resetPage();
        }
    }

    public function archive(Member $member): void
    {
        $this->authorize('archive', $member);

        $member->update(['status' => MemberStatus::Archived]);
    }

    public function restore(Member $member): void
    {
        $this->authorize('restore', $member);

        $member->update(['status' => MemberStatus::Active]);
    }

    /**
     * @return LengthAwarePaginator<int, Member>
     */
    protected function members(): LengthAwarePaginator
    {
        $membership = $this->currentMembership();

        $query = Member::query()
            ->with('primaryClub:id,name')
            // The member's live plan, so the list can show expiry and balance
            // without an N+1 lookup per row (MEP.md 6.6).
            ->with(['subscriptions' => fn ($subscriptions) => $subscriptions
                ->where('status', SubscriptionStatus::Active)
                ->with('plan:id,name')
                ->orderByDesc('end_date')
                ->limit(1)])
            ->when(! $membership->isAdmin(), fn ($query) => $query->whereIn(
                'primary_club_id',
                $membership->clubAssignments()->where('status', ClubAssignmentStatus::Active)->pluck('club_id')
            ))
            ->when($this->search !== '', fn ($query) => $query->where(
                fn ($query) => $query->where('name', 'ilike', "%{$this->search}%")
                    ->orWhere('phone', 'ilike', '%'.PhoneNumber::searchable($this->search).'%')
            ))
            ->when($this->status !== '', fn ($query) => $query->where('status', $this->status))
            ->when($this->club !== '', fn ($query) => $query->where('primary_club_id', $this->club))
            ->orderBy('name');

        return $query->paginate(15);
    }

    public function render(): View
    {
        $organisation = $this->organisation();

        return view('livewire.members.index', [
            'members' => $this->members(),
            'organisation' => $organisation,
            'clubs' => $this->accessibleClubs(true),
            'statuses' => MemberStatus::cases(),
        ])->layout('components.layouts.app', [
            'heading' => $organisation->term('member_plural'),
        ]);
    }
}
