<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('club_user_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignId('club_id')->constrained('clubs')->cascadeOnDelete();
            $table->foreignId('organisation_user_id')->constrained('organisation_users')->cascadeOnDelete();
            $table->jsonb('permissions_override')->nullable();
            $table->string('status')->default('active');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            $table->index(['organisation_id', 'club_id', 'status']);
            $table->index(['organisation_user_id', 'status']);
        });

        DB::statement("ALTER TABLE club_user_assignments ADD CONSTRAINT club_user_assignments_status_check CHECK (status IN ('active','ended'))");

        // A membership may only have one *active* assignment per club at a time;
        // ended assignments are kept for history and are excluded from the index.
        DB::statement('CREATE UNIQUE INDEX club_user_assignments_active_unique ON club_user_assignments (club_id, organisation_user_id) WHERE status = \'active\'');
    }

    public function down(): void
    {
        Schema::dropIfExists('club_user_assignments');
    }
};
