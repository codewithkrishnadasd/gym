<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Discussion on a task. A comment can mention colleagues with "@Name";
     * each mention is stored on its own so that "tasks I was mentioned in"
     * is one join rather than a text search.
     */
    public function up(): void
    {
        Schema::create('task_comments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organisation_user_id')->constrained()->restrictOnDelete();
            $table->text('body');
            $table->timestamps();

            $table->index(['task_id', 'created_at']);
        });

        Schema::create('task_mentions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_comment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organisation_user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['task_comment_id', 'organisation_user_id']);
            $table->index(['organisation_user_id', 'task_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_mentions');
        Schema::dropIfExists('task_comments');
    }
};
