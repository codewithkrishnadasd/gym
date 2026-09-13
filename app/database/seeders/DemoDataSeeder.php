<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AttendanceAction;
use App\Enums\AttendanceSource;
use App\Enums\AttendanceSubjectType;
use App\Enums\ClubAssignmentStatus;
use App\Enums\ConfirmationStatus;
use App\Enums\ExpenseStatus;
use App\Enums\FinancialAccountStatus;
use App\Enums\FinancialAccountType;
use App\Enums\MembershipStatus;
use App\Enums\MemberStatus;
use App\Enums\PaymentMethod;
use App\Enums\PlanStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Attendance;
use App\Models\Club;
use App\Models\ClubUserAssignment;
use App\Models\Expense;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Fills one organisation with three months of believable operating history so
 * the dashboards, trend charts, and reports can be reviewed with real shapes
 * rather than empty states.
 *
 * Safe to re-run: everything is keyed on stable names/codes and updated in
 * place. Intended for local review only — never run against production.
 *
 *     php artisan db:seed --class=DemoDataSeeder -- --slug=fitzone
 */
class DemoDataSeeder extends Seeder
{
    private const MEMBER_NAMES = [
        'Aditi Sharma', 'Rahul Verma', 'Priya Nair', 'Arjun Mehta', 'Sneha Iyer',
        'Vikram Singh', 'Ananya Rao', 'Karthik Menon', 'Divya Kapoor', 'Rohan Das',
        'Meera Pillai', 'Siddharth Jain', 'Nisha Reddy', 'Aman Gupta', 'Kavya Krishnan',
        'Farhan Qureshi', 'Ishita Bose', 'Nikhil Joshi', 'Tara Malhotra', 'Yash Agarwal',
        'Ritu Chawla', 'Manav Bhatt', 'Pooja Desai', 'Sanjay Kulkarni', 'Leela Varma',
        'Imran Sheikh', 'Deepa Raman', 'Aryan Kohli', 'Shreya Ghosh', 'Vivek Anand',
    ];

    private const EXPENSE_CATEGORIES = [
        'Rent' => [45000, 45000],
        'Utilities' => [6000, 14000],
        'Salaries' => [30000, 60000],
        'Equipment' => [3000, 25000],
        'Maintenance' => [1500, 8000],
        'Marketing' => [2000, 12000],
        'Supplies' => [800, 4500],
    ];

    public function run(): void
    {
        $organisation = Organisation::query()->where('slug', 'fitzone')->first()
            ?? Organisation::query()->first();

        if (! $organisation) {
            $this->command?->error('No organisation found. Run the base DatabaseSeeder first.');

            return;
        }

        // Tenant-scoped models need the resolved organisation, which does not
        // exist in a console context.
        app()->instance('tenant', $organisation);

        $organisation->update(['currency_code' => 'INR', 'timezone' => 'Asia/Kolkata', 'default_country_code' => 'IN']);

        $admin = OrganisationUser::query()->where('role', 'admin')->firstOrFail();

        $clubs = $this->clubs($organisation, $admin);
        $staff = $this->staff($clubs);
        $plans = $this->plans();
        $accounts = $this->accounts();
        $members = $this->members($clubs, $admin);

        $this->subscriptionsAndPayments($members, $plans, $accounts, $staff, $admin);
        $this->attendance($members, $staff);
        $this->expenses($clubs, $accounts, $admin);

        $this->command?->info('Demo data seeded for '.$organisation->name.'.');
    }

