<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Indexes for the two list filters that had none of their own: payments
     * by date across all clubs (the ledger's default view), and tasks by
     * open/done (the task list's default view).
     */
    public function up(): void
    {
        Schema::table('fee_payments', function (Blueprint $table): void {
            $table->index(['organisation_id', 'payment_date'], 'fee_payments_org_date_index');
        });

        Schema::table('tasks', function (Blueprint $table): void {
            $table->index(['organisation_id', 'completed_at'], 'tasks_org_completed_index');
            $table->index('created_by', 'tasks_created_by_index');
        });
    }

    public function down(): void
    {
        Schema::table('fee_payments', function (Blueprint $table): void {
            $table->dropIndex('fee_payments_org_date_index');
        });

        Schema::table('tasks', function (Blueprint $table): void {
            $table->dropIndex('tasks_org_completed_index');
            $table->dropIndex('tasks_created_by_index');
        });
    }
};
