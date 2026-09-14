<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The one colour an organisation can be branded with.
 *
 * Deliberately a single accent rather than a full palette: the rest of the
 * design system — surfaces, ink, and the semantic positive/caution/critical
 * colours — carries meaning that must stay consistent, and letting each gym
 * repaint "critical" would make the interface unreadable rather than branded.
 *
 * Null keeps the built-in teal.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->string('accent_color', 7)->nullable()->after('favicon_path');
        });
    }

    public function down(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->dropColumn('accent_color');
        });
    }
};
