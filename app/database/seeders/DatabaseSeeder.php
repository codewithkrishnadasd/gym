<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ClubAssignmentStatus;
use App\Enums\ConfirmationStatus;
use App\Enums\DomainStatus;
use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Enums\PaymentMethod;
use App\Models\Club;
use App\Models\ClubUserAssignment;
use App\Models\Domain;
use App\Models\FeePayment;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\PlatformAdmin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Demonstrates the platform's tenant model end to end:
 *
 * - A platform admin ("root user") who creates organisations.
 * - Two organisations on two different domains.
 * - One `users` row (jordan@example.com) with an OrganisationUser
 *   membership — and therefore an admin role — in *both* organisations,
 *   proving one email/password can sign in to multiple organisations on
 *   different domains. See MEP.md Section 5.3.
 * - A staff user who only belongs to one organisation and one club there.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $root = PlatformAdmin::factory()->create([
            'name' => 'Root Operator',
            'email' => 'root@example.com',
            'password' => Hash::make('password'),
        ]);

        $sharedUser = User::factory()->create([
            'name' => 'Jordan Lee',
            'email' => 'jordan@example.com',
            'password' => Hash::make('password'),
        ]);

        $staffUser = User::factory()->create([
            'name' => 'Sam Rivera',
            'email' => 'sam@example.com',
            'password' => Hash::make('password'),
        ]);

        $fitZone = Organisation::factory()->create([
            'name' => 'FitZone',
            'slug' => 'fitzone',
            'created_by' => $root->id,
        ]);

        Domain::factory()->create([
            'organisation_id' => $fitZone->id,
            'hostname' => 'fitzone.test',
            'status' => DomainStatus::Active,
            'is_primary' => true,
        ]);

        $powerHouse = Organisation::factory()->create([
            'name' => 'PowerHouse Gym',
            'slug' => 'powerhouse-gym',
            'created_by' => $root->id,
        ]);

        Domain::factory()->create([
            'organisation_id' => $powerHouse->id,
            'hostname' => 'powerhouse.test',
            'status' => DomainStatus::Active,
            'is_primary' => true,
        ]);

        // The same person, same login, admin of both organisations.
        $fitZoneAdmin = OrganisationUser::factory()->admin()->create([
            'organisation_id' => $fitZone->id,
            'user_id' => $sharedUser->id,
            'status' => MembershipStatus::Active,
        ]);

        OrganisationUser::factory()->admin()->create([
            'organisation_id' => $powerHouse->id,
            'user_id' => $sharedUser->id,
            'status' => MembershipStatus::Active,
        ]);

        // A club, a limited-permission staff member, and one club assignment
        // inside FitZone only.
        $downtownClub = Club::factory()->create([
            'organisation_id' => $fitZone->id,
            'name' => 'Downtown Club',
            'code' => 'DTN',
            'created_by' => $fitZoneAdmin->id,
        ]);

        $fitZoneStaff = OrganisationUser::factory()->create([
            'organisation_id' => $fitZone->id,
            'user_id' => $staffUser->id,
            'role' => MembershipRole::User,
            'status' => MembershipStatus::Active,
            'permissions' => [
                'members.view' => true,
                'members.create' => true,
                'members.edit' => true,
                'attendance.member.mark' => true,
                'fees.collect' => true,
                'fees.view_own' => true,
                'clubs.view_assigned' => true,
            ],
            'created_by' => $fitZoneAdmin->id,
        ]);

        ClubUserAssignment::factory()->create([
            'organisation_id' => $fitZone->id,
            'club_id' => $downtownClub->id,
            'organisation_user_id' => $fitZoneStaff->id,
            'status' => ClubAssignmentStatus::Active,
        ]);

        $monthlyPlan = Plan::factory()->create([
            'organisation_id' => $fitZone->id,
            'name' => 'Monthly Plan',
            'price_minor' => 4900,
            'duration_days' => 30,
        ]);

        $member = Member::factory()->create([
            'organisation_id' => $fitZone->id,
            'primary_club_id' => $downtownClub->id,
            'name' => 'Alex Morgan',
            'created_by' => $fitZoneStaff->id,
        ]);

        $subscription = MemberSubscription::factory()->create([
            'organisation_id' => $fitZone->id,
            'member_id' => $member->id,
            'club_id' => $downtownClub->id,
            'plan_id' => $monthlyPlan->id,
            'amount_due_minor' => 4900,
            'amount_paid_minor' => 4900,
        ]);

        FeePayment::factory()->create([
            'organisation_id' => $fitZone->id,
            'club_id' => $downtownClub->id,
            'member_id' => $member->id,
            'subscription_id' => $subscription->id,
            'payer_name' => $member->name,
            'amount_minor' => 4900,
            'currency_code' => 'USD',
            'payment_method' => PaymentMethod::Cash,
            'payment_date' => now()->toDateString(),
            'collected_by' => $fitZoneStaff->id,
            'confirmation_status' => ConfirmationStatus::Confirmed,
            'confirmed_by' => $fitZoneAdmin->id,
            'confirmed_at' => now(),
        ]);
    }
}
