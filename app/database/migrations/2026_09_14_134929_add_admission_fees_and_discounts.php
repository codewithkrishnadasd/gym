<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Admission fees and discounts.
     *
     * A club sets its admission fee and a per-plan discount. Each member
     * carries a snapshot of the admission fee they were signed up under, with
     * running paid and discounted totals maintained by the payment lifecycle —
     * the same shape as a subscription's balance. Discounts given at the
     * counter are recorded on the payment and rolled into the thing being
     * paid, so every balance is: owed − discounted − paid.
     */
    public function up(): void
    {
        Schema::table('clubs', function (Blueprint $table): void {
            $table->bigInteger('admission_fee_minor')->default(0)->after('opening_hours');
        });

        Schema::create('club_plan_discounts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('discount_minor')->default(0);
            $table->timestamps();

            $table->unique(['club_id', 'plan_id']);
        });

        Schema::table('members', function (Blueprint $table): void {
            $table->bigInteger('admission_fee_minor')->default(0)->after('joined_at');
            $table->bigInteger('admission_discount_minor')->default(0)->after('admission_fee_minor');
            $table->bigInteger('admission_paid_minor')->default(0)->after('admission_discount_minor');
        });

        Schema::table('member_subscriptions', function (Blueprint $table): void {
            // Cumulative. amount_due_minor is what is owed after it.
            $table->bigInteger('discount_minor')->default(0)->after('amount_due_minor');
        });

        Schema::table('invoices', function (Blueprint $table): void {
            // Cumulative. total_minor stays the gross figure on the document.
            $table->bigInteger('discount_minor')->default(0)->after('total_minor');
        });

        Schema::table('fee_payments', function (Blueprint $table): void {
            $table->string('purpose', 20)->default('other')->after('invoice_id');
            $table->bigInteger('discount_minor')->default(0)->after('amount_minor');
        });

        DB::statement("UPDATE fee_payments SET purpose = CASE WHEN invoice_id IS NOT NULL THEN 'invoice' WHEN subscription_id IS NOT NULL THEN 'plan' ELSE 'other' END");
        DB::statement("ALTER TABLE fee_payments ADD CONSTRAINT fee_payments_purpose_check CHECK (purpose IN ('plan','invoice','admission','other'))");

        // A payment may now be a pure discount (nothing handed over, some of
        // the balance written off), so the amount alone need not be positive —
        // but a record with neither money nor discount would mean nothing.
        DB::statement('ALTER TABLE fee_payments DROP CONSTRAINT IF EXISTS fee_payments_amount_positive_check');
        DB::statement('ALTER TABLE fee_payments ADD CONSTRAINT fee_payments_amount_positive_check CHECK (amount_minor >= 0 AND discount_minor >= 0 AND amount_minor + discount_minor > 0)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE fee_payments DROP CONSTRAINT IF EXISTS fee_payments_amount_positive_check');
        DB::statement('ALTER TABLE fee_payments DROP CONSTRAINT IF EXISTS fee_payments_purpose_check');
        DB::statement('ALTER TABLE fee_payments ADD CONSTRAINT fee_payments_amount_positive_check CHECK (amount_minor > 0)');

        Schema::table('fee_payments', fn (Blueprint $table) => $table->dropColumn(['purpose', 'discount_minor']));
        Schema::table('invoices', fn (Blueprint $table) => $table->dropColumn('discount_minor'));
        Schema::table('member_subscriptions', fn (Blueprint $table) => $table->dropColumn('discount_minor'));
        Schema::table('members', fn (Blueprint $table) => $table->dropColumn(['admission_fee_minor', 'admission_discount_minor', 'admission_paid_minor']));
        Schema::dropIfExists('club_plan_discounts');
        Schema::table('clubs', fn (Blueprint $table) => $table->dropColumn('admission_fee_minor'));
    }
};
