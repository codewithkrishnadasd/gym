<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Dated reminders on a task. From the reminder date until the task is
     * done, everyone involved sees it on opening the app.
     */
    public function up(): void
    {
        Schema::create('task_reminders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->string('label', 200);
            $table->date('remind_on');
            $table->foreignId('created_by')->constrained('organisation_users')->restrictOnDelete();
            $table->timestamps();

            $table->index(['remind_on', 'task_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_reminders');
    }
};
