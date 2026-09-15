<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A fee can be collected from, and an invoice raised for, someone who is
     * not a member — a day visitor, a guest, a company. Such a row names the
     * payer directly instead of pointing at a member, and its WhatsApp
     * message goes to that person rather than to a member or staff record.
     */
    public function up(): void
    {
        Schema::table('fee_payments', function (Blueprint $table): void {
            $table->foreignId('member_id')->nullable()->change();
            $table->string('payer_phone', 50)->nullable()->after('payer_name');
        });

        Schema::table('invoices', function (Blueprint $table): void {
            $table->foreignId('member_id')->nullable()->change();
            $table->string('payer_name')->nullable()->after('member_id');
            $table->string('payer_phone', 50)->nullable()->after('payer_name');
        });

        // Every invoice is addressed to someone: a member, or a named payer.
        DB::statement('ALTER TABLE invoices ADD CONSTRAINT invoices_addressed_check CHECK (member_id IS NOT NULL OR payer_name IS NOT NULL)');

        Schema::table('whatsapp_action_notifications', function (Blueprint $table): void {
            $table->unsignedBigInteger('recipient_id')->nullable()->change();
        });

        DB::statement('ALTER TABLE whatsapp_action_notifications DROP CONSTRAINT IF EXISTS whatsapp_notifications_recipient_type_check');
        DB::statement("ALTER TABLE whatsapp_action_notifications ADD CONSTRAINT whatsapp_notifications_recipient_type_check CHECK (recipient_type IN ('member','user','contact'))");
    }

    public function down(): void
    {
        DB::statement("DELETE FROM whatsapp_action_notifications WHERE recipient_type = 'contact'");
        DB::statement('DELETE FROM fee_payments WHERE member_id IS NULL');
        DB::statement('DELETE FROM invoice_lines WHERE invoice_id IN (SELECT id FROM invoices WHERE member_id IS NULL)');
        DB::statement('DELETE FROM invoices WHERE member_id IS NULL');

        DB::statement('ALTER TABLE whatsapp_action_notifications DROP CONSTRAINT IF EXISTS whatsapp_notifications_recipient_type_check');
        DB::statement("ALTER TABLE whatsapp_action_notifications ADD CONSTRAINT whatsapp_notifications_recipient_type_check CHECK (recipient_type IN ('member','user'))");

        Schema::table('whatsapp_action_notifications', function (Blueprint $table): void {
            $table->unsignedBigInteger('recipient_id')->nullable(false)->change();
        });

        DB::statement('ALTER TABLE invoices DROP CONSTRAINT IF EXISTS invoices_addressed_check');

        Schema::table('invoices', function (Blueprint $table): void {
            $table->dropColumn(['payer_name', 'payer_phone']);
            $table->foreignId('member_id')->nullable(false)->change();
        });

        Schema::table('fee_payments', function (Blueprint $table): void {
            $table->dropColumn('payer_phone');
            $table->foreignId('member_id')->nullable(false)->change();
        });
    }
};
