<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NotificationActionType;
use App\Enums\OrganisationStatus;
use App\Support\Money;
use App\Support\Theme\ThemeTokens;
use Database\Factories\OrganisationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name', 'slug', 'logo_path', 'favicon_path', 'accent_color', 'theme_colors', 'id_prefixes', 'status', 'timezone', 'currency_code', 'locale',
    'default_country_code', 'contact_email', 'contact_phone', 'address',
    'notification_settings', 'expense_categories', 'created_by',
    'terminology_member_singular', 'terminology_member_plural',
    'terminology_user_singular', 'terminology_user_plural',
    'terminology_club_singular', 'terminology_club_plural',
])]
class Organisation extends Model
{
    /** @use HasFactory<OrganisationFactory> */
    use HasFactory;

    /**
     * What a new organisation starts with. Deliberately generic: these are the
     * costs almost every gym has, and an operator prunes and extends the list
     * in settings rather than building it from nothing.
     *
     * @var list<string>
     */
    public const DEFAULT_EXPENSE_CATEGORIES = [
        'Rent', 'Utilities', 'Salaries', 'Equipment', 'Maintenance',
        'Marketing', 'Supplies', 'Insurance', 'Software', 'Other',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrganisationStatus::class,
            'address' => 'array',
            'notification_settings' => 'array',
            'expense_categories' => 'array',
            'theme_colors' => 'array',
            'id_prefixes' => 'array',
        ];
    }

    /**
     * The full configured list, active and inactive alike, in the order an
     * operator arranged it.
     *
     * Falls back to the defaults rather than returning nothing, so an
     * organisation created before this was configurable — or one whose list was
     * emptied — still has a usable expense form.
     *
     * @return list<array{name: string, active: bool}>
     */
    public function expenseCategoryList(): array
    {
        /** @var array<int, mixed>|null $configured */
        $configured = $this->expense_categories;

        /** @var array<string, array{name: string, active: bool}> $categories */
        $categories = [];

        foreach ($configured ?? [] as $entry) {
            // Tolerates the pre-migration shape (a bare string), so a stale
            // cached model or a hand-edited row cannot empty the list.
            $name = trim(is_array($entry) ? (string) ($entry['name'] ?? '') : (string) $entry);

            if ($name === '') {
                continue;
            }

            $categories[mb_strtolower($name)] = [
                'name' => $name,
                'active' => is_array($entry) ? (bool) ($entry['active'] ?? true) : true,
            ];
        }

        if ($categories === []) {
            return array_map(
                static fn (string $name): array => ['name' => $name, 'active' => true],
                self::DEFAULT_EXPENSE_CATEGORIES,
            );
        }

        return array_values($categories);
    }

    /**
     * The categories an operator may file a *new* expense under. A deactivated
     * category stays out of this list but keeps working everywhere historical
     * data is read.
     *
     * @return list<string>
     */
    public function expenseCategories(): array
    {
        $active = array_values(array_filter(
            $this->expenseCategoryList(),
            static fn (array $category): bool => $category['active'],
        ));

        return array_map(static fn (array $category): string => $category['name'], $active);
    }

    /**
     * Every configured category regardless of state — what filters and reports
     * offer, so a deactivated category's existing expenses stay reachable.
     *
     * @return list<string>
     */
    public function allExpenseCategories(): array
    {
        return array_map(
            static fn (array $category): string => $category['name'],
            $this->expenseCategoryList(),
        );
    }

    /**
     * @return BelongsTo<PlatformAdmin, $this>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(PlatformAdmin::class, 'created_by');
    }

    /**
     * @return HasMany<Domain, $this>
     */
    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    /**
     * @return HasMany<OrganisationUser, $this>
     */
    public function organisationUsers(): HasMany
    {
        return $this->hasMany(OrganisationUser::class);
    }

    /**
     * @return HasMany<Club, $this>
     */
    public function clubs(): HasMany
    {
        return $this->hasMany(Club::class);
    }

    /**
     * @return HasMany<Member, $this>
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * @return HasMany<Plan, $this>
     */
    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    /**
     * @return HasMany<FinancialAccount, $this>
     */
    public function financialAccounts(): HasMany
    {
        return $this->hasMany(FinancialAccount::class);
    }

    /**
     * Formats a minor-unit amount in this organisation's currency and locale.
     */
    public function money(int $minor): string
    {
        return Money::ofMinor($minor, $this->currency_code)->format($this->locale);
    }

    public function moneyCompact(int $minor): string
    {
        return Money::ofMinor($minor, $this->currency_code)->formatCompact($this->locale);
    }

    /**
     * The dialling region used to normalise phone numbers. Falls back rather
     * than returning null, because MEP.md 10 requires a bad or missing number
     * never to break the business action that triggered a message.
     */
    public function defaultCountry(): string
    {
        return $this->default_country_code ?: 'IN';
    }

    /**
     * Both URLs carry a version derived from the stored filename, so a
     * replaced image reaches browsers immediately even though the responses
     * are cached for a week.
     */
    public function logoUrl(): ?string
    {
        return $this->brandingUrl('tenant.branding.logo', $this->logo_path);
    }

    public function faviconUrl(): ?string
    {
        return $this->brandingUrl('tenant.branding.favicon', $this->favicon_path);
    }

    /**
     * The icon for the browser tab, which every organisation gets whether or
     * not it has uploaded anything: the uploaded favicon when there is one, and
     * otherwise the generated mark in the organisation's accent colour.
     *
     * Falling back to nothing meant a gym that had set its colours but not yet
     * uploaded a logo still showed the browser's blank default in the tab.
     */
    public function tabIconUrl(): string
    {
        return $this->faviconUrl() ?? route('tenant.branding.app-icon', [
            'size' => 64,
            'v' => substr(md5((string) $this->accent_color), 0, 8),
        ]);
    }

    private function brandingUrl(string $route, ?string $path): ?string
    {
        return $path === null ? null : route($route, ['v' => substr(md5($path), 0, 8)]);
    }

    /**
     * The hostname this organisation is reached on. Needed wherever the
     * application builds a link someone will open from outside a request —
     * a WhatsApp message, a reset link — where there is no current host to
     * borrow.
     */
    public function primaryHostname(): ?string
    {
        /** @var string|null $hostname */
        $hostname = $this->domains()
            ->where('is_primary', true)
            ->value('hostname')
            ?? $this->domains()->value('hostname');

        return $hostname;
    }

    /**
     * Everything this organisation has changed about the interface colours —
     * the accent and any per-theme overrides — as a CSS block for the document
     * head. Null when nothing is configured, so the built-in palette is left
     * alone rather than restated on every page.
     */
    public function themeCss(): ?string
    {
        return ThemeTokens::css($this->accent_color, $this->theme_colors);
    }

    /**
     * The single colour that stands for the brand outside the stylesheet: the
     * browser chrome tint, the installed-app icon. An explicit light-theme
     * primary wins over the accent picker, which wins over the default.
     */
    public function brandColor(): string
    {
        return ThemeTokens::resolve($this->accent_color, $this->theme_colors)['light']['accent'];
    }

    public function currencySymbol(): string
    {
        return Money::symbol($this->currency_code, $this->locale);
    }

    /**
     * What each kind of record is called by its number, in the order the
     * settings screen lists them. Keys are stable; labels are for the editor.
     *
     * @var array<string, array{prefix: string, label: string}>
     */
    public const DEFAULT_ID_PREFIXES = [
        'member' => ['prefix' => 'MEM', 'label' => 'Members'],
        'staff' => ['prefix' => 'STF', 'label' => 'Staff'],
        'club' => ['prefix' => 'CLB', 'label' => 'Clubs'],
        'plan' => ['prefix' => 'PLN', 'label' => 'Plans'],
        'payment' => ['prefix' => 'PMT', 'label' => 'Payments and receipts'],
        'expense' => ['prefix' => 'EXP', 'label' => 'Expenses'],
        'invoice' => ['prefix' => 'INV', 'label' => 'Invoices'],
    ];

    /**
     * The prefix for one kind of record, e.g. "MEM". Falls back to the default
     * for anything unset, so a partially configured organisation never shows a
     * bare number.
     */
    public function idPrefix(string $entity): string
    {
        $configured = $this->id_prefixes[$entity] ?? null;

        if (is_string($configured) && $configured !== '') {
            return $configured;
        }

        return self::DEFAULT_ID_PREFIXES[$entity]['prefix'] ?? strtoupper(substr($entity, 0, 3));
    }

    /**
     * A record's human reference, e.g. "MEM-42". Used everywhere a number is
     * shown to people — lists, headings, receipts, exports, messages — so the
     * same record reads the same in every place.
     */
    public function reference(string $entity, int $id): string
    {
        return $this->idPrefix($entity).'-'.$id;
    }

    /**
     * Terminology lookups are indirected through here so views never guess a
     * column name — MEP.md Section 1 requires configured labels everywhere.
     */
    public function term(string $key): string
    {
        /** @var string|null $value */
        $value = $this->getAttribute('terminology_'.$key);

        return $value ?? ucfirst(str_replace('_', ' ', $key));
    }

    /**
     * Attendance notifications are opt-in because daily marking would
     * otherwise generate very high message volume (MEP.md 5.14).
     */
    public function notificationsEnabled(NotificationActionType $type): bool
    {
        // A password reset link is a handover, not an announcement. Suppressing
        // it would leave an admin holding a link they have no way to pass on.
        if (! $type->isOptional()) {
            return true;
        }

        $settings = $this->notification_settings ?? [];

        if (($settings['enabled'] ?? true) === false) {
            return false;
        }

        $default = ! in_array($type, [
            NotificationActionType::MemberAttendanceMarked,
            NotificationActionType::UserAttendanceMarked,
        ], true);

        return (bool) ($settings['actions'][$type->value] ?? $default);
    }

    public function requiresNotificationPreview(): bool
    {
        return (bool) (($this->notification_settings ?? [])['require_preview'] ?? true);
    }
}
