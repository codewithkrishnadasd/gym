<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `users` is the global authentication identity. One row here can back
     * memberships in many organisations via `organisation_users` — this is
     * what lets one email/password sign in to any organisation the person
     * belongs to, even across different domains. See MEP.md Section 5.3.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('phone')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('phone');
        });
    }
};
