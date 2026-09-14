<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Makes the receiving account mandatory on every fee collection.
 *
 * Without it, an account's balance on the finance pages is only ever a partial
 * picture — money is recorded as received by the organisation but not into
 * anything — and an admin confirming a payment has nothing to reconcile
 * against a statement.
 *
 * Rows predating the rule are given an account rather than being deleted or
 * left behind: the payment happened, and losing it would change the books.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->backfillMissingAccounts();

        Schema::table('fee_payments', function (Blueprint $table): void {
            // The old foreign key nulled the column when an account was
            // deleted, which a NOT NULL column cannot honour. Accounts are
            // removed by status rather than deleted, so restricting is the
            // accurate rule: an account with payments against it stays.
            $table->dropForeign(['financial_account_id']);
        });

        DB::statement('ALTER TABLE fee_payments ALTER COLUMN financial_account_id SET NOT NULL');

        Schema::table('fee_payments', function (Blueprint $table): void {
            $table->foreign('financial_account_id')
                ->references('id')
                ->on('financial_accounts')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fee_payments', function (Blueprint $table): void {
            $table->dropForeign(['financial_account_id']);
        });

        DB::statement('ALTER TABLE fee_payments ALTER COLUMN financial_account_id DROP NOT NULL');

        Schema::table('fee_payments', function (Blueprint $table): void {
            $table->foreign('financial_account_id')
                ->references('id')
                ->on('financial_accounts')
                ->nullOnDelete();
        });
    }

    /**
     * Points every accountless payment at its organisation's first active
     * account, creating a cash account for organisations that have none.
     */
    private function backfillMissingAccounts(): void
    {
        $organisationIds = DB::table('fee_payments')
            ->whereNull('financial_account_id')
            ->distinct()
            ->pluck('organisation_id');

        foreach ($organisationIds as $organisationId) {
            $accountId = DB::table('financial_accounts')
                ->where('organisation_id', $organisationId)
                ->where('status', 'active')
                ->orderBy('id')
                ->value('id');

            $accountId ??= DB::table('financial_accounts')->insertGetId([
                'organisation_id' => $organisationId,
                'name' => 'Cash',
                'account_type' => 'cash',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('fee_payments')
                ->where('organisation_id', $organisationId)
                ->whereNull('financial_account_id')
                ->update(['financial_account_id' => $accountId]);
        }
    }
};
