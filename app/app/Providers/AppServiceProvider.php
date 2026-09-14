<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Attendance;
use App\Models\Club;
use App\Models\Document;
use App\Models\Expense;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\MessageTemplate;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\StorageBucket;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());

        // Production terminates TLS at a reverse proxy, so the scheme reaches
        // the application as a header rather than on the connection. When a hop
        // in that chain forwards the wrong one, every asset URL is built as
        // http:// and the browser blocks it as mixed content on an https://
        // page — the site loads unstyled with no working JavaScript.
        //
        // Anchoring the scheme to APP_URL removes that whole failure mode: a
        // deployment configured for https stays on https no matter what the
        // proxy in front of it reports. Only the scheme is forced, so tenant
        // domains still generate their own hostnames.
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        Paginator::defaultView('vendor.pagination.default');
        Paginator::defaultSimpleView('vendor.pagination.default');

        // Store the short aliases from MEP.md ('member' / 'user' / ...) in
        // polymorphic subject_type/recipient_type/entity_type columns instead
        // of fully qualified class names.
        //
        // The map is *enforced*, so it must list every model that can reach a
        // polymorphic column — that includes anything AuditEvent::record()
        // writes an `entity_type` for, not just the attendance subjects.
        Relation::enforceMorphMap([
            'member' => Member::class,
            'user' => OrganisationUser::class,
            'club' => Club::class,
            'plan' => Plan::class,
            'subscription' => MemberSubscription::class,
            'attendance' => Attendance::class,
            'fee_payment' => FeePayment::class,
            'expense' => Expense::class,
            'financial_account' => FinancialAccount::class,
            'organisation' => Organisation::class,
            'message_template' => MessageTemplate::class,
            'document' => Document::class,
            'storage_bucket' => StorageBucket::class,
        ]);

        // The `auth` middleware serves two entirely separate guards (the
        // platform hostname's `platform` guard and every tenant domain's
        // `web` guard) sharing one route path, so the redirect target is
        // chosen by hostname rather than a single named 'login' route.
        Authenticate::redirectUsing(function (Request $request): string {
            if (strtolower($request->getHost()) === strtolower((string) config('platform.hostname'))) {
                return route('platform.login');
            }

            return route('tenant.login');
        });
    }
}
