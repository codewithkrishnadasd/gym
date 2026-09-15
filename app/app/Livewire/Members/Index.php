<?php

declare(strict_types=1);

namespace App\Livewire\Members;

use App\Enums\MemberStatus;
use App\Enums\SubscriptionHealth;
use App\Enums\SubscriptionStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Member;
use App\Models\Plan;
use App\Support\Search;
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

    /**
     * Narrowings reached from a dashboard card rather than the filter bar,
     * so a KPI opens the exact rows it counted: joined in a date range,
     * owing on a plan, or on a plan ending by a date.
     */
    #[Url]
    public string $joinedFrom = '';

    #[Url]
    public string $joinedTo = '';

    #[Url]
    public string $balance = '';

    #[Url]
    public string $endingBy = '';

    /** Members whose current plan is this one; reached from a plan's count. */
    #[Url]
    public string $planId = '';

    public function clearNarrowing(): void
    {
        $this->reset(['joinedFrom', 'joinedTo', 'balance', 'endingBy', 'planId']);
        $this->resetPage();
    }

    /**
     * Human description of the dashboard narrowing in effect, or null.
     */
    public function narrowingLabel(): ?string
    {
        $parts = [];

        if ($this->joinedFrom !== '' || $this->joinedTo !== '') {
            $from = $this->joinedFrom !== '' ? Carbon::parse($this->joinedFrom)->format('d M Y') : '…';
            $to = $this->joinedTo !== '' ? Carbon::parse($this->joinedTo)->format('d M Y') : '…';
            $parts[] = "joined {$from} – {$to}";
        }

        if ($this->balance === 'due') {
            $parts[] = 'owing plan fees';
        }

        if ($this->endingBy !== '') {
            $parts[] = 'plan ending by '.Carbon::parse($this->endingBy)->format('d M Y');
        }

        if ($this->planId !== '') {
            $plan = Plan::query()->find($this->planId);
            $parts[] = 'on the '.($plan->name ?? 'chosen').' plan';
        }

        return $parts === [] ? null : ucfirst(implode(' · ', $parts));
    }

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
        $query = $this->restrictToClubs(Member::query(), 'primary_club_id')
            ->with('primaryClub:id,name')
            // The member's live plan, so the list can show expiry and balance
            // without an N+1 lookup per row (MEP.md 6.6).
            ->with(['subscriptions' => fn ($subscriptions) => $subscriptions
                ->where('status', SubscriptionStatus::Active)
                ->with('plan:id,name')
                ->orderByDesc('end_date')
                ->limit(1)])
            // Name, phone, or reference (MEM-42) — see App\Support\Search.
            ->when($this->search !== '', fn ($query) => Search::apply($query, $this->organisation(), 'member', $this->search))
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
            ->when($this->joinedFrom !== '', fn ($query) => $query->whereDate('joined_at', '>=', $this->joinedFrom))
            ->when($this->joinedTo !== '', fn ($query) => $query->whereDate('joined_at', '<=', $this->joinedTo))
            // Mirrors OrganisationMetrics::outstandingFees(): any live or lapsed
            // term with less paid than is due.
            ->when($this->balance === 'due', fn ($query) => $query->whereHas('subscriptions', fn ($subscriptions) => $subscriptions
                ->whereIn('status', [SubscriptionStatus::Active, SubscriptionStatus::Expired])
                ->whereColumn('amount_paid_minor', '<', 'amount_due_minor')))
            // Mirrors OrganisationMetrics::expiringSubscriptions(): active terms
            // ending between today and the given date.
            ->when($this->endingBy !== '', fn ($query) => $query->whereHas('subscriptions', fn ($subscriptions) => $subscriptions
                ->where('status', SubscriptionStatus::Active)
                ->whereBetween('end_date', [$this->today()->toDateString(), $this->endingBy])))
            // Mirrors Plans\Index's "Active" count: a live term on that plan.
            ->when($this->planId !== '', fn ($query) => $query->whereHas('subscriptions', fn ($subscriptions) => $subscriptions
                ->where('status', SubscriptionStatus::Active)
                ->where('plan_id', $this->planId)))
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
            'narrowing' => $this->narrowingLabel(),
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
