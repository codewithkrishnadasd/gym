<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds `password_reset_link` to the action types a notification may carry.
 *
 * The column is guarded by a CHECK constraint rather than left free text, so a
 * new NotificationActionType case is a schema change as well as an enum one —
 * which is the point: the database refuses a value the application has not
 * declared, instead of silently storing a typo that only surfaces when a
 * filter quietly returns nothing.
 */
return new class extends Migration
{
    /** @var list<string> */
    private const TYPES = [
        'member_created', 'member_profile_updated', 'member_club_transferred', 'member_plan_created',
        'member_plan_renewed', 'member_plan_paused', 'member_plan_cancelled', 'member_status_changed',
        'member_attendance_marked', 'fee_payment_confirmed', 'user_invited', 'user_profile_updated',
        'user_club_assignment_changed', 'user_permissions_changed', 'user_status_changed', 'user_attendance_marked',
        'password_reset_link',
    ];

    public function up(): void
    {
        $this->replaceConstraint(self::TYPES);
    }

    public function down(): void
    {
        $this->replaceConstraint(array_values(array_diff(self::TYPES, ['password_reset_link'])));
    }

    /**
     * @param  list<string>  $types
     */
    private function replaceConstraint(array $types): void
    {
        $values = collect($types)->map(static fn (string $type): string => "'".$type."'")->implode(',');

        DB::statement('ALTER TABLE whatsapp_action_notifications DROP CONSTRAINT IF EXISTS whatsapp_notifications_action_type_check');
        DB::statement("ALTER TABLE whatsapp_action_notifications ADD CONSTRAINT whatsapp_notifications_action_type_check CHECK (action_type IN ({$values}))");
    }
};
