<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Invoicing for anything that is not a membership plan: a personal-training
 * block, a locker, merchandise, a late fee — priced from an organisation's own
 * catalogue and billed to a member as one document with several lines.
 *
 * Payments against an invoice are ordinary `fee_payments` rows carrying an
 * `invoice_id`, so they go through the same pending → confirmed lifecycle, land
 * in the same financial accounts, and appear in the same ledger and reports as
 * plan fees. The invoice tracks how much of its total those confirmed payments
 * have covered, which is what makes partial payment work.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billable_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('unit_price_minor');
            $table->string('status')->default('active');
            $table->foreignId('created_by')->nullable()->constrained('organisation_users')->nullOnDelete();
            $table->timestamps();

            $table->index(['organisation_id', 'status']);
        });

        DB::statement("ALTER TABLE billable_items ADD CONSTRAINT billable_items_status_check CHECK (status IN ('active','archived'))");

        Schema::create('invoices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->restrictOnDelete();
            $table->foreignId('club_id')->constrained('clubs')->restrictOnDelete();

            // Human-facing number plus the integer it was derived from. The
            // integer is what is locked and incremented; the string is what is
            // printed and quoted. Both unique per organisation.
            $table->unsignedInteger('sequence');
            $table->string('number', 32);

            $table->string('status')->default('issued');
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->string('currency_code', 3);

            // Snapshot totals. Lines can never be edited after issue, so these
            // are stable, and reports sum them without joining lines.
            $table->unsignedBigInteger('total_minor');
            $table->unsignedBigInteger('paid_minor')->default(0);

            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('organisation_users')->restrictOnDelete();

            $table->foreignId('voided_by')->nullable()->constrained('organisation_users')->nullOnDelete();
            $table->timestamp('voided_at')->nullable();
            $table->string('void_reason')->nullable();

            $table->timestamps();

            $table->unique(['organisation_id', 'sequence']);
            $table->unique(['organisation_id', 'number']);
            $table->index(['organisation_id', 'status']);
            $table->index(['organisation_id', 'member_id']);
            $table->index(['organisation_id', 'due_date']);
        });

        DB::statement("ALTER TABLE invoices ADD CONSTRAINT invoices_status_check CHECK (status IN ('issued','partially_paid','paid','void'))");

        Schema::create('invoice_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();

            // Kept as a reference to where the price came from, but the line
            // carries its own description and price: a catalogue item renamed
            // or repriced later must not rewrite invoices already issued.
            $table->foreignId('billable_item_id')->nullable()->constrained('billable_items')->nullOnDelete();

            $table->string('description');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('unit_price_minor');
            $table->unsignedBigInteger('line_total_minor');
            $table->unsignedSmallInteger('position')->default(0);
        });

        Schema::table('fee_payments', function (Blueprint $table): void {
            // Restricted, not nulled: a confirmed payment against an invoice is
            // part of that invoice's history, and an invoice with payments is
            // voided rather than deleted anyway.
            $table->foreignId('invoice_id')->nullable()->after('subscription_id')
                ->constrained('invoices')->restrictOnDelete();
        });

        // The notification tables are guarded by CHECK constraints listing the
        // known types, so a new one is a schema change as well as an enum one.
        DB::statement('ALTER TABLE whatsapp_action_notifications DROP CONSTRAINT IF EXISTS whatsapp_notifications_entity_type_check');
        DB::statement("ALTER TABLE whatsapp_action_notifications ADD CONSTRAINT whatsapp_notifications_entity_type_check CHECK (entity_type IN ('member','user','fee_payment','subscription','attendance','invoice'))");

        DB::statement('ALTER TABLE whatsapp_action_notifications DROP CONSTRAINT IF EXISTS whatsapp_notifications_action_type_check');
        DB::statement("ALTER TABLE whatsapp_action_notifications ADD CONSTRAINT whatsapp_notifications_action_type_check CHECK (action_type IN (
            'member_created','member_profile_updated','member_club_transferred','member_plan_created',
            'member_plan_renewed','member_plan_paused','member_plan_cancelled','member_status_changed',
            'member_attendance_marked','fee_payment_confirmed','user_invited','user_profile_updated',
            'user_club_assignment_changed','user_permissions_changed','user_status_changed','user_attendance_marked',
            'password_reset_link','invoice_issued'
        ))");
    }

    public function down(): void
    {
        Schema::table('fee_payments', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('invoice_id');
        });

        Schema::dropIfExists('invoice_lines');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('billable_items');
    }
};
