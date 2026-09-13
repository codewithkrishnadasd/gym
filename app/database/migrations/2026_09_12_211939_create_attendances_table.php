<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `subject_type` + `subject_id` is a polymorphic reference into either
     * `members` or `organisation_users`, using Laravel's morph map ('member',
     * 'user') so the column stores the short alias from MEP.md rather than a
     * fully qualified class name. The composite unique index is the actual
     * "at most one attendance record per day" guarantee — see MEP.md 5.9.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignId('club_id')->constrained('clubs')->cascadeOnDelete();
            $table->string('subject_type');
            $table->unsignedBigInteger('subject_id');
            $table->date('attendance_date');
            $table->timestamp('check_in_at')->nullable();
            $table->string('action');
            $table->foreignId('marked_by')->constrained('organisation_users')->restrictOnDelete();
            $table->string('source')->default('manual');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(
                ['organisation_id', 'club_id', 'subject_type', 'subject_id', 'attendance_date'],
                'attendances_unique_per_day'
            );
            $table->index(['organisation_id', 'club_id', 'attendance_date']);
            $table->index(['subject_type', 'subject_id']);
        });

        DB::statement("ALTER TABLE attendances ADD CONSTRAINT attendances_subject_type_check CHECK (subject_type IN ('member','user'))");
        DB::statement("ALTER TABLE attendances ADD CONSTRAINT attendances_action_check CHECK (action IN ('present','absent','late','excused'))");
        DB::statement("ALTER TABLE attendances ADD CONSTRAINT attendances_source_check CHECK (source IN ('manual','import'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
