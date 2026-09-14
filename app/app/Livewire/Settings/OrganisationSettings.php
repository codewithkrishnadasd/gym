<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Enums\NotificationActionType;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\AuditEvent;
use App\Models\Expense;
use App\Models\MessageTemplate;
use App\Models\Organisation;
use App\Support\Images\BrandImage;
use App\Support\PhoneNumber;
use App\Support\WhatsApp\MessageComposer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use RuntimeException;

/**
 * Tenant-side organisation settings: profile, terminology, and notification
 * preferences (MEP.md 4.1, 5.14, 6.11).
 *
 * Terminology changes only ever affect display labels — never column names,
 * stored values, or audit history (MEP.md 10).
 */
class OrganisationSettings extends Component
{
    use ResolvesMembership, WithFileUploads;

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

    /**
     * Reference prefixes by entity key ("member" => "MEM"), one per
     * Organisation::DEFAULT_ID_PREFIXES entry.
     *
     * @var array<string, string>
     */
    public array $idPrefixes = [];

    // Notifications
    public bool $notificationsEnabled = true;

    public bool $requirePreview = true;

    /** @var array<int, string> */
    public array $enabledActions = [];

    // Branding
    public ?TemporaryUploadedFile $brandImage = null;

    // Expense categories
    /** @var array<int, array{name: string, active: bool}> */
    public array $expenseCategories = [];

    public string $newCategory = '';

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

        foreach (array_keys(Organisation::DEFAULT_ID_PREFIXES) as $entity) {
            $this->idPrefixes[$entity] = $organisation->idPrefix($entity);
        }

        $settings = $organisation->notification_settings ?? [];
        $this->notificationsEnabled = (bool) ($settings['enabled'] ?? true);
        $this->requirePreview = (bool) ($settings['require_preview'] ?? true);
        $this->enabledActions = collect(NotificationActionType::cases())
            ->filter(fn (NotificationActionType $type): bool => $organisation->notificationsEnabled($type))
            ->map(fn (NotificationActionType $type): string => $type->value)
            ->values()
            ->all();

        $this->expenseCategories = $organisation->expenseCategoryList();

