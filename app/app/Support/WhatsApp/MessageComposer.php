<?php

declare(strict_types=1);

namespace App\Support\WhatsApp;

use App\Enums\NotificationActionType;
use App\Models\MessageTemplate;
use App\Models\Organisation;

/**
 * Renders the outbound message for each action using the organisation's own
 * terminology, currency, and timezone (MEP.md 5.11.1, 6.8).
 *
 * The wording comes from the organisation's `message_templates` row when it
 * has one, and from DEFAULTS otherwise. Either way the same rules hold:
 *
 *   - templates are assembled from named placeholders, never by concatenating
 *     raw user input into a URL-bound string;
 *   - only the placeholders declared in `variablesFor()` are substituted, so
 *     an operator cannot invent one that reaches internal data;
 *   - nothing sensitive (passwords, invitation tokens, bank details, internal
 *     audit data) is ever exposed as a variable.
 *
 * The rendered text is stored on the notification as `message_snapshot`
 * alongside the template version, so later edits never rewrite the history of
 * what was actually sent.
 */
final class MessageComposer
{
    public const VERSION = 'v1';

    /**
     * Variables offered to the editor for each action, as name => description.
     * This list is also the substitution allowlist.
     *
     * @return array<string, string>
     */
    public static function variablesFor(NotificationActionType $type): array
    {
        $shared = [
            'memberName' => 'The recipient’s name',
            'organisationName' => 'Your organisation’s name',
            'memberLabel' => 'Your word for a member, e.g. “Student”',
            'clubLabel' => 'Your word for a club, e.g. “Branch”',
            'supportContact' => 'Your organisation’s contact number',
        ];

        $specific = match ($type) {
            NotificationActionType::MemberCreated => [
                'clubName' => 'The club they belong to',
                'memberId' => 'Their reference, e.g. MEM-42',
            ],
            NotificationActionType::MemberProfileUpdated,
            NotificationActionType::MemberStatusChanged => [
                'changedItem' => 'What changed, e.g. “Contact details”',
                'clubName' => 'The club they belong to',
            ],
            NotificationActionType::MemberClubTransferred => [
                'clubName' => 'The club they moved to',
                'effectiveDate' => 'The date the move takes effect',
            ],
            NotificationActionType::MemberPlanCreated,
            NotificationActionType::MemberPlanRenewed,
            NotificationActionType::MemberPlanPaused,
            NotificationActionType::MemberPlanCancelled => [
                'planAction' => 'What happened, e.g. “renewed”',
                'planName' => 'The plan’s name',
                'clubName' => 'The club they belong to',
                'startDate' => 'First day of the term',
                'endDate' => 'Last day of the term',
            ],
            NotificationActionType::FeePaymentConfirmed => [
                'amount' => 'The amount received, formatted in your currency',
                'planName' => 'The plan the payment was applied to',
                'purpose' => 'What the payment settled: the plan, invoice number, or "Admission fee"',
                'discount' => 'The discount given with this payment, if any',
                'clubName' => 'The club the payment belongs to',
                'paymentDate' => 'The date of payment',
                'endDate' => 'The date the plan is valid until',
                'reference' => 'The receipt or transaction reference',
                'invoiceNumber' => 'The invoice this payment was made against, if any',
                'balanceDue' => 'What is still owed on that invoice after this payment',
                'receiptLink' => 'A link where they can view and download the receipt',
                'invoiceLink' => 'A link to the invoice this payment was made against, if any',
            ],
            NotificationActionType::InvoiceIssued => [
                'invoiceNumber' => 'The invoice number, e.g. INV-2026-0042',
                'amount' => 'The invoice total, formatted in your currency',
                'items' => 'The billed items, comma-separated',
                'dueDate' => 'When payment is due',
                'clubName' => 'The club the invoice belongs to',
                'invoiceLink' => 'A link where they can view and download the invoice',
            ],
            NotificationActionType::MemberAttendanceMarked,
            NotificationActionType::UserAttendanceMarked => [
                'clubName' => 'The club attended',
                'effectiveDate' => 'The date recorded',
                'changedItem' => 'The status recorded, e.g. “Present”',
            ],
            NotificationActionType::UserInvited => [
                'roleLabel' => 'The role they were given',
                'clubName' => 'The clubs they are assigned to',
                'signInUrl' => 'The sign-in address for your organisation',
            ],
            NotificationActionType::UserProfileUpdated,
            NotificationActionType::UserClubAssignmentChanged,
            NotificationActionType::UserPermissionsChanged => [
                'clubName' => 'The clubs they are assigned to',
            ],
            NotificationActionType::UserStatusChanged => [
                'changedItem' => 'The new account status',
            ],
            // `resetUrl` is a one-time token, not a password: it expires on its
            // own and stops working once used, which is why it is allowed in a
            // message at all.
            NotificationActionType::PasswordResetLink => [
                'resetUrl' => 'The single-use link for setting their password',
                'expiresIn' => 'How long the link lasts, e.g. “1 hour”',
            ],
        };

        return [...$shared, ...$specific];
    }

