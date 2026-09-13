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
        Schema::create('expenses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignId('club_id')->nullable()->constrained('clubs')->nullOnDelete();
            $table->string('category');
            $table->unsignedBigInteger('amount_minor');
            $table->string('currency_code', 3);
            $table->date('expense_date');
            $table->foreignId('paid_from_financial_account_id')->nullable()->constrained('financial_accounts')->nullOnDelete();
            $table->string('payee')->nullable();
            $table->text('description')->nullable();
            $table->string('receipt_path')->nullable();
            $table->foreignId('created_by')->constrained('organisation_users')->restrictOnDelete();
            $table->string('status')->default('completed');
            $table->string('target_type')->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->timestamps();

            $table->index(['organisation_id', 'expense_date']);
            $table->index(['organisation_id', 'club_id']);
            $table->index(['target_type', 'target_id']);
        });

        DB::statement("ALTER TABLE expenses ADD CONSTRAINT expenses_status_check CHECK (status IN ('completed','reversed'))");
        DB::statement("ALTER TABLE expenses ADD CONSTRAINT expenses_target_type_check CHECK (target_type IS NULL OR target_type IN ('organisation','club','member','user'))");
        DB::statement('ALTER TABLE expenses ADD CONSTRAINT expenses_amount_positive_check CHECK (amount_minor > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
