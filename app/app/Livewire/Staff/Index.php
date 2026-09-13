<?php

declare(strict_types=1);

namespace App\Livewire\Staff;

use App\Enums\ClubAssignmentStatus;
use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\OrganisationUser;
use App\Support\PhoneNumber;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
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
    public string $role = '';

    #[Url]
    public string $club = '';

    public function mount(): void
    {
        $this->authorize('viewAny', OrganisationUser::class);
    }

    public function updated(string $property): void
    {
        if ($property !== 'page') {
            $this->resetPage();
        }
    }

    public function suspend(OrganisationUser $organisationUser): void
    {
        $this->authorize('update', $organisationUser);

        $organisationUser->update(['status' => MembershipStatus::Suspended]);
    }

    public function reactivate(OrganisationUser $organisationUser): void
    {
        $this->authorize('update', $organisationUser);

        $organisationUser->update(['status' => MembershipStatus::Active]);
    }

    public function deactivate(OrganisationUser $organisationUser): void
    {
        $this->authorize('update', $organisationUser);

        $organisationUser->update(['status' => MembershipStatus::Deactivated]);
    }

    /**
     * @return LengthAwarePaginator<int, OrganisationUser>
     */
    protected function members(): LengthAwarePaginator
    {
        $query = OrganisationUser::query()
            ->with([
                'user:id,name,phone',
                'clubAssignments' => fn ($assignments) => $assignments
                    ->where('status', ClubAssignmentStatus::Active)
                    ->with('club:id,name'),
            ])
            ->when($this->search !== '', fn (Builder $query) => $query->whereHas(
                'user',
                fn (Builder $user) => $user->where('name', 'ilike', "%{$this->search}%")
                    ->orWhere('phone', 'ilike', '%'.PhoneNumber::searchable($this->search).'%')
            ))
            ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
            ->when($this->role !== '', fn (Builder $query) => $query->where('role', $this->role))
            ->when($this->club !== '', fn (Builder $query) => $query->whereHas(
                'clubAssignments',
                fn (Builder $assignments) => $assignments
                    ->where('club_id', $this->club)
                    ->where('status', ClubAssignmentStatus::Active)
            ))
            ->latest();

        return $query->paginate(15);
    }

    public function render(): View
    {
        $organisation = $this->organisation();

        return view('livewire.staff.index', [
            'members' => $this->members(),
            'organisation' => $organisation,
            'statuses' => MembershipStatus::cases(),
            'roles' => MembershipRole::cases(),
            'clubs' => $this->accessibleClubs(true),
        ])->layout('components.layouts.app', [
            'heading' => $organisation->term('user_plural'),
        ]);
    }
}
