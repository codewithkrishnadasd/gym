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
use App\Support\SettingsTabs;
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
    /** Which fields each console tab's form posts. */
    private const SECTIONS = ['general', 'features', 'appearance'];

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

    /**
     * One page, tabbed like an organisation's own settings page: the console's
     * tabs (general, features, appearance, domains, people) first, then every
     * tab the organisation's admins have — rendered by the same Livewire
     * settings component, acting on this organisation.
     */
    public function edit(Request $request, Organisation $organisation): View
    {
        $organisation->load(['domains', 'organisationUsers.user']);

        $tab = SettingsTabs::resolve($organisation, true, $request->query('tab'));

        // The settings component is written for a tenant domain, where the
        // organisation is the bound tenant. Bound here for this page load;
        // the component rebinds it on its own later requests.
        if (! SettingsTabs::isPlatformTab($tab)) {
            app()->instance('tenant', $organisation);
        }

        return view('platform.organisations.edit', [
            'organisation' => $organisation,
            'tab' => $tab,
            'tabs' => SettingsTabs::items($organisation, true, $tab),
            'timezones' => DateTimeZone::listIdentifiers(),
        ]);
    }

    /**
     * Saves one tab's form — `section` names it — or, with no section, all of
     * them at once.
     */
    public function update(Request $request, Organisation $organisation): RedirectResponse
    {
        $request->validate(['section' => ['nullable', Rule::in(self::SECTIONS)]]);

        $section = $request->input('section');
        $sections = $section === null ? self::SECTIONS : [$section];

        $rules = [];

        if (in_array('general', $sections, true)) {
            $rules += [
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('organisations', 'slug')->ignore($organisation->id)],
                'status' => ['required', Rule::enum(OrganisationStatus::class)],
                'timezone' => ['required', 'timezone'],
                'currency_code' => ['required', 'string', 'size:3', 'uppercase'],
                'locale' => ['required', 'string', 'max:10'],
                'contact_email' => ['nullable', 'email', 'max:255'],
                'contact_phone' => ['nullable', 'string', 'max:50'],
            ];
        }

        if (in_array('appearance', $sections, true)) {
            // Branding is a platform-admin decision: it is the one visual
            // setting a gym cannot change for itself.
            $rules += [
                'theme' => ['nullable', 'array'],
                'theme.*' => ['nullable', 'array'],
                'theme.*.*' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            ];
        }

        if (in_array('features', $sections, true)) {
            // Which modules the organisation gets. An unticked list is a
            // valid choice (nothing but the dashboard), so absence is not
            // treated as "leave alone".
            $rules += [
                'features' => ['nullable', 'array'],
                'features.*' => ['string', Rule::enum(Feature::class)],
            ];
        }

        $validated = $request->validate($rules);

        /** @var PlatformAdmin $platformAdmin */
        $platformAdmin = Auth::guard('platform')->user();

        if (in_array('appearance', $sections, true)) {
            // Only colours that were actually set are kept, so an untouched
            // field keeps following the built-in palette (and the accent)
            // rather than freezing today's value.
            $theme = ThemeTokens::sanitize($validated['theme'] ?? []);
            unset($validated['theme']);
            $validated['theme_colors'] = $theme === [] ? null : $theme;

            // The brand accent is the palette's light primary colour: tints,
            // the dark-theme lift, and the installed-app icon all derive from it.
            $validated['accent_color'] = $theme['light']['accent'] ?? null;
        }

        if (in_array('features', $sections, true)) {
            // Closed over what each module needs, so a stored list never
            // names a module without the ones it cannot work without.
            /** @var array<int, string> $features */
            $features = $validated['features'] ?? [];
            $validated['features'] = Feature::expand($features);
        }

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

        return redirect()->route('platform.organisations.edit', ['organisation' => $organisation, 'tab' => $section ?? 'general'])
            ->with('status', "\"{$organisation->name}\" was updated.");
    }
}
