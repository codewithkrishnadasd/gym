<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * An organisation without the Clubs module has members with no club, and
     * those members still buy plans, pay fees, and receive invoices. The
     * club on each of these rows is therefore a snapshot when there is one,
     * not a requirement.
     */
    public function up(): void
    {
        Schema::table('member_subscriptions', function (Blueprint $table): void {
            $table->foreignId('club_id')->nullable()->change();
        });

        Schema::table('fee_payments', function (Blueprint $table): void {
            $table->foreignId('club_id')->nullable()->change();
        });

        Schema::table('invoices', function (Blueprint $table): void {
            $table->foreignId('club_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Rows written without a club cannot be given one back.
        DB::statement('DELETE FROM fee_payments WHERE club_id IS NULL');
        DB::statement('DELETE FROM invoice_lines WHERE invoice_id IN (SELECT id FROM invoices WHERE club_id IS NULL)');
        DB::statement('DELETE FROM invoices WHERE club_id IS NULL');
        DB::statement('DELETE FROM member_subscriptions WHERE club_id IS NULL');

        Schema::table('member_subscriptions', function (Blueprint $table): void {
            $table->foreignId('club_id')->nullable(false)->change();
        });

        Schema::table('fee_payments', function (Blueprint $table): void {
            $table->foreignId('club_id')->nullable(false)->change();
        });

        Schema::table('invoices', function (Blueprint $table): void {
            $table->foreignId('club_id')->nullable(false)->change();
        });
    }
};