        $this->loadTemplate();
    }

    /**
     * One upload becomes both the sidebar logo and the browser-tab favicon.
     *
     * Done inline rather than queued: GD takes a few tens of milliseconds at
     * these sizes, and an operator who has just picked a picture should see it
     * appear, not a "processing…" placeholder they have to refresh to clear.
     */
    public function updatedBrandImage(): void
    {
        $organisation = $this->organisation();
        $this->authorize('manageSettings', $organisation);

        $this->validate([
            'brandImage' => ['required', 'image', 'mimes:jpg,jpeg,png,gif', 'max:8192'],
        ], [
            'brandImage.image' => 'Upload a JPEG, PNG, or GIF image.',
            'brandImage.max' => 'That image is larger than 8 MB. Any reasonable logo is far smaller.',
        ], ['brandImage' => 'image']);

        $upload = $this->brandImage;

        if ($upload === null) {
            return;
        }

        try {
            $derived = BrandImage::derive($upload->getRealPath());
        } catch (RuntimeException $exception) {
            $this->addError('brandImage', $exception->getMessage());
            $this->reset('brandImage');

            return;
        }

        $disk = Storage::disk(config('filesystems.default'));
        $directory = 'organisations/'.$organisation->id.'/branding';

        $logoPath = $directory.'/logo-'.Str::random(16).'.'.$derived['logoExtension'];
        $faviconPath = $directory.'/favicon-'.Str::random(16).'.png';

        $disk->put($logoPath, $derived['logo']);
        $disk->put($faviconPath, $derived['favicon']);

        $previous = [
            'logo_path' => $organisation->logo_path,
            'favicon_path' => $organisation->favicon_path,
        ];

        $this->persist(
            ['logo_path' => $logoPath, 'favicon_path' => $faviconPath],
            $previous,
            'organisation.branding_updated',
        );

        // Only after the new paths are committed, so a failed write never
        // leaves the organisation pointing at files that no longer exist.
        $this->deleteBrandingFiles($previous);

        $this->reset('brandImage');

        session()->flash('status', 'Logo and favicon updated.');
    }

    public function removeBrandImage(): void
    {
        $organisation = $this->organisation();
        $this->authorize('manageSettings', $organisation);

        $previous = [
            'logo_path' => $organisation->logo_path,
            'favicon_path' => $organisation->favicon_path,
        ];

        $this->persist(
            ['logo_path' => null, 'favicon_path' => null],
            $previous,
            'organisation.branding_removed',
        );

        $this->deleteBrandingFiles($previous);

        session()->flash('status', 'Logo and favicon removed.');
    }

    /**
     * @param  array{logo_path: string|null, favicon_path: string|null}  $paths
     */
    private function deleteBrandingFiles(array $paths): void
    {
        $disk = Storage::disk(config('filesystems.default'));

        foreach (array_filter($paths) as $path) {
            $disk->delete($path);
        }
    }

    /**
     * Adds a category to the working list. Nothing is written until the
     * operator saves, so a mistyped entry can be taken back out first.
     */
    public function addCategory(): void
    {
        $category = trim($this->newCategory);

        if ($category === '') {
            return;
        }

        // Case-insensitive, because "Rent" and "rent" would otherwise become
        // two lines in every expense report.
        $exists = collect($this->expenseCategories)
            ->contains(fn (array $existing): bool => mb_strtolower($existing['name']) === mb_strtolower($category));

        if ($exists) {
            $this->addError('newCategory', 'That category is already on the list.');

            return;
        }

        if (count($this->expenseCategories) >= 60) {
            $this->addError('newCategory', 'Sixty categories is the limit — a longer list stops being useful to pick from.');

            return;
        }

        $this->expenseCategories[] = ['name' => $category, 'active' => true];
        $this->newCategory = '';
        $this->resetErrorBag('newCategory');
    }

    /**
     * Takes a category out of use without erasing it.
     *
     * This is the only thing that can be done to a category with expenses
     * behind it: new expenses can no longer be filed under it, while every
     * report, filter and existing row that references it keeps working.
     */
    public function toggleCategory(int $index): void
    {
        if (! isset($this->expenseCategories[$index])) {
            return;
        }

        $this->expenseCategories[$index]['active'] = ! $this->expenseCategories[$index]['active'];
    }

    /**
     * Deleting is allowed only while nothing references the category. Removing
     * one that has expenses behind it would leave those rows — and every report
     * that groups by category — describing something the system denies exists.
     */
    public function removeCategory(int $index): void
    {
        $category = $this->expenseCategories[$index] ?? null;

        if ($category === null) {
            return;
        }

        if ((Expense::categoryUsage()[$category['name']] ?? 0) > 0) {
            $this->addError('expenseCategories', '"'.$category['name'].'" has expenses filed under it, so it cannot be deleted. Deactivate it instead — it will stop appearing on new expenses but stay in your reports.');

            return;
        }

        unset($this->expenseCategories[$index]);

        $this->expenseCategories = array_values($this->expenseCategories);
        $this->resetErrorBag('expenseCategories');
    }

    public function restoreDefaultCategories(): void
    {
        // Merged rather than replaced: wiping the list would delete categories
        // that have expenses behind them, which removeCategory() refuses to do
        // one at a time and should not do wholesale either.
        $existing = collect($this->expenseCategories)
            ->keyBy(fn (array $category): string => mb_strtolower($category['name']));

        foreach (Organisation::DEFAULT_EXPENSE_CATEGORIES as $name) {
            if (! $existing->has(mb_strtolower($name))) {
                $this->expenseCategories[] = ['name' => $name, 'active' => true];
            }
        }
    }

    public function saveExpenseCategories(): void
    {
        $organisation = $this->organisation();
        $this->authorize('manageSettings', $organisation);

        $this->validate([
            'expenseCategories' => ['array', 'max:60'],
            'expenseCategories.*.name' => ['required', 'string', 'max:60'],
        ], [], ['expenseCategories.*.name' => 'category']);

        /** @var array<string, array{name: string, active: bool}> $unique */
        $unique = [];

        foreach ($this->expenseCategories as $category) {
            $name = trim($category['name']);

            if ($name !== '') {
                $unique[mb_strtolower($name)] = ['name' => $name, 'active' => (bool) $category['active']];
            }
        }

        $categories = array_values($unique);

        if (collect($categories)->every(fn (array $category): bool => ! $category['active'])) {
            $this->addError('expenseCategories', 'Keep at least one category active — expenses cannot be filed without one.');

            return;
        }

        $this->expenseCategories = $categories;

        $this->persist(
            ['expense_categories' => $categories],
            ['expense_categories' => $organisation->expense_categories],
            'organisation.expense_categories_updated',
        );

        session()->flash('status', 'Expense categories saved.');
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

    public function saveIdPrefixes(): void
    {
        $organisation = $this->organisation();
        $this->authorize('manageSettings', $organisation);

        // Uppercased before validation so "mem" is accepted as MEM rather
        // than rejected for case; the regex then only has to say what a
        // prefix may contain.
        $this->idPrefixes = array_map(
            static fn (string $prefix): string => strtoupper(trim($prefix)),
            $this->idPrefixes,
        );

        $rules = [];
        $messages = [];

        foreach (Organisation::DEFAULT_ID_PREFIXES as $entity => $default) {
            $rules['idPrefixes.'.$entity] = ['required', 'string', 'regex:/^[A-Z0-9]{1,8}$/'];
            $messages['idPrefixes.'.$entity.'.required'] = 'The '.strtolower($default['label']).' prefix is required.';
            $messages['idPrefixes.'.$entity.'.regex'] = 'Use 1–8 letters or digits only for '.strtolower($default['label']).', e.g. '.$default['prefix'].'.';
        }

        $this->validate($rules, $messages);

        // Only entities the organisation knows about are stored, and only as
        // plain prefixes: the number format itself is not configurable.
        $after = [];

        foreach (array_keys(Organisation::DEFAULT_ID_PREFIXES) as $entity) {
            $after[$entity] = $this->idPrefixes[$entity];
        }

        $this->persist(
            ['id_prefixes' => $after],
            ['id_prefixes' => $organisation->id_prefixes],
            'organisation.id_prefixes_updated',
        );

        session()->flash('status', 'Reference prefixes saved. New and existing records now show the new prefixes; invoice numbers already issued keep theirs.');
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
            // Drives whether a category offers Delete or only Deactivate.
            'categoryUsage' => Expense::categoryUsage(),
        ])->layout('components.layouts.app', ['heading' => 'Settings']);
    }
}
