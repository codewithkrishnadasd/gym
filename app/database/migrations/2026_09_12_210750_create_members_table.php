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
        Schema::create('members', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignId('primary_club_id')->nullable()->constrained('clubs')->nullOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('photo_path')->nullable();
            $table->jsonb('address')->nullable();
            $table->jsonb('emergency_contact')->nullable();
            $table->date('joined_at');
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('organisation_users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organisation_id', 'status']);
            $table->index(['organisation_id', 'primary_club_id']);
            $table->index(['organisation_id', 'phone']);
            $table->index(['organisation_id', 'name']);
        });

        DB::statement("ALTER TABLE members ADD CONSTRAINT members_status_check CHECK (status IN ('active','paused','inactive','archived'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
