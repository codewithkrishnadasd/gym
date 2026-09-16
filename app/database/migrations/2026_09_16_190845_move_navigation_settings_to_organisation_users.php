<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The phone tab bar and the dashboard button are personal: each person
     * arranges their own, on their membership, rather than one arrangement
     * for the whole organisation.
     */
    public function up(): void
    {
        Schema::table('organisation_users', function (Blueprint $table): void {
            $table->jsonb('navigation_settings')->nullable()->after('permissions');
        });

        Schema::table('organisations', function (Blueprint $table): void {
            $table->dropColumn('navigation_settings');
        });
    }

    public function down(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->jsonb('navigation_settings')->nullable()->after('features');
        });

        Schema::table('organisation_users', function (Blueprint $table): void {
            $table->dropColumn('navigation_settings');
        });
    }
};