    /**
     * @return array<int, Club>
     */
    private function clubs(Organisation $organisation, OrganisationUser $admin): array
    {
        $definitions = [
            ['Downtown Club', 'DTN', '12 Residency Road, Bengaluru'],
            ['Uptown Studio', 'UPT', '48 Indiranagar 100ft Road, Bengaluru'],
            ['Riverside Gym', 'RIV', '7 Sarjapur Main Road, Bengaluru'],
        ];

        $clubs = [];

        foreach ($definitions as [$name, $code, $address]) {
            $clubs[] = Club::query()->updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'status' => 'active',
                    'phone' => '080'.random_int(40000000, 49999999),
                    'email' => strtolower($code).'@fitzone.test',
                    'address' => ['line1' => $address],
                    'timezone' => $organisation->timezone,
                    'opening_hours' => collect(['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'])
                        ->mapWithKeys(fn (string $day): array => [$day => [
                            'closed' => $day === 'sun',
                            'open' => '06:00',
                            'close' => $day === 'sat' ? '20:00' : '22:00',
                        ]])->all(),
                    'created_by' => $admin->id,
                ],
            );
        }

        return $clubs;
    }

    /**
     * @param  array<int, Club>  $clubs
     * @return array<int, OrganisationUser>
     */
    private function staff(array $clubs): array
    {
        $staff = OrganisationUser::query()
            ->where('status', MembershipStatus::Active)
            ->get()
            ->all();

        // Give every membership an active assignment so rosters and
        // collection attribution look realistic.
        foreach ($staff as $index => $person) {
            $club = $clubs[$index % count($clubs)];

            ClubUserAssignment::query()->updateOrCreate(
                ['club_id' => $club->id, 'organisation_user_id' => $person->id],
                ['status' => ClubAssignmentStatus::Active, 'assigned_at' => now()->subMonths(4)],
            );
        }

        return $staff;
    }

    /**
     * @return array<int, Plan>
     */
    private function plans(): array
    {
        $definitions = [
            ['Monthly', 'Rolling monthly membership.', 150000, 30],
            ['Quarterly', 'Three months, billed up front.', 400000, 90],
            ['Half Yearly', 'Six months with one free personal training session.', 750000, 180],
            ['Annual', 'Best value — twelve months.', 1300000, 365],
        ];

        $plans = [];

        foreach ($definitions as [$name, $description, $price, $days]) {
            $plans[] = Plan::query()->updateOrCreate(
                ['name' => $name],
                [
                    'description' => $description,
                    'price_minor' => $price,
                    'currency_code' => 'INR',
                    'duration_days' => $days,
                    'status' => PlanStatus::Active,
                ],
            );
        }

        return $plans;
    }

    /**
     * @return array<int, FinancialAccount>
     */
    private function accounts(): array
    {
        $definitions = [
            ['Main current account', FinancialAccountType::Bank, 'HDFC Bank', '4821', null, null],
            ['Front desk UPI', FinancialAccountType::Upi, null, null, 'fitzone@hdfcbank', 'upi://pay?pa=fitzone@hdfcbank&pn=FitZone'],
            ['Cash drawer', FinancialAccountType::Cash, null, null, null, null],
        ];

        $accounts = [];

        foreach ($definitions as [$name, $type, $bank, $last4, $upi, $qr]) {
            $accounts[] = FinancialAccount::query()->updateOrCreate(
                ['name' => $name],
                [
                    'account_type' => $type,
                    'bank_name' => $bank,
                    'account_number_last4' => $last4,
                    'upi_id' => $upi,
                    'qr_payload' => $qr,
                    'status' => FinancialAccountStatus::Active,
                ],
            );
        }

        return $accounts;
    }

    /**
     * @param  array<int, Club>  $clubs
     * @return array<int, Member>
     */
    private function members(array $clubs, OrganisationUser $admin): array
    {
        $members = [];

        foreach (self::MEMBER_NAMES as $index => $name) {
            $club = $clubs[$index % count($clubs)];

            $status = match (true) {
                $index % 13 === 0 => MemberStatus::Paused,
                $index % 17 === 0 => MemberStatus::Inactive,
                default => MemberStatus::Active,
            };

            $members[] = Member::query()->updateOrCreate(
                ['name' => $name],
                [
                    'primary_club_id' => $club->id,
                    'phone' => '9'.str_pad((string) (800000000 + $index * 137), 9, '0', STR_PAD_LEFT),
                    'date_of_birth' => Carbon::today()->subYears(20 + ($index % 25))->subDays($index * 7),
                    'gender' => $index % 2 === 0 ? 'Female' : 'Male',
                    'joined_at' => Carbon::today()->subDays(random_int(5, 110)),
                    'status' => $status,
                    'created_by' => $admin->id,
                ],
            );
        }

        return $members;
    }

    /**
     * @param  array<int, Member>  $members
     * @param  array<int, Plan>  $plans
     * @param  array<int, FinancialAccount>  $accounts
     * @param  array<int, OrganisationUser>  $staff
     */
    private function subscriptionsAndPayments(array $members, array $plans, array $accounts, array $staff, OrganisationUser $admin): void
    {
        $methods = PaymentMethod::cases();

        foreach ($members as $index => $member) {
            if ($member->status === MemberStatus::Inactive) {
                continue;
            }

            $plan = $plans[$index % count($plans)];
            $start = Carbon::parse($member->joined_at);

            $subscription = MemberSubscription::query()->updateOrCreate(
                ['member_id' => $member->id, 'plan_id' => $plan->id, 'start_date' => $start->toDateString()],
                [
                    'club_id' => $member->primary_club_id,
                    'end_date' => $start->copy()->addDays($plan->duration_days - 1)->toDateString(),
                    'amount_due_minor' => $plan->price_minor,
                    'amount_paid_minor' => 0,
                    'status' => $member->status === MemberStatus::Paused
                        ? SubscriptionStatus::Paused
                        : SubscriptionStatus::Active,
                ],
            );

            // Roughly one in six members still owes something, so the
            // outstanding-fees and follow-up panels have real content.
            $paysInFull = $index % 6 !== 0;
            $amount = $paysInFull ? $plan->price_minor : (int) round($plan->price_minor * 0.4);

            $collector = $staff[$index % count($staff)];
            $account = $accounts[$index % count($accounts)];

            // Most payments are confirmed; a few stay pending so the
            // confirmation queue is not empty.
            $pending = $index % 9 === 0;

            $payment = FeePayment::query()->updateOrCreate(
                ['member_id' => $member->id, 'subscription_id' => $subscription->id, 'payment_date' => $start->toDateString()],
                [
                    'club_id' => $member->primary_club_id,
                    'payer_name' => $member->name,
                    'amount_minor' => $amount,
                    'currency_code' => 'INR',
                    'payment_method' => $methods[$index % count($methods)],
                    'financial_account_id' => $account->id,
                    'transaction_reference' => 'REF'.str_pad((string) (1000 + $index), 6, '0', STR_PAD_LEFT),
                    'collected_by' => $collector->id,
                ],
            );

            $payment->forceFill($pending ? [
                'confirmation_status' => ConfirmationStatus::PendingAdminConfirmation,
                'confirmed_by' => null,
                'confirmed_at' => null,
            ] : [
                'confirmation_status' => ConfirmationStatus::Confirmed,
                'confirmed_by' => $admin->id,
                'confirmed_at' => $start->copy()->addHours(3),
            ])->save();

            $subscription->update(['amount_paid_minor' => $pending ? 0 : $amount]);
        }
    }

    /**
     * @param  array<int, Member>  $members
     * @param  array<int, OrganisationUser>  $staff
     */
    private function attendance(array $members, array $staff): void
    {
        $rows = [];
        $now = now();

        foreach (range(0, 59) as $daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);

            if ($date->isSunday()) {
                continue;
            }

            foreach ($members as $index => $member) {
                if ($member->status !== MemberStatus::Active) {
                    continue;
                }

                // A deterministic but uneven pattern, so the trend chart and
                // attendance rate are not a flat line.
                $seed = ($index * 7 + $daysAgo * 3) % 10;

                if ($seed > 6) {
                    continue;
                }

                $rows[] = [
                    'organisation_id' => $member->organisation_id,
                    'club_id' => $member->primary_club_id,
                    'subject_type' => AttendanceSubjectType::Member->value,
                    'subject_id' => $member->id,
                    'attendance_date' => $date->toDateString(),
                    'check_in_at' => $seed === 6 ? null : $date->copy()->setTime(7 + ($index % 12), 0),
                    'action' => match ($seed) {
                        6 => AttendanceAction::Absent->value,
                        5 => AttendanceAction::Late->value,
                        default => AttendanceAction::Present->value,
                    },
                    'marked_by' => $staff[$index % count($staff)]->id,
                    'source' => AttendanceSource::Manual->value,
                    'notes' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            Attendance::query()->upsert(
                $chunk,
                ['organisation_id', 'club_id', 'subject_type', 'subject_id', 'attendance_date'],
                ['action', 'check_in_at', 'marked_by', 'updated_at'],
            );
        }
    }

    /**
     * @param  array<int, Club>  $clubs
     * @param  array<int, FinancialAccount>  $accounts
     */
    private function expenses(array $clubs, array $accounts, OrganisationUser $admin): void
    {
        $index = 0;

        foreach (range(0, 2) as $monthsAgo) {
            $month = Carbon::today()->subMonthsNoOverflow($monthsAgo)->startOfMonth();

            foreach (self::EXPENSE_CATEGORIES as $category => [$min, $max]) {
                $club = $clubs[$index % count($clubs)];
                $account = $accounts[$index % count($accounts)];

                Expense::query()->updateOrCreate(
                    [
                        'category' => $category,
                        'expense_date' => $month->copy()->addDays(($index % 20) + 1)->toDateString(),
                        'club_id' => $category === 'Rent' ? null : $club->id,
                    ],
                    [
                        'amount_minor' => random_int($min, $max) * 100,
                        'currency_code' => 'INR',
                        'paid_from_financial_account_id' => $account->id,
                        'payee' => $category.' vendor',
                        'description' => $category.' for '.$month->format('F Y'),
                        'created_by' => $admin->id,
                        'status' => ExpenseStatus::Completed,
                    ],
                );

                $index++;
            }
        }
    }
}
