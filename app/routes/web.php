<?php

use App\Http\Controllers\Platform\AuthController as PlatformAuthController;
use App\Http\Controllers\Platform\DashboardController as PlatformDashboardController;
use App\Http\Controllers\Platform\OrganisationController as PlatformOrganisationController;
use App\Http\Controllers\Platform\OrganisationDomainController as PlatformOrganisationDomainController;
use App\Http\Controllers\Platform\OrganisationMemberController as PlatformOrganisationMemberController;
use App\Http\Controllers\Tenant\AuthController as TenantAuthController;
use App\Http\Controllers\Tenant\BrandingController;
use App\Http\Controllers\Tenant\DocumentController;
use App\Http\Controllers\Tenant\ExportController;
use App\Http\Controllers\Tenant\PasswordResetController;
use App\Http\Middleware\EnsureActiveMembership;
use App\Livewire\Attendance\Roster as AttendanceRoster;
use App\Livewire\Audit\Index as AuditIndex;
use App\Livewire\Billing\Form as BillingForm;
use App\Livewire\Billing\Index as BillingIndex;
use App\Livewire\Billing\Show as BillingShow;
use App\Livewire\Clubs\Form as ClubForm;
use App\Livewire\Clubs\Index as ClubIndex;
use App\Livewire\Clubs\Show as ClubShow;
use App\Livewire\Dashboard\Index as TenantDashboard;
use App\Livewire\Finance\Accounts\Form as AccountForm;
use App\Livewire\Finance\Accounts\Index as AccountIndex;
use App\Livewire\Finance\Accounts\Show as AccountShow;
use App\Livewire\Finance\Confirmations as PaymentConfirmations;
use App\Livewire\Finance\Expenses\Form as ExpenseForm;
use App\Livewire\Finance\Expenses\Index as ExpenseIndex;
use App\Livewire\Finance\Payments\Form as PaymentForm;
use App\Livewire\Finance\Payments\Index as PaymentIndex;
use App\Livewire\Finance\Payments\Show as PaymentShow;
use App\Livewire\Members\Form as MemberForm;
use App\Livewire\Members\Index as MemberIndex;
use App\Livewire\Members\Show as MemberShow;
use App\Livewire\Notifications\Index as NotificationIndex;
use App\Livewire\Plans\Form as PlanForm;
use App\Livewire\Plans\Index as PlanIndex;
use App\Livewire\Reports\Index as ReportIndex;
use App\Livewire\Settings\OrganisationSettings;
use App\Livewire\Staff\Form as StaffForm;
use App\Livewire\Staff\Index as StaffIndex;
use Illuminate\Support\Facades\Route;

// Platform ("root") surface — reachable only through the dedicated platform
// hostname, never through a tenant domain. See MEP.md Section 3.3.
Route::domain(config('platform.hostname'))->group(function (): void {
    Route::get('/', [PlatformAuthController::class, 'showLogin'])->name('platform.login');
    Route::post('/login', [PlatformAuthController::class, 'login'])->name('platform.login.attempt');
    Route::post('/logout', [PlatformAuthController::class, 'logout'])
        ->middleware('auth:platform')
        ->name('platform.logout');

    Route::get('/dashboard', [PlatformDashboardController::class, 'index'])
        ->middleware('auth:platform')
        ->name('platform.dashboard');

    Route::middleware('auth:platform')->prefix('organisations')->name('platform.organisations.')->group(function (): void {
        Route::get('/', [PlatformOrganisationController::class, 'index'])->name('index');
        Route::get('/create', [PlatformOrganisationController::class, 'create'])->name('create');
        Route::post('/', [PlatformOrganisationController::class, 'store'])->name('store');
        Route::get('/{organisation}/edit', [PlatformOrganisationController::class, 'edit'])->name('edit');
        Route::put('/{organisation}', [PlatformOrganisationController::class, 'update'])->name('update');

        Route::post('/{organisation}/domains', [PlatformOrganisationDomainController::class, 'store'])->name('domains.store');
        Route::patch('/{organisation}/domains/{domain}', [PlatformOrganisationDomainController::class, 'update'])->name('domains.update');
        Route::delete('/{organisation}/domains/{domain}', [PlatformOrganisationDomainController::class, 'destroy'])->name('domains.destroy');

        Route::post('/{organisation}/members/{organisationUser}/reset-password', [PlatformOrganisationMemberController::class, 'resetPassword'])
            ->name('members.reset-password');
    });
});

// Tenant-facing surface — resolved to an organisation by the `ResolveTenant`
// middleware (prepended to the `web` group) based on the request hostname.
Route::get('/', [TenantAuthController::class, 'showLogin'])->name('tenant.login');
Route::post('/login', [TenantAuthController::class, 'login'])->name('tenant.login.attempt');

// Branding is served unauthenticated because both images appear on the
// sign-in page and the browser fetches a favicon without a session. Only the
// two paths held on the organisation record are reachable.
Route::get('/branding/logo', [BrandingController::class, 'logo'])->name('tenant.branding.logo');
Route::get('/branding/favicon', [BrandingController::class, 'favicon'])->name('tenant.branding.favicon');
Route::get('/branding/app-icon', [BrandingController::class, 'appIcon'])->name('tenant.branding.app-icon');

// Installable-app plumbing. All unauthenticated, because a browser fetches the
// manifest and registers the worker without sending the session along.
Route::get('/manifest.webmanifest', [BrandingController::class, 'manifest'])->name('tenant.manifest');

