<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Short-lived, single-use links that let a person set their own password,
 * replacing the old flow where an admin generated one and read it out.
 *
 * A generated password has to travel through a human to reach its owner and
 * stays valid until someone changes it; a link expires on its own and the
 * admin never learns the password at all.
 *
 * Only the SHA-256 of the token is stored. Anyone with database access — a
 * backup, a support session, a leaked dump — can therefore see that a link was
 * issued but cannot use it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('password_reset_links', function (Blueprint $table): void {
            $table->id();

            // The link is always used on one organisation's domain, so the
            // organisation is part of the link's identity rather than
            // something inferred from the request.
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('token_hash', 64)->unique();
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();

            // Exactly one of these is set: an organisation admin issuing a link
            // for their staff, or a platform admin issuing one for an
            // organisation's admin. Two typed columns rather than a polymorphic
            // pair, so both stay foreign-key checked.
            $table->foreignId('issued_by_organisation_user_id')->nullable()
                ->constrained('organisation_users')->nullOnDelete();
            $table->foreignId('issued_by_platform_admin_id')->nullable()
                ->constrained('platform_admins')->nullOnDelete();

            $table->timestamp('created_at')->useCurrent();

            // Supports "supersede this user's outstanding links" on issue, and
            // the scheduled prune of spent ones.
            $table->index(['user_id', 'organisation_id', 'used_at']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_links');
    }
};
