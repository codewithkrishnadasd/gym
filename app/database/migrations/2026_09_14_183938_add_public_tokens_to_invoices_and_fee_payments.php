<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Unguessable tokens behind the shareable invoice and receipt links. The
     * token is the only thing in the URL, so a link says nothing about how
     * many invoices exist or in what order. Existing rows get a token now so
     * their links work immediately.
     *
     * Written out per table (not looped) so static analysis can read the
     * schema.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table): void {
            $table->string('public_token', 40)->nullable()->unique();
        });

        Schema::table('fee_payments', function (Blueprint $table): void {
            $table->string('public_token', 40)->nullable()->unique();
        });

        DB::statement('UPDATE invoices SET public_token = md5(random()::text || clock_timestamp()::text || id::text) WHERE public_token IS NULL');
        DB::statement('UPDATE fee_payments SET public_token = md5(random()::text || clock_timestamp()::text || id::text) WHERE public_token IS NULL');
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table): void {
            $table->dropColumn('public_token');
        });

        Schema::table('fee_payments', function (Blueprint $table): void {
            $table->dropColumn('public_token');
        });
    }
};
