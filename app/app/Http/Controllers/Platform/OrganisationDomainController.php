<?php

declare(strict_types=1);

namespace App\Http\Controllers\Platform;

use App\Enums\DomainStatus;
use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Organisation;
use App\Models\PlatformAdmin;
use App\Models\PlatformAuditEvent;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrganisationDomainController extends Controller
{
    public function store(Request $request, Organisation $organisation): RedirectResponse
    {
        $validated = $request->validate([
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
            'status' => ['required', Rule::enum(DomainStatus::class)],
        ]);

        /** @var PlatformAdmin $platformAdmin */
        $platformAdmin = Auth::guard('platform')->user();

        $domain = $organisation->domains()->create([
            'hostname' => strtolower($validated['hostname']),
            'status' => $validated['status'],
            'is_primary' => ! $organisation->domains()->exists(),
        ]);

        PlatformAuditEvent::record(
            actor: $platformAdmin,
            action: 'domain.created',
            entityType: 'domain',
            entityId: $domain->id,
            after: ['organisation_id' => $organisation->id, 'hostname' => $domain->hostname, 'status' => $domain->status->value],
        );

        return redirect()->route('platform.organisations.edit', ['organisation' => $organisation, 'tab' => 'domains'])
            ->with('status', "Domain \"{$domain->hostname}\" was added.");
    }

    public function update(Request $request, Organisation $organisation, Domain $domain): RedirectResponse
    {
        abort_unless($domain->organisation_id === $organisation->id, 404);

        $validated = $request->validate([
            'status' => ['required', Rule::enum(DomainStatus::class)],
            'is_primary' => ['nullable', 'boolean'],
        ]);

        /** @var PlatformAdmin $platformAdmin */
        $platformAdmin = Auth::guard('platform')->user();
        $before = $domain->only(['status', 'is_primary']);
        $makingPrimary = (bool) ($validated['is_primary'] ?? false);

        DB::transaction(function () use ($organisation, $domain, $validated, $makingPrimary): void {
            if ($makingPrimary) {
                $organisation->domains()->where('id', '!=', $domain->id)->update(['is_primary' => false]);
            }

            $domain->update([
                'status' => $validated['status'],
                'is_primary' => $makingPrimary ?: $domain->is_primary,
            ]);
        });

        PlatformAuditEvent::record(
            actor: $platformAdmin,
            action: 'domain.updated',
            entityType: 'domain',
            entityId: $domain->id,
            before: $before,
            after: $domain->only(['status', 'is_primary']),
        );

        return redirect()->route('platform.organisations.edit', ['organisation' => $organisation, 'tab' => 'domains'])
            ->with('status', "Domain \"{$domain->hostname}\" was updated.");
    }

    public function destroy(Organisation $organisation, Domain $domain): RedirectResponse
    {
        abort_unless($domain->organisation_id === $organisation->id, 404);

        if ($organisation->domains()->count() <= 1) {
            return back()->withErrors(['hostname' => 'An organisation must keep at least one domain.']);
        }

        /** @var PlatformAdmin $platformAdmin */
        $platformAdmin = Auth::guard('platform')->user();
        $hostname = $domain->hostname;
        $wasPrimary = $domain->is_primary;

        $domain->delete();

        if ($wasPrimary) {
            $organisation->domains()->oldest()->first()?->update(['is_primary' => true]);
        }

        PlatformAuditEvent::record(
            actor: $platformAdmin,
            action: 'domain.deleted',
            entityType: 'domain',
            entityId: null,
            before: ['organisation_id' => $organisation->id, 'hostname' => $hostname],
        );

        return redirect()->route('platform.organisations.edit', ['organisation' => $organisation, 'tab' => 'domains'])
            ->with('status', "Domain \"{$hostname}\" was removed.");
    }
}
