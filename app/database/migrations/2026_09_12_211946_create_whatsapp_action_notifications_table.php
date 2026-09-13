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
        Schema::create('whatsapp_action_notifications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->string('recipient_type');
            $table->unsignedBigInteger('recipient_id');
            $table->string('recipient_name');
            $table->string('recipient_phone')->nullable();
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->string('action_type');
            $table->string('message_template_version')->nullable();
            $table->text('message_snapshot');
            $table->string('status')->default('ready');
            $table->foreignId('created_by')->constrained('organisation_users')->restrictOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('opened_by')->nullable()->constrained('organisation_users')->nullOnDelete();
            $table->timestamp('opened_at')->nullable();
            $table->string('failure_reason')->nullable();

            // Idempotency key: retries of the same business operation must hit
            // this constraint instead of creating a duplicate notification.
            $table->string('operation_id');
            $table->unique(['entity_id', 'action_type', 'operation_id'], 'whatsapp_notifications_idempotency_unique');

            $table->index(['organisation_id', 'recipient_type', 'recipient_id']);
            $table->index(['organisation_id', 'status']);
        });

        DB::statement("ALTER TABLE whatsapp_action_notifications ADD CONSTRAINT whatsapp_notifications_recipient_type_check CHECK (recipient_type IN ('member','user'))");
        DB::statement("ALTER TABLE whatsapp_action_notifications ADD CONSTRAINT whatsapp_notifications_entity_type_check CHECK (entity_type IN ('member','user','fee_payment','subscription','attendance'))");
        DB::statement("ALTER TABLE whatsapp_action_notifications ADD CONSTRAINT whatsapp_notifications_status_check CHECK (status IN ('ready','opened','skipped','unavailable','failed'))");
        DB::statement("ALTER TABLE whatsapp_action_notifications ADD CONSTRAINT whatsapp_notifications_action_type_check CHECK (action_type IN (
            'member_created','member_profile_updated','member_club_transferred','member_plan_created',
            'member_plan_renewed','member_plan_paused','member_plan_cancelled','member_status_changed',
            'member_attendance_marked','fee_payment_confirmed','user_invited','user_profile_updated',
            'user_club_assignment_changed','user_permissions_changed','user_status_changed','user_attendance_marked'
        ))");
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_action_notifications');
    }
};
