<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Reporting accelerator, not the source of truth — see MEP.md 8.4 and
     * technology.md 7.1. Rebuilt from source-of-truth tables by a scheduled job.
     */
    public function up(): void
    {
        Schema::create('organisation_daily_metrics', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->date('metric_date');
            $table->unsignedInteger('total_members')->default(0);
            $table->unsignedInteger('new_members')->default(0);
            $table->unsignedInteger('active_members')->default(0);
            $table->unsignedInteger('attendance_present')->default(0);
            $table->unsignedBigInteger('revenue_collected_minor')->default(0);
            $table->unsignedBigInteger('expenses_recorded_minor')->default(0);
            $table->bigInteger('net_movement_minor')->default(0);
            $table->jsonb('by_club')->default('{}');
            $table->timestamp('updated_at');

            $table->unique(['organisation_id', 'metric_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisation_daily_metrics');
    }
};
