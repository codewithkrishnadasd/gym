<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One person responsible for each part of a task, on top of the task's
     * own assignees. Being given a part makes the task visible to them.
     */
    public function up(): void
    {
        Schema::table('task_items', function (Blueprint $table): void {
            $table->foreignId('assignee_id')->nullable()->after('task_status_id')->constrained('organisation_users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('task_items', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('assignee_id');
        });
    }
};
