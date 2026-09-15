<?php

declare(strict_types=1);

namespace App\Http\Controllers\Platform;

use App\Enums\DomainStatus;
use App\Enums\Feature;
use App\Enums\FinancialAccountStatus;
use App\Enums\FinancialAccountType;
use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Enums\OrganisationStatus;
use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\FinancialAccount;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\PlatformAdmin;
use App\Models\PlatformAuditEvent;
use App\Models\User;
use App\Support\PhoneNumber;
use App\Support\Theme\ThemeTokens;
use Closure;
use DateTimeZone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrganisationController extends Controller
{
    public function index(): View
    {
        $organisations = Organisation::query()
            ->withCount('organisationUsers')
            ->with(['domains' => fn ($query) => $query->where('is_primary', true)])
            ->latest()
            ->get();

        return view('platform.organisations.index', ['organisations' => $organisations]);
    }

    public function create(): View
    {
        return view('platform.organisations.create');
    }

    /**
     * Creates the organisation, its primary domain, and its first admin in
     * one transaction — the admin's `users` row is reused if the number
     * already belongs to a person on another organisation, so they sign in
     * with the same credentials everywhere. See MEP.md Section 3.3.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('organisations', 'slug')],
            'hostname' => [
                'required', 'string', 'max:255',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (strtolower($value) === strtolower((string) config('platform.hostname'))) {
                        $fail('This hostname is reserved for the platform.');
                    }
                },
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (Domain::query()->whereRaw('lower(hostname) = ?', [strtolower($value)])->exists()) {
                        $fail('This hostname is already mapped to an organisation.');
                    }
                },
            ],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_phone' => ['required', 'string', 'max:50'],
            'admin_password' => ['nullable', 'string', 'min:8'],
        ]);

        $adminPhone = PhoneNumber::normalise($validated['admin_phone']);

        if ($adminPhone === null) {
            return back()->withInput()->withErrors([
                'admin_phone' => 'Enter a valid WhatsApp number.',
            ]);
        }

        $existingUser = User::query()->where('phone', $adminPhone)->first();

        if (! $existingUser && ! $validated['admin_password']) {
            return back()->withInput()->withErrors([
                'admin_password' => 'This number has no account yet — set a password to create one.',
            ]);
        }

        /** @var PlatformAdmin $platformAdmin */
        $platformAdmin = Auth::guard('platform')->user();

        $organisation = DB::transaction(function () use ($validated, $existingUser, $platformAdmin, $adminPhone): Organisation {
            $organisation = Organisation::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'created_by' => $platformAdmin->id,
                // Seeded so the expense form is usable on day one; the admin
                // prunes and extends the list in organisation settings.
                'expense_categories' => Organisation::DEFAULT_EXPENSE_CATEGORIES,
            ]);

            // Every fee collection has to name the account it was received
            // into, so a new organisation needs at least one before it can
            // take a single payment. Cash is the safe universal default.
            FinancialAccount::create([
                'organisation_id' => $organisation->id,
                'name' => 'Cash',
                'account_type' => FinancialAccountType::Cash,
                'status' => FinancialAccountStatus::Active,
            ]);

            Domain::create([
                'organisation_id' => $organisation->id,
                'hostname' => strtolower($validated['hostname']),
                'status' => DomainStatus::Active,
                'is_primary' => true,
            ]);

            $user = $existingUser ?? User::create([
                'name' => $validated['admin_name'],
                'phone' => $adminPhone,
                'password' => Hash::make($validated['admin_password']),
            ]);

            OrganisationUser::create([
                'organisation_id' => $organisation->id,
                'user_id' => $user->id,
                'role' => MembershipRole::Admin,
                'status' => MembershipStatus::Active,
            ]);

            PlatformAuditEvent::record(
                actor: $platformAdmin,
                action: 'organisation.created',
                entityType: 'organisation',
                entityId: $organisation->id,
                after: [
                    'name' => $organisation->name,
                    'slug' => $organisation->slug,
                    'hostname' => strtolower($validated['hostname']),
                    'admin_phone' => $user->phone,
                ],
            );

            return $organisation;
        });

        return redirect()->route('platform.organisations.index')
            ->with('status', "\"{$organisation->name}\" was created.");
    }

    public function edit(Organisation $organisation): View
    {
        $organisation->load(['domains', 'organisationUsers.user']);

        return view('platform.organisations.edit', [
            'organisation' => $organisation,
            'timezones' => DateTimeZone::listIdentifiers(),
        ]);
    }

    public function update(Request $request, Organisation $organisation): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('organisations', 'slug')->ignore($organisation->id)],
            'status' => ['required', Rule::enum(OrganisationStatus::class)],
            'timezone' => ['required', 'timezone'],
            'currency_code' => ['required', 'string', 'size:3', 'uppercase'],
            'locale' => ['required', 'string', 'max:10'],
            // Branding is a platform-admin decision: it is the one visual
            // setting a gym cannot change for itself.
            'theme' => ['nullable', 'array'],
            'theme.*' => ['nullable', 'array'],
            'theme.*.*' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            // Which modules the organisation gets. An unticked list is a
            // valid choice (nothing but the dashboard), so absence is not
            // treated as "leave alone".
            'features' => ['nullable', 'array'],
            'features.*' => ['string', Rule::enum(Feature::class)],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'terminology_member_singular' => ['required', 'string', 'max:50'],
            'terminology_member_plural' => ['required', 'string', 'max:50'],
            'terminology_user_singular' => ['required', 'string', 'max:50'],
            'terminology_user_plural' => ['required', 'string', 'max:50'],
            'terminology_club_singular' => ['required', 'string', 'max:50'],
            'terminology_club_plural' => ['required', 'string', 'max:50'],
        ]);

        /** @var PlatformAdmin $platformAdmin */
        $platformAdmin = Auth::guard('platform')->user();

        // Only colours that were actually set are kept, so an untouched field
        // keeps following the built-in palette (and the accent) rather than
        // freezing today's value.
        $theme = ThemeTokens::sanitize($validated['theme'] ?? []);
        unset($validated['theme']);
        $validated['theme_colors'] = $theme === [] ? null : $theme;

        // The brand accent is the palette's light primary colour: tints, the
        // dark-theme lift, and the installed-app icon all derive from it.
        $validated['accent_color'] = $theme['light']['accent'] ?? null;

        // Closed over what each module needs, so a stored list never names a
        // module without the ones it cannot work without.
        /** @var array<int, string> $features */
        $features = $validated['features'] ?? [];
        $validated['features'] = Feature::expand($features);

        $before = $organisation->only(array_keys($validated));

        $organisation->update($validated);

        PlatformAuditEvent::record(
            actor: $platformAdmin,
            action: 'organisation.updated',
            entityType: 'organisation',
            entityId: $organisation->id,
            before: $before,
            after: $validated,
        );

        return redirect()->route('platform.organisations.edit', $organisation)
            ->with('status', "\"{$organisation->name}\" was updated.");
    }
}
