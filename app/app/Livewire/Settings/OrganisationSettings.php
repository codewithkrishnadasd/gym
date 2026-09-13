<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Enums\NotificationActionType;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\AuditEvent;
use App\Models\MessageTemplate;
use App\Support\PhoneNumber;
use App\Support\WhatsApp\MessageComposer;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Tenant-side organisation settings: profile, terminology, and notification
 * preferences (MEP.md 4.1, 5.14, 6.11).
 *
 * Terminology changes only ever affect display labels — never column names,
 * stored values, or audit history (MEP.md 10).
 */
class OrganisationSettings extends Component
{
    use ResolvesMembership;

    #[Url]
    public string $tab = 'profile';

    // Profile
    public string $name = '';

    public string $contactEmail = '';

    public string $contactPhone = '';

    public string $addressLine = '';

    public string $timezone = 'UTC';

    public string $locale = 'en';

    public string $defaultCountryCode = 'IN';

    // Terminology
    public string $memberSingular = '';

    public string $memberPlural = '';

    public string $userSingular = '';

    public string $userPlural = '';

    public string $clubSingular = '';

    public string $clubPlural = '';

    // Notifications
    public bool $notificationsEnabled = true;

    public bool $requirePreview = true;

    /** @var array<int, string> */
    public array $enabledActions = [];

    // Message templates
    public string $templateAction = 'fee_payment_confirmed';

    public string $templateBody = '';

    public function mount(): void
    {
        $organisation = $this->organisation();

        $this->authorize('manageSettings', $organisation);

        $this->name = $organisation->name;
        $this->contactEmail = (string) $organisation->contact_email;
        $this->contactPhone = (string) $organisation->contact_phone;
        $this->addressLine = (string) ($organisation->address['line1'] ?? '');
        $this->timezone = $organisation->timezone;
        $this->locale = $organisation->locale;
        $this->defaultCountryCode = $organisation->defaultCountry();

        $this->memberSingular = $organisation->terminology_member_singular;
        $this->memberPlural = $organisation->terminology_member_plural;
        $this->userSingular = $organisation->terminology_user_singular;
        $this->userPlural = $organisation->terminology_user_plural;
        $this->clubSingular = $organisation->terminology_club_singular;
        $this->clubPlural = $organisation->terminology_club_plural;

        $settings = $organisation->notification_settings ?? [];
        $this->notificationsEnabled = (bool) ($settings['enabled'] ?? true);
        $this->requirePreview = (bool) ($settings['require_preview'] ?? true);
        $this->enabledActions = collect(NotificationActionType::cases())
            ->filter(fn (NotificationActionType $type): bool => $organisation->notificationsEnabled($type))
            ->map(fn (NotificationActionType $type): string => $type->value)
            ->values()
            ->all();

        $this->loadTemplate();
    }

    /**
     * Loads whichever template the operator selected, falling back to the
     * built-in wording when the organisation has no override yet.
     */
    public function updatedTemplateAction(): void
    {
        $this->loadTemplate();
    }

    private function loadTemplate(): void
    {
        $this->templateBody = MessageComposer::bodyFor(
            $this->organisation(),
            NotificationActionType::from($this->templateAction),
        );
    }

    public function saveTemplate(): void
    {
        $organisation = $this->organisation();
        $this->authorize('manageSettings', $organisation);

        $validated = $this->validate([
            'templateAction' => ['required', Rule::enum(NotificationActionType::class)],
            'templateBody' => ['required', 'string', 'max:2000'],
        ], [], ['templateBody' => 'message']);

        $type = NotificationActionType::from($validated['templateAction']);

        // Reject placeholders that are not on this action's allowlist rather
        // than silently rendering them as an em dash later (MEP.md 6.8).
        $allowed = array_keys(MessageComposer::variablesFor($type));
        preg_match_all('/\{(\w+)\}/', $validated['templateBody'], $matches);
        $unknown = array_values(array_unique(array_diff($matches[1], $allowed)));

        if ($unknown !== []) {
            $this->addError('templateBody', 'Unknown variable: {'.implode('}, {', $unknown).'}.');

            return;
        }

        $template = MessageTemplate::query()->withoutGlobalScopes()->firstOrNew([
            'organisation_id' => $organisation->id,
            'action_type' => $type,
        ]);

        $before = ['body' => $template->body];

        $template->fill([
            'body' => $validated['templateBody'],
            'version' => ($template->version ?? 0) + 1,
            'updated_by' => $this->currentMembership()->id,
        ])->save();

        AuditEvent::record($template, 'message_template.updated', $this->currentMembership(), $before, [
            'action_type' => $type->value,
            'version' => $template->version,
        ]);

        session()->flash('status', 'Message template saved. New messages use this wording; already-sent ones are unchanged.');
    }