// Served from the root so its scope can cover the whole site; a worker at a
// deeper path may only control that subtree.
Route::get('/sw.js', [BrandingController::class, 'serviceWorker'])->name('tenant.service-worker');

// Redeeming a password reset link. Throttled per IP: the token is 256 bits, so
// this is about keeping a scanner from generating load, not about guessability.
Route::middleware('throttle:10,1')->group(function (): void {
    Route::get('/set-password/{token}', [PasswordResetController::class, 'show'])
        ->name('tenant.password.set');
    Route::post('/set-password/{token}', [PasswordResetController::class, 'store'])
        ->name('tenant.password.set.store');
});
Route::post('/logout', [TenantAuthController::class, 'logout'])
    ->middleware('auth:web')
    ->name('tenant.logout');

// Every route below requires an authenticated user with an active membership
// for the resolved organisation (MEP.md 3.2, 4.3). Each component and
// controller re-authorizes with a policy on top of this.
Route::middleware(['auth:web', EnsureActiveMembership::class])->group(function (): void {
    Route::get('/dashboard', TenantDashboard::class)->name('tenant.dashboard');

    Route::prefix('clubs')->name('tenant.clubs.')->group(function (): void {
        Route::get('/', ClubIndex::class)->name('index');
        Route::get('/create', ClubForm::class)->name('create');
        Route::get('/{club}', ClubShow::class)->name('show');
        Route::get('/{club}/edit', ClubForm::class)->name('edit');
    });

    Route::prefix('staff')->name('tenant.staff.')->group(function (): void {
        Route::get('/', StaffIndex::class)->name('index');
        Route::get('/create', StaffForm::class)->name('create');
        Route::get('/{organisationUser}/edit', StaffForm::class)->name('edit');
    });

    Route::prefix('members')->name('tenant.members.')->group(function (): void {
        Route::get('/', MemberIndex::class)->name('index');
        Route::get('/create', MemberForm::class)->name('create');
        Route::get('/export', [ExportController::class, 'members'])->name('export');
        Route::get('/{member}', MemberShow::class)->name('show');
        Route::get('/{member}/edit', MemberForm::class)->name('edit');
    });

    Route::prefix('plans')->name('tenant.plans.')->group(function (): void {
        Route::get('/', PlanIndex::class)->name('index');
        Route::get('/create', PlanForm::class)->name('create');
        Route::get('/{plan}/edit', PlanForm::class)->name('edit');
    });

    Route::prefix('attendance')->name('tenant.attendance.')->group(function (): void {
        Route::get('/members', AttendanceRoster::class)->defaults('subject', 'members')->name('members');
        Route::get('/users', AttendanceRoster::class)->defaults('subject', 'staff')->name('staff');
    });

    Route::prefix('finance')->name('tenant.finance.')->group(function (): void {
        Route::get('/confirmations', PaymentConfirmations::class)->name('confirmations');

        Route::prefix('payments')->name('payments.')->group(function (): void {
            Route::get('/', PaymentIndex::class)->name('index');
            Route::get('/create', PaymentForm::class)->name('create');
            // Registered before the {payment} binding so "export" is not
            // treated as a payment identifier.
            Route::get('/export', [ExportController::class, 'payments'])->name('export');
            Route::get('/{payment}', PaymentShow::class)->name('show');
            Route::get('/{payment}/receipt', [DocumentController::class, 'paymentReceipt'])->name('receipt');
        });

        Route::prefix('expenses')->name('expenses.')->group(function (): void {
            Route::get('/', ExpenseIndex::class)->name('index');
            Route::get('/create', ExpenseForm::class)->name('create');
            Route::get('/export', [ExportController::class, 'expenses'])->name('export');
            Route::get('/{expense}/edit', ExpenseForm::class)->name('edit');
            Route::get('/{expense}/receipt', [DocumentController::class, 'expenseReceipt'])->name('receipt');
        });

        Route::prefix('accounts')->name('accounts.')->group(function (): void {
            Route::get('/', AccountIndex::class)->name('index');
            Route::get('/create', AccountForm::class)->name('create');
            Route::get('/{financialAccount}', AccountShow::class)->name('show');
            Route::get('/{financialAccount}/edit', AccountForm::class)->name('edit');
        });
    });

    Route::prefix('reports')->name('tenant.reports.')->group(function (): void {
        Route::get('/', ReportIndex::class)->name('index');
        Route::get('/export', [ExportController::class, 'report'])->name('export');
        Route::get('/pdf', [DocumentController::class, 'reportSummary'])->name('pdf');
    });

    Route::prefix('billing')->name('tenant.billing.')->group(function (): void {
        Route::get('/', BillingIndex::class)->name('index');
        Route::get('/create', BillingForm::class)->name('create');
        Route::get('/{invoice}', BillingShow::class)->name('show');
        Route::get('/{invoice}/pdf', [DocumentController::class, 'invoice'])->name('pdf');
    });

    Route::get('/messages', NotificationIndex::class)->name('tenant.notifications.index');

    // Streamed through the app so the policy runs on every fetch — see
    // DocumentController::memberDocument().
    Route::get('/documents/{document}', [DocumentController::class, 'memberDocument'])
        ->name('tenant.documents.download');

    Route::get('/audit-log', AuditIndex::class)->name('tenant.audit.index');

    Route::get('/settings/organisation', OrganisationSettings::class)->name('tenant.settings.organisation');
});
