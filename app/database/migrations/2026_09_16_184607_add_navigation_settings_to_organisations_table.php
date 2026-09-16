<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * What the phone tab bar shows and which action the floating button on
     * the dashboard opens — see Organisation::mobileNavigation() and
     * Navigation::quickAction(). Null means the built-in choice.
     */
    public function up(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->jsonb('navigation_settings')->nullable()->after('features');
        });
    }

    public function down(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->dropColumn('navigation_settings');
        });
    }
};
