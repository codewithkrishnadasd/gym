<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tenant-specific membership and authority. `users` is the single global
     * identity (one email/password); a person gets one row here per
     * organisation they belong to, each with its own role and permissions.
     * This is what lets the same login work across organisations that live
     * on entirely different domains. See MEP.md Section 5.3.
     */
    public function up(): void
    {
        Schema::create('organisation_users', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role')->default('user');
            $table->string('status')->default('invited');
            $table->jsonb('permissions')->default('{}');
            $table->jsonb('club_ids')->default('[]');
            $table->timestamp('last_login_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('organisation_users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['organisation_id', 'user_id']);
            $table->index(['organisation_id', 'status']);
            $table->index(['organisation_id', 'role']);
        });

        DB::statement("ALTER TABLE organisation_users ADD CONSTRAINT organisation_users_role_check CHECK (role IN ('admin','user'))");
        DB::statement("ALTER TABLE organisation_users ADD CONSTRAINT organisation_users_status_check CHECK (status IN ('invited','active','suspended','deactivated'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('organisation_users');
    }
};
