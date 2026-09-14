<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Who a task is for. Any number of assignees; optionally the member it
     * concerns. Both drive what staff can see: their own tasks and the ones
     * handed to them.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table): void {
            $table->foreignId('member_id')->nullable()->after('task_status_id')->constrained()->nullOnDelete();
        });

        Schema::create('task_assignees', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organisation_user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['task_id', 'organisation_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_assignees');

        Schema::table('tasks', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('member_id');
        });
    }
};