    public function resetTemplate(): void
    {
        $organisation = $this->organisation();
        $this->authorize('manageSettings', $organisation);

        MessageTemplate::query()
            ->withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('action_type', NotificationActionType::from($this->templateAction))
            ->delete();

        $this->loadTemplate();

        session()->flash('status', 'Template reset to the built-in wording.');
    }

    public function saveProfile(): void
    {
        $organisation = $this->organisation();
        $this->authorize('manageSettings', $organisation);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'contactEmail' => ['nullable', 'email', 'max:255'],
            'contactPhone' => ['nullable', 'string', 'max:50'],
            'addressLine' => ['nullable', 'string', 'max:255'],
            'timezone' => ['required', 'timezone'],
            'locale' => ['required', 'string', 'max:10'],
            'defaultCountryCode' => ['required', 'string', 'size:2'],
        ]);

        $before = $organisation->only(['name', 'contact_email', 'contact_phone', 'timezone', 'locale', 'default_country_code']);

        $after = [
            'name' => $validated['name'],
            'contact_email' => $validated['contactEmail'] ?: null,
            'contact_phone' => $validated['contactPhone'] ?: null,
            'address' => $validated['addressLine'] ? ['line1' => $validated['addressLine']] : null,
            'timezone' => $validated['timezone'],
            'locale' => $validated['locale'],
            'default_country_code' => strtoupper($validated['defaultCountryCode']),
        ];

        $this->persist($after, $before, 'organisation.profile_updated');

        session()->flash('status', 'Organisation profile saved.');
    }

    public function saveTerminology(): void
    {
        $organisation = $this->organisation();
        $this->authorize('manageSettings', $organisation);

        $rules = ['required', 'string', 'max:40'];

        $validated = $this->validate([
            'memberSingular' => $rules,
            'memberPlural' => $rules,
            'userSingular' => $rules,
            'userPlural' => $rules,
            'clubSingular' => $rules,
            'clubPlural' => $rules,
        ]);

        $before = $organisation->only([
            'terminology_member_singular', 'terminology_member_plural',
            'terminology_user_singular', 'terminology_user_plural',
            'terminology_club_singular', 'terminology_club_plural',
        ]);

        $after = [
            'terminology_member_singular' => $validated['memberSingular'],
            'terminology_member_plural' => $validated['memberPlural'],
            'terminology_user_singular' => $validated['userSingular'],
            'terminology_user_plural' => $validated['userPlural'],
            'terminology_club_singular' => $validated['clubSingular'],
            'terminology_club_plural' => $validated['clubPlural'],
        ];

        $this->persist($after, $before, 'organisation.terminology_updated');

        session()->flash('status', 'Terminology saved. Labels are updated everywhere.');

        // The navigation and page chrome live in the layout, which a Livewire
        // component update does not re-render — so a full navigation is needed
        // for the new labels to appear immediately rather than on the user's
        // next page load.
        $this->redirect(route('tenant.settings.organisation', ['tab' => 'terminology']));
    }

    public function saveNotifications(): void
    {
        $organisation = $this->organisation();
        $this->authorize('manageSettings', $organisation);

        $actions = collect(NotificationActionType::cases())
            ->mapWithKeys(fn (NotificationActionType $type): array => [
                $type->value => in_array($type->value, $this->enabledActions, true),
            ])
            ->all();

        $settings = [
            'enabled' => $this->notificationsEnabled,
            'require_preview' => $this->requirePreview,
            'actions' => $actions,
        ];

        $this->persist(
            ['notification_settings' => $settings],
            ['notification_settings' => $organisation->notification_settings],
            'organisation.notification_settings_updated',
        );

        session()->flash('status', 'Notification settings saved.');
    }

    /**
     * @param  array<string, mixed>  $after
     * @param  array<string, mixed>  $before
     */
    private function persist(array $after, array $before, string $action): void
    {
        $organisation = $this->organisation();
        $actor = $this->currentMembership();

        DB::transaction(function () use ($organisation, $after, $before, $action, $actor): void {
            $organisation->update($after);

            AuditEvent::record($organisation, $action, $actor, $before, $after);
        });
    }

    public function render(): View
    {
        $organisation = $this->organisation();

        return view('livewire.settings.organisation', [
            'organisation' => $organisation,
            'timezones' => \DateTimeZone::listIdentifiers(),
            'countries' => PhoneNumber::countries(),
            'actionTypes' => NotificationActionType::cases(),
            'templateVariables' => MessageComposer::variablesFor(NotificationActionType::from($this->templateAction)),
            'templatePreview' => MessageComposer::preview(
                $organisation,
                NotificationActionType::from($this->templateAction),
                $this->templateBody ?: null,
            ),
            'templateIsCustom' => MessageTemplate::query()->withoutGlobalScopes()
                ->where('organisation_id', $organisation->id)
                ->where('action_type', $this->templateAction)
                ->exists(),
            'phonePreview' => PhoneNumber::forDisplay($this->contactPhone ?: '9876543210', $this->defaultCountryCode),
        ])->layout('components.layouts.app', ['heading' => 'Settings']);
    }
}
