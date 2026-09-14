<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tasks, organised by category.
     *
     * A category owns a set of statuses and a list of sub-categories, and each
     * sub-category owns its own statuses. A task belongs to one category,
     * carries one of that category's statuses, and has one item per
     * sub-category, each carrying one of the sub-category's statuses. Statuses
     * are coloured; the colour is the status's identity across the interface.
     */
    public function up(): void
    {
        Schema::create('task_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained()->cascadeOnDelete();
            $table->string('name', 120);
            $table->string('status', 20)->default('active');
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        DB::statement("ALTER TABLE task_categories ADD CONSTRAINT task_categories_status_check CHECK (status IN ('active','archived'))");

        Schema::create('task_sub_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_category_id')->constrained()->cascadeOnDelete();
            $table->string('name', 120);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        // One table for both kinds of status: exactly one owner is set.
        Schema::create('task_statuses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('task_sub_category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name', 60);
            $table->string('color', 7);
            $table->boolean('completes')->default(false);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE task_statuses ADD CONSTRAINT task_statuses_one_owner_check CHECK ((task_category_id IS NULL) <> (task_sub_category_id IS NULL))');

        Schema::create('tasks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_category_id')->constrained()->restrictOnDelete();
            $table->foreignId('task_status_id')->nullable()->constrained('task_statuses')->nullOnDelete();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->foreignId('created_by')->constrained('organisation_users')->restrictOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['organisation_id', 'task_category_id']);
            $table->index(['organisation_id', 'due_date']);
        });

        Schema::create('task_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_sub_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_status_id')->nullable()->constrained('task_statuses')->nullOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['task_id', 'task_sub_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_items');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('task_statuses');
        Schema::dropIfExists('task_sub_categories');
        Schema::dropIfExists('task_categories');
    }
};
