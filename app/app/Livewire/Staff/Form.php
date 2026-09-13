<?php

declare(strict_types=1);

namespace App\Livewire\Staff;

use App\Actions\Notifications\CreateActionNotification;
use App\Enums\ClubAssignmentStatus;
use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Enums\NotificationActionType;
use App\Enums\NotificationEntityType;
use App\Enums\NotificationRecipientType;
use App\Enums\Permission;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Club;
use App\Models\ClubUserAssignment;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\WhatsappActionNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class Form extends Component
{
    use ResolvesMembership;

    public ?OrganisationUser $organisationUser = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $role = 'user';

    public string $status = 'active';

    /** @var array<int, string> */
    public array $permissions = [];

    /** @var array<int, int> */
    public array $clubIds = [];

    public function mount(?OrganisationUser $organisationUser = null): void
    {
        $this->organisationUser = $organisationUser;

        $this->authorize($organisationUser ? 'update' : 'create', $organisationUser ?? OrganisationUser::class);

        if ($organisationUser) {
            /** @var User $user */
            $user = $organisationUser->user;

            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $organisationUser->role->value;
            $this->status = $organisationUser->status->value;
            $this->permissions = collect($organisationUser->permissions)
                ->filter()
                ->keys()
                ->all();
            $this->clubIds = $organisationUser->clubAssignments()
                ->where('status', ClubAssignmentStatus::Active)
                ->pluck('club_id')
                ->all();
        }
    }

    public function save(): void
    {
        $this->authorize($this->organisationUser ? 'update' : 'create', $this->organisationUser ?? OrganisationUser::class);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', Rule::enum(MembershipRole::class)],
            'status' => ['required', Rule::enum(MembershipStatus::class)],
        ];

        if (! $this->organisationUser) {
            $rules['email'] = ['required', 'email', 'max:255'];
            $rules['password'] = ['nullable', 'string', 'min:8'];
        }

        $validated = $this->validate($rules);

        $existingUser = $this->organisationUser
            ? $this->organisationUser->user
            : User::query()->where('email', $validated['email'] ?? null)->first();

        if (! $this->organisationUser) {
            if (! $existingUser && ! $this->password) {
                $this->addError('password', 'This email has no account yet — set a password to create one.');

                return;
            }

            if ($existingUser && OrganisationUser::query()
                ->where('organisation_id', app('tenant')->id)
                ->where('user_id', $existingUser->id)
                ->exists()) {
                $this->addError('email', 'This person is already a member of this organisation.');

                return;
            }
        }

        // Admins bypass the permission map entirely, so storing keys for them
        // would be misleading (MEP.md 4.2).
        $permissions = MembershipRole::from($validated['role']) === MembershipRole::Admin
            ? []
            : collect(Permission::keys())
                ->mapWithKeys(fn (string $key): array => [$key => in_array($key, $this->permissions, true)])
                ->all();

        $invitedBy = $this->currentMembership();
        $isInvite = $this->organisationUser === null;
        $previousStatus = $this->organisationUser?->status->value;
        $previousClubIds = $isInvite ? [] : $this->organisationUser->activeClubIds();

        $saved = DB::transaction(function () use ($validated, $existingUser, $permissions, $invitedBy): OrganisationUser {
            $user = $existingUser ?? User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($this->password),
            ]);

            if ($this->organisationUser) {
                $user->update(['name' => $validated['name']]);
            }

            $organisationUser = $this->organisationUser
                ?? new OrganisationUser(['user_id' => $user->id, 'created_by' => $invitedBy->id]);
            $organisationUser->fill([
                'role' => $validated['role'],
                'status' => $validated['status'],
                'permissions' => $permissions,
            ]);
            $organisationUser->save();

            $this->syncClubAssignments($organisationUser);

            return $organisationUser;
        });

        $notification = $this->notify($saved, $isInvite, $previousStatus, $previousClubIds);

        session()->flash('status', "\"{$validated['name']}\" was saved.");
        session()->flash('notification_id', $notification?->id);

        $this->redirect(route('tenant.staff.index'));
    }

    /**
     * Sends the action-specific message required by MEP.md 6.5. An invitation
     * carries only the organisation, role, assigned clubs, and sign-in link —
     * never a password or a raw token.
     *
     * @param  array<int, int>  $previousClubIds
     */
    private function notify(
        OrganisationUser $organisationUser,
        bool $isInvite,
        ?string $previousStatus,
        array $previousClubIds,
    ): ?WhatsappActionNotification {
        $organisationUser->refresh();

        /** @var User $user */
        $user = $organisationUser->user;

        $clubNames = Club::query()
            ->whereIn('id', $organisationUser->activeClubIds())
            ->orderBy('name')
            ->pluck('name')
            ->implode(', ');

        $statusChanged = ! $isInvite && $previousStatus !== $organisationUser->status->value;
        $clubsChanged = ! $isInvite
            && array_diff($previousClubIds, $organisationUser->activeClubIds()) !== []
            || array_diff($organisationUser->activeClubIds(), $previousClubIds) !== [];

        $type = match (true) {
            $isInvite => NotificationActionType::UserInvited,
            $statusChanged => NotificationActionType::UserStatusChanged,
            $clubsChanged => NotificationActionType::UserClubAssignmentChanged,
            default => NotificationActionType::UserProfileUpdated,
        };

        return app(CreateActionNotification::class)->handle(
            organisation: $this->organisation(),
            type: $type,
            recipientType: NotificationRecipientType::User,
            recipientId: $organisationUser->id,
            recipientName: $user->name,
            recipientPhone: $user->phone,
            entityType: NotificationEntityType::User,
            entityId: $organisationUser->id,
            actor: $this->currentMembership(),
            operationId: $type->value.'.'.$organisationUser->id.'.'.now()->timestamp,
            context: [
                'roleLabel' => $organisationUser->isAdmin()
                    ? 'an administrator'
                    : $this->organisation()->term('user_singular'),
                'clubName' => $clubNames ?: null,
                'changedItem' => $organisationUser->status->label(),
                'signInUrl' => route('tenant.login'),
            ],
        );
    }

    private function syncClubAssignments(OrganisationUser $organisationUser): void
    {
        $current = $organisationUser->clubAssignments()
            ->where('status', ClubAssignmentStatus::Active)
            ->get()
            ->keyBy('club_id');

        foreach ($this->clubIds as $clubId) {
            if (! $current->has($clubId)) {
                ClubUserAssignment::create([
                    'club_id' => $clubId,
                    'organisation_user_id' => $organisationUser->id,
                    'status' => ClubAssignmentStatus::Active,
                    'assigned_at' => now(),
                ]);
            }
        }

        foreach ($current as $clubId => $assignment) {
            if (! in_array($clubId, $this->clubIds, true)) {
                $assignment->update(['status' => ClubAssignmentStatus::Ended, 'ended_at' => now()]);
            }
        }
    }

    public function render(): View
    {
        $organisation = $this->organisation();

        return view('livewire.staff.form', [
            'organisation' => $organisation,
            'availableClubs' => $this->accessibleClubs(),
            'permissionGroups' => Permission::grouped(),
            'roles' => MembershipRole::cases(),
            'statuses' => MembershipStatus::cases(),
        ])->layout('components.layouts.app', [
            'heading' => $this->organisationUser
                ? "Edit {$this->name}"
                : 'Invite '.$organisation->term('user_singular'),
        ]);
    }
}
