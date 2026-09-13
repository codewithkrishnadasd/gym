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
        Schema::create('financial_accounts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->string('name');
            $table->string('account_type');
            $table->string('bank_name')->nullable();
            $table->string('account_number_last4', 4)->nullable();
            $table->string('upi_id')->nullable();
            $table->text('qr_payload')->nullable();
            $table->string('qr_image_path')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index(['organisation_id', 'status']);
        });

        DB::statement("ALTER TABLE financial_accounts ADD CONSTRAINT financial_accounts_account_type_check CHECK (account_type IN ('bank','upi','cash','other'))");
        DB::statement("ALTER TABLE financial_accounts ADD CONSTRAINT financial_accounts_status_check CHECK (status IN ('active','archived'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_accounts');
    }
};
