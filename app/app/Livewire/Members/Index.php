<?php

declare(strict_types=1);

namespace App\Livewire\Members;

use App\Enums\ClubAssignmentStatus;
use App\Enums\MemberStatus;
use App\Enums\SubscriptionHealth;
use App\Enums\SubscriptionStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Member;
use App\Support\PhoneNumber;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
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

    #[Url]
    public string $club = '';

    /** Derived plan state — see App\Enums\SubscriptionHealth. */
    #[Url]
    public string $plan = '';

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
            // Removed rows are only ever listed when they are asked for by
            // name. Leaving them in the default view makes the list grow
            // forever and puts dead records next to live ones.
            ->when(
                $this->status !== '',
                fn ($query) => $query->where('status', $this->status),
                fn ($query) => $query->where('status', '!=', MemberStatus::Archived),
            )
            ->when($this->club !== '', fn ($query) => $query->where('primary_club_id', $this->club))
            ->when($this->plan !== '', fn ($query) => $this->constrainByPlanHealth($query))
            ->orderBy('name');

        return $query->paginate(15);
    }

    /**
     * Filters by what a member's plan is doing today rather than by the stored
     * subscription status, which never changes on its own when a term runs out.
     *
     * @param  Builder<Member>  $query
     * @return Builder<Member>
     */
    private function constrainByPlanHealth(Builder $query): Builder
    {
        $health = SubscriptionHealth::tryFrom($this->plan);

        if ($health === null) {
            return $query;
        }

        $today = $this->today();

        // "No plan" is the absence of a live term, so it is the one case that
        // cannot be a constraint on a subscription row.
        if ($health === SubscriptionHealth::None) {
            return $query->whereDoesntHave(
                'subscriptions',
                fn (Builder $subscriptions) => $subscriptions->where('status', SubscriptionStatus::Active),
            );
        }

        return $query->whereHas(
            'subscriptions',
            fn (Builder $subscriptions) => $subscriptions->inHealth($health, $today),
        );
    }

    private function today(): CarbonInterface
    {
        return Carbon::today($this->organisation()->timezone);
    }

    public function render(): View
    {
        $organisation = $this->organisation();

        return view('livewire.members.index', [
            'members' => $this->members(),
            'organisation' => $organisation,
            'clubs' => $this->accessibleClubs(true),
            'statuses' => MemberStatus::cases(),
            'planStates' => SubscriptionHealth::filterable(),
            'today' => $this->today(),
        ])->layout('components.layouts.app', [
            'heading' => $organisation->term('member_plural'),
        ]);
    }
}
