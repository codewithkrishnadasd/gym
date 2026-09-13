<?php

declare(strict_types=1);

use App\Support\PhoneNumber;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Replaces email with the WhatsApp number as the identity for people, on both
 * the tenant and platform sides.
 *
 * Existing rows are normalised to bare international digits so the login
 * lookup is an exact match. A row with no number — or one that collides with
 * another row — is parked on a `pending-<id>` placeholder: it satisfies the
 * NOT NULL and unique constraints, but `PhoneNumber::normalise()` can never
 * produce that shape, so those accounts simply cannot sign in until an
 * operator sets a real number. That is deliberately safer than inventing a
 * plausible-looking number somebody could guess.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->adoptPhoneIdentity('users');

        Schema::table('platform_admins', function (Blueprint $table): void {
            $table->string('phone')->nullable()->after('name');
        });

        $this->adoptPhoneIdentity('platform_admins');

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['email', 'email_verified_at']);
        });

        Schema::table('platform_admins', function (Blueprint $table): void {
            $table->dropColumn(['email', 'email_verified_at']);
        });

        // A member is now always reachable on WhatsApp; email is gone.
        $this->backfillPhones('members');

        Schema::table('members', function (Blueprint $table): void {
            $table->dropColumn('email');
        });

        DB::statement('ALTER TABLE members ALTER COLUMN phone SET NOT NULL');

        // Both reset-token tables are keyed by email address, and there is no
        // email channel left to send a reset through.
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('platform_password_reset_tokens');
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->dropUnique('users_phone_unique');
        });

        Schema::table('platform_admins', function (Blueprint $table): void {
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->dropColumn('phone');
        });

        Schema::table('members', function (Blueprint $table): void {
            $table->string('email')->nullable();
        });

        DB::statement('ALTER TABLE members ALTER COLUMN phone DROP NOT NULL');

        Schema::create('password_reset_tokens', function (Blueprint $table): void {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('platform_password_reset_tokens', function (Blueprint $table): void {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Normalises, de-duplicates and constrains a table's `phone` column so it
     * can serve as the login identity.
     */
    private function adoptPhoneIdentity(string $table): void
    {
        $this->backfillPhones($table);

        DB::statement("ALTER TABLE {$table} ALTER COLUMN phone SET NOT NULL");

        Schema::table($table, function (Blueprint $blueprint): void {
            $blueprint->unique('phone');
        });
    }

    private function backfillPhones(string $table): void
    {
        $seen = [];

        foreach (DB::table($table)->select('id', 'phone')->orderBy('id')->cursor() as $row) {
            $normalised = PhoneNumber::normalise($row->phone);

            if ($normalised === null || isset($seen[$normalised])) {
                $normalised = 'pending-'.$row->id;
            }

            $seen[$normalised] = true;

            if ($normalised !== $row->phone) {
                DB::table($table)->where('id', $row->id)->update(['phone' => $normalised]);
            }
        }
    }
};
