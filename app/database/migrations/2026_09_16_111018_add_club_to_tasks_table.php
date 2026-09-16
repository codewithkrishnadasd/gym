<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A task can belong to one club — the treadmill at North, the open day
     * at South — so the list can be narrowed to a location. Optional, and
     * absent altogether for organisations without the Clubs module.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table): void {
            $table->foreignId('club_id')->nullable()->after('member_id')->constrained()->nullOnDelete();
            $table->index(['organisation_id', 'club_id']);
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table): void {
            $table->dropIndex(['organisation_id', 'club_id']);
            $table->dropConstrainedForeignId('club_id');
        });
    }
};
