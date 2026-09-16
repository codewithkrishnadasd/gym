<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * An organisation's own message wordings, sent by hand from a member's
     * (or staff member's) page. Separate from `message_templates`, which
     * override the built-in wording of the messages actions compose.
     */
    public function up(): void
    {
        Schema::create('custom_message_templates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained()->cascadeOnDelete();
            $table->string('audience', 20); // member | user
            $table->string('name', 80);
            $table->text('body');
            $table->foreignId('created_by')->nullable()->constrained('organisation_users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['organisation_id', 'audience', 'name']);
        });

        // The notifications table lists every known action type, so the two
        // hand-sent kinds are a schema change as well as an enum one.
        DB::statement('ALTER TABLE whatsapp_action_notifications DROP CONSTRAINT IF EXISTS whatsapp_notifications_action_type_check');
        DB::statement("ALTER TABLE whatsapp_action_notifications ADD CONSTRAINT whatsapp_notifications_action_type_check CHECK (action_type IN (
            'member_created','member_profile_updated','member_club_transferred','member_plan_created',
            'member_plan_renewed','member_plan_paused','member_plan_cancelled','member_status_changed',
            'member_attendance_marked','fee_payment_confirmed','user_invited','user_profile_updated',
            'user_club_assignment_changed','user_permissions_changed','user_status_changed','user_attendance_marked',
            'password_reset_link','invoice_issued','member_plan_expired','custom_message'
        ))");
    }

    public function down(): void
    {
        DB::statement("DELETE FROM whatsapp_action_notifications WHERE action_type IN ('member_plan_expired','custom_message')");
        DB::statement('ALTER TABLE whatsapp_action_notifications DROP CONSTRAINT IF EXISTS whatsapp_notifications_action_type_check');
        DB::statement("ALTER TABLE whatsapp_action_notifications ADD CONSTRAINT whatsapp_notifications_action_type_check CHECK (action_type IN (
            'member_created','member_profile_updated','member_club_transferred','member_plan_created',
            'member_plan_renewed','member_plan_paused','member_plan_cancelled','member_status_changed',
            'member_attendance_marked','fee_payment_confirmed','user_invited','user_profile_updated',
            'user_club_assignment_changed','user_permissions_changed','user_status_changed','user_attendance_marked',
            'password_reset_link','invoice_issued'
        ))");

        Schema::dropIfExists('custom_message_templates');
    }
};
