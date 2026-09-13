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
        Schema::create('fee_payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignId('club_id')->constrained('clubs')->restrictOnDelete();
            $table->foreignId('member_id')->constrained('members')->restrictOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained('member_subscriptions')->nullOnDelete();
            $table->string('payer_name');
            $table->unsignedBigInteger('amount_minor');
            $table->string('currency_code', 3);
            $table->string('payment_method');
            $table->foreignId('financial_account_id')->nullable()->constrained('financial_accounts')->nullOnDelete();
            $table->string('transaction_reference')->nullable();
            $table->date('payment_date');
            $table->foreignId('collected_by')->constrained('organisation_users')->restrictOnDelete();
            $table->text('notes')->nullable();

            $table->string('confirmation_status')->default('pending_admin_confirmation');
            $table->foreignId('confirmed_by')->nullable()->constrained('organisation_users')->nullOnDelete();
            $table->timestamp('confirmed_at')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->string('reversal_reason')->nullable();

            $table->string('whatsapp_status')->default('not_sent');
            $table->string('whatsapp_message_template_version')->nullable();
            $table->text('whatsapp_message_snapshot')->nullable();

            $table->timestamps();

            $table->index(['organisation_id', 'confirmation_status']);
            $table->index(['organisation_id', 'club_id', 'payment_date']);
            $table->index(['organisation_id', 'member_id']);
            $table->index(['organisation_id', 'collected_by']);
        });

        DB::statement("ALTER TABLE fee_payments ADD CONSTRAINT fee_payments_payment_method_check CHECK (payment_method IN ('cash','bank_transfer','upi','card','other'))");
        DB::statement("ALTER TABLE fee_payments ADD CONSTRAINT fee_payments_confirmation_status_check CHECK (confirmation_status IN ('pending_admin_confirmation','confirmed','rejected','reversed'))");
        DB::statement("ALTER TABLE fee_payments ADD CONSTRAINT fee_payments_whatsapp_status_check CHECK (whatsapp_status IN ('not_sent','ready','opened','skipped','failed'))");
        DB::statement('ALTER TABLE fee_payments ADD CONSTRAINT fee_payments_amount_positive_check CHECK (amount_minor > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};
