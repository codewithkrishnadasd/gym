<?php

declare(strict_types=1);

use App\Enums\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Rewrites every stored permission map so it includes the prerequisites its
 * grants imply, and carries the new `staff.view` key.
 *
 * `OrganisationUser::hasPermission()` expands on read, so access is already
 * correct without this — but the settings screen shows what is stored, and an
 * admin looking at a staff member who can create members while "View members"
 * sits unticked has been told something untrue about their own configuration.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (DB::table('organisation_users')->select('id', 'permissions')->orderBy('id')->cursor() as $row) {
            /** @var array<string, bool>|null $stored */
            $stored = json_decode((string) $row->permissions, true);

            if (! is_array($stored) || $stored === []) {
                continue;
            }

            $normalised = Permission::map(array_keys(array_filter($stored)));

            if ($normalised !== $stored) {
                DB::table('organisation_users')
                    ->where('id', $row->id)
                    ->update(['permissions' => json_encode($normalised)]);
            }
        }
    }

    public function down(): void
    {
        // Nothing to reverse: the expanded set is what these staff already had
        // in practice, and narrowing it again would remove access people are
        // by now relying on.
    }
};