    /**
     * The built-in wording, used when an organisation has no override.
     */
    public static function defaultBody(NotificationActionType $type): string
    {
        return implode("\n", match ($type) {
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
                'View your receipt: {receiptLink}',
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
            NotificationActionType::InvoiceIssued => [
                'Hi {memberName}, {organisationName} has issued invoice {invoiceNumber} for {amount}.',
                'Items: {items}',
                'Due: {dueDate}',
                'Club: {clubName}',
                'View and download: {invoiceLink}',
                '',
                'Please pay at the counter or contact {supportContact} with any questions.',
            ],
            NotificationActionType::PasswordResetLink => [
                'Hi {memberName}, here is your link to set a new password for {organisationName}.',
                '',
                '{resetUrl}',
                '',
                'It works once and expires in {expiresIn}. If you did not ask for it, ignore this message and tell {supportContact}.',
            ],
        });
    }

    /**
     * The wording currently in force for an organisation.
     */
    public static function bodyFor(Organisation $organisation, NotificationActionType $type): string
    {
        return self::override($organisation, $type)->body ?? self::defaultBody($type);
    }

    public static function versionFor(Organisation $organisation, NotificationActionType $type): string
    {
        return self::override($organisation, $type)?->versionLabel() ?? self::VERSION;
    }

    /**
     * @param  array<string, string|null>  $context
     */
    public static function render(NotificationActionType $type, Organisation $organisation, array $context): string
    {
        $values = [
            'organisationName' => $organisation->name,
            'memberLabel' => $organisation->term('member_singular'),
            'clubLabel' => $organisation->term('club_singular'),
            'supportContact' => $organisation->contact_phone ?: $organisation->name,
            ...$context,
        ];

        // Only declared variables are substituted; anything else in the body
        // is left alone rather than resolved against arbitrary data.
        $replacements = [];

        foreach (array_keys(self::variablesFor($type)) as $name) {
            $replacements['{'.$name.'}'] = $values[$name] ?? '—';
        }

        $rendered = strtr(self::bodyFor($organisation, $type), $replacements);

        // Drop any line whose only value was never supplied, so recipients
        // never see a half-filled template.
        $kept = array_filter(
            explode("\n", $rendered),
            static fn (string $line): bool => ! str_ends_with(trim($line), ': —'),
        );

        return trim(implode("\n", $kept));
    }

    /**
     * A preview built from believable stand-in values, for the editor.
     */
    public static function preview(Organisation $organisation, NotificationActionType $type, ?string $body = null): string
    {
        $samples = [
            'memberName' => 'Aditi Sharma',
            'clubName' => 'Downtown Club',
            'memberId' => $organisation->reference('member', 42),
            'changedItem' => 'Contact details',
            'effectiveDate' => now($organisation->timezone)->format('d M Y'),
            'planAction' => 'renewed',
            'planName' => 'Quarterly',
            'startDate' => now($organisation->timezone)->format('d M Y'),
            'endDate' => now($organisation->timezone)->addDays(90)->format('d M Y'),
            'amount' => $organisation->money(400000),
            'paymentDate' => now($organisation->timezone)->format('d M Y'),
            'reference' => 'REF001234',
            'purpose' => 'Quarterly',
            'discount' => $organisation->money(50000),
            'invoiceNumber' => $organisation->idPrefix('invoice').'-'.now($organisation->timezone)->format('Y').'-0042',
            'balanceDue' => $organisation->money(150000),
            'receiptLink' => 'https://'.($organisation->primaryHostname() ?? 'your-gym.example.com').'/r/…',
            'invoiceLink' => 'https://'.($organisation->primaryHostname() ?? 'your-gym.example.com').'/i/…',
            'items' => '4 × Personal training, Locker rental',
            'dueDate' => now($organisation->timezone)->addDays(7)->format('d M Y'),
            'roleLabel' => $organisation->term('user_singular'),
            'signInUrl' => 'https://'.($organisation->domains()->where('is_primary', true)->value('hostname') ?? 'your-gym.example.com'),
        ];

        if ($body === null) {
            return self::render($type, $organisation, $samples);
        }

        // Render an unsaved draft without touching the stored template.
        $values = [
            'organisationName' => $organisation->name,
            'memberLabel' => $organisation->term('member_singular'),
            'clubLabel' => $organisation->term('club_singular'),
            'supportContact' => $organisation->contact_phone ?: $organisation->name,
            ...$samples,
        ];

        $replacements = [];

        foreach (array_keys(self::variablesFor($type)) as $name) {
            $replacements['{'.$name.'}'] = $values[$name] ?? '—';
        }

        return trim(strtr($body, $replacements));
    }

    private static function override(Organisation $organisation, NotificationActionType $type): ?MessageTemplate
    {
        return MessageTemplate::query()
            ->withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('action_type', $type)
            ->first();
    }
}
