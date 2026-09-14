<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Per-theme colour overrides chosen by the platform admin, shaped as
     * {"light": {"app": "#hex", ...}, "dark": {...}}. Only tokens that differ
     * from the built-in palette are stored; see App\Support\Theme\ThemeTokens.
     */
    public function up(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->jsonb('theme_colors')->nullable()->after('accent_color');
        });
    }

    public function down(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->dropColumn('theme_colors');
        });
    }
};
