<?php

declare(strict_types=1);

namespace App\Livewire\Staff;

use App\Actions\Auth\IssuePasswordResetLink;
use App\Actions\Notifications\CreateActionNotification;
use App\Enums\ClubAssignmentStatus;
use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Enums\NotificationActionType;
use App\Enums\NotificationEntityType;
use App\Enums\NotificationRecipientType;
use App\Livewire\Concerns\LazyPage;
use App\Livewire\Concerns\LoadsMore;
use App\Livewire\Concerns\RemembersFilters;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\AuditEvent;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Support\Listing\Slice;
use App\Support\Search;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Defer;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Defer]
class Index extends Component
{
    use LazyPage, LoadsMore, RemembersFilters, ResolvesMembership;

    protected function pageSize(): int
    {
        return 15;
    }

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

    /**
     * Hands the person a one-time link instead of a password an admin has read
     * and could reuse (MEP.md 3.2). The link itself is only ever shown through
     * the WhatsApp panel, which is also where it can be copied — it is not
     * written to the flash message or the audit trail.
     */
    public function sendResetLink(OrganisationUser $organisationUser): void
    {
        $this->authorize('issuePasswordResetLink', $organisationUser);

        $organisation = $this->organisation();
        $actor = $this->currentMembership();

        /** @var User $account */
        $account = $organisationUser->user;

        $issued = app(IssuePasswordResetLink::class)->handle($organisation, $account, $actor);

        AuditEvent::record($organisationUser, 'user.password_reset_link_issued', $actor, null, [
            'expires_at' => $issued->link->expires_at->toIso8601String(),
        ]);

        $notification = app(CreateActionNotification::class)->handle(
            organisation: $organisation,
            type: NotificationActionType::PasswordResetLink,
            recipientType: NotificationRecipientType::User,
            recipientId: $organisationUser->id,
            recipientName: $account->name,
            recipientPhone: $account->phone,
            entityType: NotificationEntityType::User,
            entityId: $organisationUser->id,
            actor: $actor,
            // The link's own id, so re-issuing produces a fresh message rather
            // than reusing the snapshot of a link that no longer works.
            operationId: 'password-reset-'.$issued->link->id,
            context: [
                'resetUrl' => $issued->url,
                'expiresIn' => $issued->expiresLabel(),
            ],
        );

        // No flash notice: the panel below carries the message, the expiry and
        // the button that actually sends it. A green banner saying the same
        // thing would only push the useful part further down the page.
        if ($notification !== null) {
            $this->dispatch('notification-created', notificationId: $notification->id);

            return;
        }

        // Only reached when the message could not be composed at all, so the
        // admin is told the link exists and nothing is silently lost.
        session()->flash('status', 'Reset link created for '.$account->name.'. It expires in '.$issued->expiresLabel().'.');
    }

    public function deactivate(OrganisationUser $organisationUser): void
    {
        $this->authorize('update', $organisationUser);

        $organisationUser->update(['status' => MembershipStatus::Deactivated]);
    }

    /**
     * @return Slice<OrganisationUser>
     */
    protected function members(): Slice
    {
        $query = OrganisationUser::query()
            ->with([
                'user:id,name,phone',
                'clubAssignments' => fn ($assignments) => $assignments
                    ->where('status', ClubAssignmentStatus::Active)
                    ->with('club:id,name'),
            ])
            // Name, phone, or reference (STF-7). The name and phone live on the
            // user row, the reference on the membership.
            ->when($this->search !== '', fn (Builder $query) => $query->where(function (Builder $inner): void {
                $digits = Search::phoneDigits($this->search, $this->organisation(), 'staff');
                $id = Search::referenceId($this->organisation(), 'staff', $this->search);

                $inner->whereHas('user', fn (Builder $user) => $user
                    ->where('name', 'ilike', "%{$this->search}%")
                    ->when($digits !== null, fn (Builder $q) => $q->orWhere('phone', 'ilike', '%'.$digits.'%')));

                if ($id !== null) {
                    $inner->orWhere('organisation_users.id', $id);
                }
            }))
            // Removed staff only appear when explicitly filtered for, the same
            // rule every other listing follows.
            ->when(
                $this->status !== '',
                fn (Builder $query) => $query->where('status', $this->status),
                fn (Builder $query) => $query->where('status', '!=', MembershipStatus::Deactivated),
            )
            ->when($this->role !== '', fn (Builder $query) => $query->where('role', $this->role))
            ->when($this->club !== '', fn (Builder $query) => $query->whereHas(
                'clubAssignments',
                fn (Builder $assignments) => $assignments
                    ->where('club_id', $this->club)
                    ->where('status', ClubAssignmentStatus::Active)
            ))
            ->latest();

        return $this->slice($query);
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
