<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A platform admin can now change an organisation's settings from the
     * console. They have no membership to point at, so such events carry no
     * actor row and the role "platform" instead.
     */
    public function up(): void
    {
        Schema::table('audit_events', function (Blueprint $table): void {
            $table->foreignId('actor_user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::statement('DELETE FROM audit_events WHERE actor_user_id IS NULL');

        Schema::table('audit_events', function (Blueprint $table): void {
            $table->foreignId('actor_user_id')->nullable(false)->change();
        });
    }
};
