<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `default_country_code` gives `App\Support\PhoneNumber` the dialling code
     * to apply to nationally-formatted numbers before a `wa.me` link is built,
     * and `notification_settings` holds the per-organisation WhatsApp action
     * notification switches described in MEP.md Section 5.14.
     */
    public function up(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->string('default_country_code', 2)->default('IN')->after('locale');
            $table->jsonb('notification_settings')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->dropColumn(['default_country_code', 'notification_settings']);
        });
    }
};
