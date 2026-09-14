<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Staff attendance is a day, not a day at a club: one person is either in
     * or not. Their records drop the club (NULL) and are unique per person
     * per day across the organisation. Members keep the per-club shape.
     *
     * The unique index is rebuilt NULLS NOT DISTINCT (PostgreSQL 15+) so the
     * same ON CONFLICT upsert keeps working when club_id is NULL.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            $table->foreignId('club_id')->nullable()->change();
        });

        // A staff member marked at two clubs on one day collapses to the most
        // recent mark; the older rows would otherwise collide once club-less.
        DB::statement(<<<'SQL'
            DELETE FROM attendances a
            USING attendances b
            WHERE a.subject_type = 'user'
              AND b.subject_type = 'user'
              AND a.organisation_id = b.organisation_id
              AND a.subject_id = b.subject_id
              AND a.attendance_date = b.attendance_date
              AND (a.updated_at < b.updated_at OR (a.updated_at = b.updated_at AND a.id < b.id))
        SQL);

        DB::statement("UPDATE attendances SET club_id = NULL WHERE subject_type = 'user'");

        DB::statement('ALTER TABLE attendances DROP CONSTRAINT IF EXISTS attendances_unique_per_day');
        DB::statement('DROP INDEX IF EXISTS attendances_unique_per_day');
        DB::statement('CREATE UNIQUE INDEX attendances_unique_per_day ON attendances (organisation_id, club_id, subject_type, subject_id, attendance_date) NULLS NOT DISTINCT');
    }

    public function down(): void
    {
        // Records without a club cannot be given one back.
        DB::statement('DELETE FROM attendances WHERE club_id IS NULL');

        DB::statement('DROP INDEX IF EXISTS attendances_unique_per_day');

        Schema::table('attendances', function (Blueprint $table): void {
            $table->foreignId('club_id')->nullable(false)->change();
            $table->unique(['organisation_id', 'club_id', 'subject_type', 'subject_id', 'attendance_date'], 'attendances_unique_per_day');
        });
    }
};
