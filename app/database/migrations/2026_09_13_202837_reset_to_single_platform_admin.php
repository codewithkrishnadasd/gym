<?php

declare(strict_types=1);

use App\Support\PhoneNumber;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Empties every application table and leaves exactly one platform admin
 * behind, so the deployment starts from a clean slate with a single known
 * login.
 *
 * This deletes data rather than changing structure, which is unusual for a
 * migration. It is done here so the reset happens exactly once per database,
 * as part of the same `php artisan migrate` the deploy already runs — a
 * seeder would need a separate command and could be re-run by accident.
 *
 * `audit_events` and `platform_audit_events` are append-only everywhere in the
 * application (MEP.md Section 9); they are cleared here only because the
 * organisations those entries describe no longer exist after this runs, and an
 * audit trail pointing at deleted rows is worse than none.
 */
return new class extends Migration
{
    /**
     * Cleared with one `TRUNCATE ... CASCADE`, so foreign keys between them
     * never dictate the order. Listed child-first anyway to document how the
     * data hangs together.
     *
     * @var list<string>
     */
    private const DATA_TABLES = [
        'whatsapp_action_notifications',
        'organisation_daily_metrics',
        'audit_events',
        'platform_audit_events',
        'attendances',
        'fee_payments',
        'expenses',
        'member_subscriptions',
        'member_club_history',
        'members',
        'plans',
        'financial_accounts',
        'message_templates',
        'club_user_assignments',
        'clubs',
        'organisation_users',
        'domains',
        'organisations',
        'users',
        'platform_admins',
    ];

    /**
     * Queue and session state left over from the old data set. A job holding
     * an ID that no longer exists would fail on its next attempt, and a live
     * session cookie would authenticate against a deleted user.
     *
     * @var list<string>
     */
    private const RUNTIME_TABLES = [
        'sessions',
        'jobs',
        'job_batches',
        'failed_jobs',
        'cache',
        'cache_locks',
    ];

    /** The number as an operator types it at the login screen. */
    private const ADMIN_PHONE = '9539439229';

    public function up(): void
    {
        $tables = array_filter(
            [...self::DATA_TABLES, ...self::RUNTIME_TABLES],
            static fn (string $table): bool => Schema::hasTable($table),
        );

        // RESTART IDENTITY so the new admin is id 1 and nothing inherits an ID
        // from the data that was just removed.
        DB::statement(
            'TRUNCATE TABLE '.implode(', ', $tables).' RESTART IDENTITY CASCADE'
        );

        // Login normalises what is typed before looking the row up, so the
        // stored value has to be the normalised form ("919539439229") or the
        // lookup can never match. The password is the same digits as typed,
        // hashed here because nothing hashes it on the way in — a raw string
        // in this column would fail every login attempt, not bypass one.
        DB::table('platform_admins')->insert([
            'name' => 'Platform Admin',
            'phone' => PhoneNumber::normalise(self::ADMIN_PHONE),
            'password' => Hash::make(self::ADMIN_PHONE),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // Nothing to reverse: the data this migration removed is gone, and
        // deleting the admin it created would leave no way to sign in at all.
    }
};
