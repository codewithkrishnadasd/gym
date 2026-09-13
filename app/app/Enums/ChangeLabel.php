<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * The allowlist of display labels a notification may name as "what changed".
 *
 * MEP.md Section 6.8 forbids putting raw field names, permission codes,
 * internal reasons, or before/after values into an outbound message — only a
 * label from this fixed set ever reaches a recipient.
 */
enum ChangeLabel: string
{
    case ContactDetails = 'Contact details';
    case Address = 'Address';
    case EmergencyContact = 'Emergency contact';
    case PersonalDetails = 'Personal details';
    case MembershipStatus = 'Membership status';
    case ClubAssignment = 'Club assignment';
    case AccessPermissions = 'Access permissions';
    case ProfileInformation = 'Profile information';

    public function label(): string
    {
        return $this->value;
    }

    /**
     * Maps changed database columns onto a single safe label. Anything not
     * explicitly mapped falls back to the most generic label rather than
     * leaking the column name.
     *
     * @param  array<int, string>  $changedColumns
     */
    public static function forColumns(array $changedColumns): self
    {
        $map = [
            'phone' => self::ContactDetails,
            'address' => self::Address,
            'emergency_contact' => self::EmergencyContact,
            'name' => self::PersonalDetails,
            'date_of_birth' => self::PersonalDetails,
            'gender' => self::PersonalDetails,
            'status' => self::MembershipStatus,
            'primary_club_id' => self::ClubAssignment,
            'permissions' => self::AccessPermissions,
        ];

        foreach ($changedColumns as $column) {
            if (isset($map[$column])) {
                return $map[$column];
            }
        }

        return self::ProfileInformation;
    }
}
