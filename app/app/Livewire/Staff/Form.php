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
use App\Enums\Permission;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Club;
use App\Models\ClubUserAssignment;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\WhatsappActionNotification;
use App\Support\PhoneNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class Form extends Component
{
    use ResolvesMembership;

    public ?OrganisationUser $organisationUser = null;

    public string $name = '';

    public string $phone = '';

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
            $this->phone = (string) $user->phone;
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

    /**
     * Ticking a permission ticks whatever it depends on, so the form always
     * shows the access that will actually be granted. Doing this on change
     * rather than only on save means an admin never saves one thing and gets
     * another.
     */
    public function updatedPermissions(): void
    {
        $this->permissions = Permission::expand($this->permissions);
    }

    public function save(): void
    {
        $this->authorize($this->organisationUser ? 'update' : 'create', $this->organisationUser ?? OrganisationUser::class);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', Rule::enum(MembershipRole::class)],
            'status' => ['required', Rule::enum(MembershipStatus::class)],
        ];

        // Editable on an existing person too: a staff member who changes their
        // number would otherwise be locked out permanently, since the number is
        // how they sign in.
        $rules['phone'] = ['required', 'string', 'max:50'];

        $validated = $this->validate($rules, [], ['phone' => 'WhatsApp number']);

        $normalisedPhone = PhoneNumber::normalise($validated['phone'], $this->organisation()->defaultCountry());

        if ($normalisedPhone === null) {
            $this->addError('phone', 'Enter a valid WhatsApp number.');

            return;
        }

        // One person is one `users` row across every organisation, so an
        // existing number is reused rather than duplicated (MEP.md 5.3).
        $existingUser = $this->organisationUser
            ? $this->organisationUser->user
            : User::query()->where('phone', $normalisedPhone)->first();

        if (! $this->organisationUser) {
            if ($existingUser && OrganisationUser::query()
                ->where('organisation_id', app('tenant')->id)
                ->where('user_id', $existingUser->id)
                ->exists()) {
                $this->addError('phone', 'This person is already a member of this organisation.');

                return;
            }
        } elseif ($existingUser && $existingUser->phone !== $normalisedPhone) {
            // The number is the login identity and is unique across the whole
            // platform, so a clash has to be caught here rather than surfacing
            // as a database error.
            $taken = User::query()
                ->where('phone', $normalisedPhone)
                ->whereKeyNot($existingUser->id)
                ->exists();

            if ($taken) {
                $this->addError('phone', 'Another account already signs in with that number.');

                return;
            }
        }

        // Admins bypass the permission map entirely, so storing keys for them
        // would be misleading (MEP.md 4.2). For everyone else the set is
        // expanded here rather than trusted from the form: the checkboxes are
        // a convenience, the server decides what a grant actually includes.
        $permissions = MembershipRole::from($validated['role']) === MembershipRole::Admin
            ? []
            : Permission::map($this->permissions);

        $invitedBy = $this->currentMembership();
        $isInvite = $this->organisationUser === null;
        $previousStatus = $this->organisationUser?->status->value;
        $previousClubIds = $isInvite ? [] : $this->organisationUser->activeClubIds();

        $saved = DB::transaction(function () use ($validated, $existingUser, $permissions, $invitedBy, $normalisedPhone): OrganisationUser {
            // A brand-new account gets an unguessable placeholder nobody ever
            // sees, including this admin. The person sets their own password
            // through the reset link issued below, so no password is ever known
            // to two people at once.
            $user = $existingUser ?? User::create([
                'name' => $validated['name'],
                'phone' => $normalisedPhone,
                'password' => Hash::make(Str::password(40)),
            ]);

            if ($this->organisationUser) {
                // `users` is one row per person across every organisation, so
                // this changes how they sign in everywhere — see the warning on
                // the form.
                $user->update(['name' => $validated['name'], 'phone' => $normalisedPhone]);
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

        // A person who already has an account keeps their password and just
        // needs the invitation. A brand-new account has no usable password at
        // all, so the link to set one *is* the useful message — sending a
        // welcome they cannot act on would be worse than sending nothing.
        $notification = $existingUser === null && $isInvite
            ? $this->sendPasswordSetupLink($saved)
            : $this->notify($saved, $isInvite, $previousStatus, $previousClubIds);

        session()->flash('status', "\"{$validated['name']}\" was saved.");
        session()->flash('notification_id', $notification?->id);

        $this->redirect(route('tenant.staff.index'));
    }

    /**
     * The first-sign-in path for a newly created account: a single-use link
     * that lets them choose their own password, rather than one an admin typed
     * and now also knows.
     */
    private function sendPasswordSetupLink(OrganisationUser $organisationUser): ?WhatsappActionNotification
    {
        $organisationUser->refresh();

        /** @var User $user */
        $user = $organisationUser->user;

        $issued = app(IssuePasswordResetLink::class)->handle(
            $this->organisation(),
            $user,
            $this->currentMembership(),
        );

        return app(CreateActionNotification::class)->handle(
            organisation: $this->organisation(),
            type: NotificationActionType::PasswordResetLink,
            recipientType: NotificationRecipientType::User,
            recipientId: $organisationUser->id,
            recipientName: $user->name,
            recipientPhone: $user->phone,
            entityType: NotificationEntityType::User,
            entityId: $organisationUser->id,
            actor: $this->currentMembership(),
            operationId: 'password-reset-'.$issued->link->id,
            context: [
                'resetUrl' => $issued->url,
                'expiresIn' => $issued->expiresLabel(),
            ],
        );
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
