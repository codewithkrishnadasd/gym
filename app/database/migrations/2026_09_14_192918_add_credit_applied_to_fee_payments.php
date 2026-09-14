<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Money a member paid without saying what for ("Not linked to anything")
     * sits as credit. Later it can be applied to a plan, invoice or admission
     * fee as part of another payment: `credit_applied_minor` is how much of
     * that credit the payment used. It counts as paid on the target, but not
     * as new revenue — that was counted when the unlinked payment was
     * confirmed.
     */
    public function up(): void
    {
        Schema::table('fee_payments', function (Blueprint $table): void {
            $table->bigInteger('credit_applied_minor')->default(0)->after('discount_minor');
        });

        DB::statement('ALTER TABLE fee_payments DROP CONSTRAINT IF EXISTS fee_payments_amount_positive_check');
        DB::statement('ALTER TABLE fee_payments ADD CONSTRAINT fee_payments_amount_positive_check CHECK (amount_minor >= 0 AND discount_minor >= 0 AND credit_applied_minor >= 0 AND amount_minor + discount_minor + credit_applied_minor > 0)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE fee_payments DROP CONSTRAINT IF EXISTS fee_payments_amount_positive_check');
        DB::statement('ALTER TABLE fee_payments ADD CONSTRAINT fee_payments_amount_positive_check CHECK (amount_minor >= 0 AND discount_minor >= 0 AND amount_minor + discount_minor > 0)');

        Schema::table('fee_payments', function (Blueprint $table): void {
            $table->dropColumn('credit_applied_minor');
        });
    }
};
