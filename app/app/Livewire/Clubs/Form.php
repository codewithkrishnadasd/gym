<?php

declare(strict_types=1);

namespace App\Livewire\Clubs;

use App\Enums\ClubAssignmentStatus;
use App\Enums\ClubStatus;
use App\Enums\MembershipStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Club;
use App\Models\ClubUserAssignment;
use App\Models\OrganisationUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class Form extends Component
{
    use ResolvesMembership;

    public ?Club $club = null;

    public string $name = '';

    public string $code = '';

    public string $phone = '';

    public string $email = '';

    public string $addressLine = '';

    public string $timezone = 'UTC';

    /** @var array<int, int> */
    public array $assignedUserIds = [];

    /**
     * Opening hours per weekday (MEP.md 6.4), stored as a jsonb map.
     *
     * @var array<string, array{closed: bool, open: string, close: string}>
     */
    public array $openingHours = [];

    public const DAYS = [
        'mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday',
        'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday',
    ];

    public function mount(?Club $club = null): void
    {
        $this->club = $club;

        $this->authorize($club ? 'update' : 'create', $club ?? Club::class);

        $stored = $club->opening_hours ?? [];

        foreach (array_keys(self::DAYS) as $day) {
            $this->openingHours[$day] = [
                'closed' => (bool) ($stored[$day]['closed'] ?? false),
                'open' => (string) ($stored[$day]['open'] ?? '06:00'),
                'close' => (string) ($stored[$day]['close'] ?? '22:00'),
            ];
        }

        if ($club) {
            $this->name = $club->name;
            $this->code = $club->code;
            $this->phone = (string) $club->phone;
            $this->email = (string) $club->email;
            $this->addressLine = (string) ($club->address['line1'] ?? '');
            $this->timezone = $club->timezone ?? 'UTC';
            $this->assignedUserIds = $club->userAssignments()
                ->where('status', ClubAssignmentStatus::Active)
                ->pluck('organisation_user_id')
                ->all();
        } else {
            $this->timezone = $this->organisation()->timezone;
        }
    }

    public function save(): void
    {
        $this->authorize($this->club ? 'update' : 'create', $this->club ?? Club::class);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('clubs', 'code')
                    ->where('organisation_id', app('tenant')->id)
                    ->ignore($this->club?->id),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'addressLine' => ['nullable', 'string', 'max:255'],
            'timezone' => ['required', 'timezone'],
            'openingHours.*.closed' => ['boolean'],
            'openingHours.*.open' => ['required', 'date_format:H:i'],
            'openingHours.*.close' => ['required', 'date_format:H:i'],
        ]);

        $membership = $this->currentMembership();

        DB::transaction(function () use ($validated, $membership): void {
            $attributes = [
                'name' => $validated['name'],
                'code' => $validated['code'],
                'phone' => $validated['phone'] ?: null,
                'email' => $validated['email'] ?: null,
                'address' => $validated['addressLine'] ? ['line1' => $validated['addressLine']] : null,
                'timezone' => $validated['timezone'],
                'opening_hours' => $this->openingHours,
            ];

            if ($this->club) {
                $this->club->update($attributes);
                $club = $this->club;
            } else {
                $club = Club::create([
                    ...$attributes,
                    'status' => ClubStatus::Active,
                    'created_by' => $membership->id,
                ]);
            }

            $this->syncAssignments($club);
        });

        session()->flash('status', "\"{$validated['name']}\" was saved.");

        $this->redirect(route('tenant.clubs.index'));
    }

    private function syncAssignments(Club $club): void
    {
        $current = $club->userAssignments()
            ->where('status', ClubAssignmentStatus::Active)
            ->get()
            ->keyBy('organisation_user_id');

        foreach ($this->assignedUserIds as $organisationUserId) {
            if (! $current->has($organisationUserId)) {
                ClubUserAssignment::create([
                    'club_id' => $club->id,
                    'organisation_user_id' => $organisationUserId,
                    'status' => ClubAssignmentStatus::Active,
                    'assigned_at' => now(),
                ]);
            }
        }

        foreach ($current as $organisationUserId => $assignment) {
            if (! in_array($organisationUserId, $this->assignedUserIds, true)) {
                $assignment->update(['status' => ClubAssignmentStatus::Ended, 'ended_at' => now()]);
            }
        }
    }

    public function render(): View
    {
        $organisation = $this->organisation();

        /** @var Collection<int, OrganisationUser> $availableUsers */
        $availableUsers = OrganisationUser::query()
            ->with('user')
            ->where('status', MembershipStatus::Active)
            ->get();

        return view('livewire.clubs.form', [
            'organisation' => $organisation,
            'availableUsers' => $availableUsers,
            'days' => self::DAYS,
            'timezones' => \DateTimeZone::listIdentifiers(),
        ])->layout('components.layouts.app', [
            'heading' => $this->club
                ? "Edit {$this->club->name}"
                : 'New '.$organisation->term('club_singular'),
        ]);
    }
}
