<?php

declare(strict_types=1);

namespace App\Support\WhatsApp;

use App\Enums\NotificationActionType;
use App\Models\Organisation;

/**
 * Renders the outbound message for each admin action using the organisation's
 * own terminology, currency, and timezone (MEP.md Sections 5.11.1 and 6.8).
 *
 * Two rules are structural rather than stylistic:
 *   - templates are assembled from named placeholders, never by concatenating
 *     raw user input into a URL-bound string;
 *   - nothing sensitive (passwords, invitation tokens, bank details, internal
 *     audit data) may appear in any template here.
 *
 * The rendered text is stored on the notification record as
 * `message_snapshot` alongside VERSION, so a later template change never
 * rewrites the history of what was actually shown to an admin.
 */
final class MessageTemplate
{
    public const VERSION = 'v1';

    /**
     * @param  array<string, string|null>  $context
     */
    public static function render(NotificationActionType $type, Organisation $organisation, array $context): string
    {
        $lines = match ($type) {
            NotificationActionType::MemberCreated => [
                'Hi {memberName}, your profile has been created with {organisationName}.',
                'Club: {clubName}',
                '{memberLabel} ID: {memberId}',
                'Please contact us if any information needs to be corrected.',
            ],
            NotificationActionType::MemberProfileUpdated,
            NotificationActionType::MemberStatusChanged => [
                'Hi {memberName}, your information with {organisationName} was updated successfully.',
                'Updated item: {changedItem}',
                'Club: {clubName}',
            ],
            NotificationActionType::MemberClubTransferred => [
                'Hi {memberName}, your club with {organisationName} has been changed.',
                'New club: {clubName}',
                'Effective date: {effectiveDate}',
            ],
            NotificationActionType::MemberPlanCreated,
            NotificationActionType::MemberPlanRenewed,
            NotificationActionType::MemberPlanPaused,
            NotificationActionType::MemberPlanCancelled => [
                'Hi {memberName}, your {memberLabel} plan with {organisationName} is {planAction}.',
                'Plan: {planName}',
                'Club: {clubName}',
                'Valid from: {startDate}',
                'Valid until: {endDate}',
            ],
            NotificationActionType::FeePaymentConfirmed => [
                'Hi {memberName}, your {memberLabel} fee payment of {amount} has been confirmed by {organisationName}.',
                'Plan: {planName}',
                'Club: {clubName}',
                'Payment date: {paymentDate}',
                'Valid until: {endDate}',
                'Receipt/reference: {reference}',
                '',
                'Thank you.',
            ],
            NotificationActionType::MemberAttendanceMarked,
            NotificationActionType::UserAttendanceMarked => [
                'Hi {memberName}, your attendance at {organisationName} was recorded.',
                'Club: {clubName}',
                'Date: {effectiveDate}',
                'Status: {changedItem}',
            ],
            // Deliberately carries no password and no raw invitation token.
            NotificationActionType::UserInvited => [
                'Hi {memberName}, you have been invited to {organisationName} as {roleLabel}.',
                'Assigned clubs: {clubName}',
                'Sign in here: {signInUrl}',
            ],
            NotificationActionType::UserProfileUpdated,
            NotificationActionType::UserClubAssignmentChanged,
            NotificationActionType::UserPermissionsChanged => [
                'Hi {memberName}, your access to {organisationName} was updated.',
                'Assigned clubs: {clubName}',
                'Please sign in to view your current access.',
            ],
            NotificationActionType::UserStatusChanged => [
                'Hi {memberName}, your account status with {organisationName} is now {changedItem}.',
                'Please contact {supportContact} if you need assistance.',
            ],
        };

        $replacements = [
            '{organisationName}' => $organisation->name,
            '{memberLabel}' => $organisation->term('member_singular'),
            '{clubLabel}' => $organisation->term('club_singular'),
            '{supportContact}' => $organisation->contact_phone ?: ($organisation->contact_email ?: $organisation->name),
        ];

        foreach ($context as $key => $value) {
            $replacements['{'.$key.'}'] = $value ?? '—';
        }

        $rendered = strtr(implode("\n", $lines), $replacements);

        // Drop any line whose only value was never supplied, so recipients
        // never see a half-filled template.
        $kept = array_filter(
            explode("\n", $rendered),
            static fn (string $line): bool => ! str_ends_with(trim($line), ': —'),
        );

        return trim(implode("\n", $kept));
    }
}
