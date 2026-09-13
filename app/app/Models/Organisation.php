<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NotificationActionType;
use App\Enums\OrganisationStatus;
use App\Support\Money;
use Database\Factories\OrganisationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name', 'slug', 'logo_path', 'status', 'timezone', 'currency_code', 'locale',
    'default_country_code', 'contact_email', 'contact_phone', 'address',
    'notification_settings', 'created_by',
    'terminology_member_singular', 'terminology_member_plural',
    'terminology_user_singular', 'terminology_user_plural',
    'terminology_club_singular', 'terminology_club_plural',
])]
class Organisation extends Model
{
    /** @use HasFactory<OrganisationFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => OrganisationStatus::class,
            'address' => 'array',
            'notification_settings' => 'array',
        ];
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

    public function currencySymbol(): string
    {
        return Money::symbol($this->currency_code, $this->locale);
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
