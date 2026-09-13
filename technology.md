# Gym Management Platform Technology Guide

**Purpose:** This document is the engineering guide for building the Gym Management Platform described in `MEP.md`.

**Primary requirement:** Laravel (PHP) with Blade templates and Livewire for interactivity, PostgreSQL as the only database, and a Docker Compose stack that is production-ready out of the box — not a dev-only compose file with a separate, undocumented production setup.

**Cost policy:** Prefer free, open-source packages. Every package listed below is free and open source (MIT/BSD-licensed Laravel ecosystem packages). The only cost-bearing production dependencies are the server(s) that run Docker, outbound email delivery, and optionally S3-compatible object storage or a managed PostgreSQL/Redis provider if you choose not to self-host them in Compose.

---

## 1. Recommended Stack

### 1.1 Application

- **Language:** PHP 8.3+, `declare(strict_types=1)` everywhere.
- **Framework:** Laravel 11.x.
- **Templating:** Blade.
- **Interactivity:** Livewire 3 for stateful, server-driven components (search, forms, tables, wizards) without hand-written JavaScript or a separate API layer.
- **Light client-side behaviour:** Alpine.js (ships with Livewire) for things that don't need a server round-trip — dropdown toggles, modals, tab switches.
- **Styling:** Tailwind CSS, compiled with Vite. Keep design tokens in the Tailwind config (`tailwind.config.js`) rather than scattered inline styles.
- **Build tool:** Vite (Laravel's default asset bundler) for compiling Tailwind and any small Alpine/JS modules.
- **Tables and lists:** Livewire components with server-side sorting, filtering, and Laravel's cursor pagination. Use `livewire/livewire` alone; no separate client-side table library is needed since rendering happens server-side.
- **Date/time:** Carbon (bundled with Laravel). Store UTC (`timestamptz`) and convert to the organisation timezone only at the presentation layer.
- **Icons:** `blade-ui-kit/blade-heroicons` (Heroicons as Blade components) or a similarly licensed open-source icon set.
- **Charts:** A small open-source charting library invoked from a lightweight Alpine/Chart.js component fed by server-computed, bounded datasets — never raw unbounded query results pushed to the browser.

### 1.2 Backend and data

- **Authentication:** Laravel's built-in session-based authentication (`laravel/framework` auth scaffolding), optionally `laravel/fortify` for a headless auth backend (password reset, email verification) wired to custom Blade/Livewire views.
- **Authorization:** Laravel Policies and Gates, backed by the `organisation_users.role`/`permissions` columns described in `MEP.md`.
- **Database:** PostgreSQL 16, accessed through Eloquent.
- **Cache, sessions, queues:** Redis 7.
- **Queues and scheduled jobs:** Laravel's queue system (`redis` driver) with **Laravel Horizon** for monitoring, and Laravel's task scheduler for recurring jobs (metric rollups, expiring-subscription checks).
- **File storage:** Laravel's Filesystem abstraction with the `s3` driver, pointed at either a real S3 bucket or a self-hosted S3-compatible service (MinIO) run as its own Compose service. Never store user uploads only on a single app container's local disk in production.
- **PDF export:** `barryvdh/laravel-dompdf` (or `spatie/laravel-pdf` if a headless-Chromium render is preferred for richer layouts).
- **CSV export:** Laravel's own streamed response (`League\Csv` or manual `fputcsv` through a streamed download) — avoid pulling in a full spreadsheet library unless the export needs multiple sheets or styling.
- **QR code generation:** `simplesoftwareio/simple-qrcode` (wraps the open-source `endroid/qr-code` library).

### 1.3 Testing and quality tooling

- **Test framework:** Pest (built on PHPUnit) for unit, feature, and policy tests.
- **Browser tests:** Laravel Dusk, run against the Compose stack in CI, for end-to-end flows (sign-in, attendance marking, payment confirmation).
- **Database testing:** Pest's `RefreshDatabase`/`DatabaseTransactions` traits against a real PostgreSQL service — never mock the database for feature tests, since PostgreSQL-specific behaviour (unique constraints, JSONB, transactions) is load-bearing for this domain.
- **Static analysis:** Larastan (PHPStan for Laravel) at a strict level.
- **Formatting:** Laravel Pint (wraps PHP-CS-Fixer with Laravel's house style).
- **Frontend linting:** ESLint + Prettier for the small amount of Alpine/JS in the project.
- **Dependency security:** `composer audit` and `npm audit`, or Dependabot/Renovate.
- **Load testing:** k6 against a staging Compose deployment.
- **Accessibility testing:** axe-core run through Dusk for smoke checks.

Avoid adding packages merely because they are popular. Every dependency needs a clear purpose, maintenance activity, acceptable license, and a documented reason in the PR that introduces it.

---

## 2. Multi-tenancy Implementation Rule

This is a **single PostgreSQL database, shared schema** multi-tenant application: every tenant-scoped table carries a required `organisation_id` column, and Eloquent enforces isolation automatically so that forgetting a `where()` clause cannot leak data across organisations.

### 2.1 Required layers

```text
Blade view / Livewire component
  -> Form Request (validation + authorization)
  -> Action/Service class (business rule, wrapped in DB::transaction() where needed)
  -> Eloquent model (with OrganisationScope global scope applied automatically)
  -> PostgreSQL
```

Recommended project shape:

```text
app/
  Actions/
    Members/
    Payments/
    Attendance/
    Notifications/
  Console/
    Commands/
  Http/
    Controllers/
    Requests/
    Middleware/
      ResolveTenant.php
  Livewire/
    Members/
    Users/
    Clubs/
    Attendance/
    Payments/
    Expenses/
    Reports/
  Models/
    Concerns/
      BelongsToOrganisation.php
    Scopes/
      OrganisationScope.php
  Policies/
  Notifications/
  Support/
    Formatting/
    Validation/
resources/
  views/
    layouts/
    livewire/
    components/
database/
  migrations/
  factories/
  seeders/
tests/
  Feature/
  Unit/
  Pest.php
```

### 2.2 The organisation global scope

Every tenant-scoped Eloquent model uses a shared trait and global scope so that a query without an explicit tenant filter still cannot cross tenant boundaries:

```php
declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Scopes\OrganisationScope;

trait BelongsToOrganisation
{
    protected static function bootBelongsToOrganisation(): void
    {
        static::addGlobalScope(new OrganisationScope());

        static::creating(function ($model) {
            if (! $model->organisation_id && app()->bound('tenant')) {
                $model->organisation_id = app('tenant')->id;
            }
        });
    }

    public function organisation()
    {
        return $this->belongsTo(\App\Models\Organisation::class);
    }
}
```

```php
declare(strict_types=1);

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrganisationScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (app()->bound('tenant')) {
            $builder->where($model->getTable() . '.organisation_id', app('tenant')->id);
        }
    }
}
```

Rules for this scope:

- `app('tenant')` is bound once, early, by `ResolveTenant` middleware — never re-derived from request input inside a controller or component.
- Platform-level console commands (which run outside an HTTP request and have no bound tenant) must explicitly pass an organisation and use `withoutGlobalScope(OrganisationScope::class)` plus an explicit `where('organisation_id', $id)`, so cross-tenant access is always visible in a code review, never implicit.
- Route-model binding for tenant-scoped models (`{member}`, `{club}`, `{payment}`) relies on this scope to turn a foreign-tenant ID into a 404 automatically.

### 2.3 Keep infrastructure concerns at the edge

- Convert request input to typed values (DTOs, Form Request `validated()` arrays) at the controller/Livewire boundary; don't pass raw `$request` arrays deep into Action classes.
- Keep PostgreSQL-specific column types (`jsonb`, enums implemented as PostgreSQL `check` constraints or native `ENUM` types) behind Eloquent casts (`casts()` method, PHP backed enums) so application code works with typed PHP values, not raw arrays/strings.
- Use PHP backed enums for every `status`/`role`/`action` column so invalid values are a compile-time/type error in application code, even though the database also enforces a check constraint.

### 2.4 Transactions

Financial confirmation, member transfer, subscription updates, audit events, and notification snapshots need atomic behaviour. Use Laravel's `DB::transaction()` directly — PostgreSQL's native ACID transactions are the mechanism, with no additional unit-of-work abstraction required:

```php
DB::transaction(function () use ($payment, $admin) {
    $payment->update([
        'confirmation_status' => ConfirmationStatus::Confirmed,
        'confirmed_by' => $admin->id,
        'confirmed_at' => now(),
    ]);

    $payment->subscription?->applyPayment($payment->amount_minor);

    AuditEvent::record($payment, 'fee_payment.confirmed', $admin);
});
```

Document the isolation level assumptions for each use case (Laravel/PostgreSQL default `READ COMMITTED` is sufficient for this domain; use `SELECT ... FOR UPDATE` inside the transaction wherever two concurrent requests could both read a stale subscription balance, e.g. payment confirmation and subscription renewal).

---

## 3. Domain and Data Design

Follow `MEP.md` as the source of truth for organisations, domains, users, clubs, members, attendance, plans, subscriptions, payments, accounts, expenses, WhatsApp notifications, and audit events.

### 3.1 Tenant context

Every Action class and Policy receives a trusted tenant context, derived from the authenticated session and the `ResolveTenant`-bound organisation — never from a hidden form field or query parameter:

```php
declare(strict_types=1);

namespace App\Support;

final readonly class TenantContext
{
    public function __construct(
        public int $organisationId,
        public int $actorUserId,
        public string $role,
        public array $clubIds,
        public array $permissions,
        public string $timezone,
    ) {}

    public static function fromRequest(): self
    {
        $organisation = app('tenant');
        $membership = auth()->user()->membershipFor($organisation);

        return new self(
            organisationId: $organisation->id,
            actorUserId: auth()->id(),
            role: $membership->role->value,
            clubIds: $membership->activeClubIds(),
            permissions: $membership->permissions,
            timezone: $organisation->timezone,
        );
    }
}
```

Never accept `organisation_id`, role, or allowed clubs only from browser form data or route parameters. Resolve the organisation from the hostname (`ResolveTenant` middleware) and authenticate membership before touching tenant data.

### 3.2 Historical records

- Use append-only `audit_events` rows — no update/delete route or Eloquent mutator for this model.
- Archive (soft-delete) operational records instead of hard-deleting them when history matters (`SoftDeletes` trait plus an explicit `status = archived` column where the domain needs a visible archived state, not just a hidden `deleted_at`).
- Preserve the original club on attendance, payment, expense, and subscription records — never cascade-update `club_id` on historical rows during a transfer.
- Use reversal records for completed financial corrections.
- Treat `confirmation_status` as the canonical fee-payment lifecycle.
- Store actor and database timestamps (`now()`, not client-supplied dates) for important transitions.

### 3.3 IDs and dates

- Use auto-incrementing bigint primary keys for internal foreign keys; expose a separate opaque public identifier (ULID or UUID column) only where IDs are shown in URLs to reduce enumeration, if the organisation's threat model requires it.
- Do not use array positions or JSON array indexes as identifiers.
- Use PostgreSQL `date` columns (`YYYY-MM-DD`) for attendance days.
- Use `timestamptz` for event time, written via `now()`/Carbon.
- Store UTC timestamps and format them in the organisation timezone at render time (Carbon's `->setTimezone($organisation->timezone)`), never in the query itself.
- Never use the browser/PHP process timezone for financial or attendance boundaries — always the organisation's configured timezone.
- Make every date range's inclusivity explicit in query builders, for example `whereBetween('date', [$start, $end])` documented as `[start, end]` inclusive, or `where('date', '>=', $start)->where('date', '<', $end)` for `[start, end)`.

### 3.4 Money

Do not use PHP floating-point arithmetic for money.

Store the smallest currency unit as an integer column (`amount_minor`, e.g. paise or cents), and use a value object or a small formatting helper (`MoneyFormatter`) at the presentation boundary to render major-unit, locale-formatted strings. Never trust totals calculated by the browser or Livewire client-side state; recompute and validate on the server before persisting.

Recommended columns, as used throughout Section 5 of `MEP.md`:

```text
amount_minor: integer
currency_code: ISO 4217 code (char(3))
```

### 3.5 Personal data

Collect the minimum necessary information. Establish retention and deletion rules before production.

- Avoid storing secrets in PostgreSQL columns; use Laravel's encrypted casts (`encrypted`, backed by `APP_KEY`) only for genuinely sensitive fields, and prefer not storing the sensitive value at all where possible.
- Never store passwords anywhere but the `users.password` column, hashed via Laravel's `Hash` facade (bcrypt/argon2id).
- Do not store full bank credentials.
- Restrict access to emergency contacts, financial details, and audit before/after values via Policies, not just hidden UI.
- Mask phone numbers and payment references in logs where possible (a custom log formatter/processor).
- Do not place invitation tokens, passwords, internal permissions, or private audit data in WhatsApp messages.

---

## 4. Eloquent, Policy, and Queue Rules

### 4.1 Database schema strategy

Use the `MEP.md` table names and require `organisation_id` on every tenant table, enforced with a `NOT NULL` constraint and a foreign key to `organisations.id`.

PostgreSQL gives this domain real relational guarantees Firestore-style document stores don't, so use them:

- Native `ENUM` types (or `CHECK` constraints) for every status/role/action column.
- `JSONB` columns for genuinely flexible data (`permissions`, `address`, `opening_hours`, `metadata`) with GIN indexes where you need to query inside them.
- Composite unique indexes as the actual dedup/idempotency mechanism (attendance, WhatsApp notifications) — see `MEP.md` Sections 5.9 and 5.14.
- Foreign keys with `ON DELETE RESTRICT` for tenant-scoped relations (organisations, clubs, members) so a row can't be silently orphaned; use `ON DELETE CASCADE` only for genuinely dependent child rows (e.g. `member_club_history` on `members`, if the product ever needs hard-deletes).
- Composite indexes matching real query patterns (club + status + date range for attendance and payment lists).
- Database transactions for every multi-row financial or lifecycle change.

Denormalised values (club name snapshots on historical records, `organisation_users.club_ids`) are read optimisations, not the source of truth. Document which fields are authoritative and which are snapshots in the migration's comment or a short doc-block on the model.

### 4.2 Policies and Form Requests

Authorization and validation are mandatory on every write and are not replaced by hiding a button in Blade.

Every Policy method must check:

- Authentication (handled by the `auth` middleware before the policy runs).
- Active organisation membership (`organisation_users.status = active`).
- Organisation equality between the resolved tenant and the target model's `organisation_id` (defense in depth on top of the global scope).
- Active user status.
- Admin versus staff permission (`organisation_users.role` / `permissions` map).
- Assigned club access (`club_user_assignments`).
- Immutable financial fields (a Policy's `update` method returns `false` for a confirmed `fee_payments` row; only a dedicated `reverse` ability is allowed).
- Allowed payment state transitions (a small state-machine check inside the Action class, not the Policy).
- No client-side privilege escalation — mass-assignment protected (`$fillable` never includes `role`, `permissions`, or `organisation_id`).

Sensitive operations (organisation setup, invitations, transfers, payment confirmation, reversals, report generation, notification snapshots) go through Action classes invoked from authorized controllers/Livewire components, never directly from a generic "update model" route.

Test both successful and denied access with Pest policy tests. Include cross-organisation and cross-club attack cases (act as a valid user of organisation A, attempt to load/mutate a record belonging to organisation B, assert a 404 or 403).

### 4.3 Queries

- Never load an entire table for a search or report; always filter by the resolved tenant (automatic via the global scope) plus real filters.
- Paginate all large lists (`paginate()` or `cursorPaginate()`).
- Debounce Livewire search input (`wire:model.live.debounce.400ms`).
- Require a minimum query length for remote search.
- Avoid unbounded `whereIn()` calls built from unpaginated user input.
- Create indexes from real query patterns (checked with `EXPLAIN ANALYZE` in a staging environment with representative data volume), not guesses.
- Keep query logic inside Eloquent model scopes or query builder classes, and cover them with tests, rather than scattering `where()` chains across controllers and views.
- Avoid N+1 queries; use eager loading (`with()`, `load()`) and Laravel Debugbar / Telescope locally to catch them before they reach production.

### 4.4 Write patterns

- Use `DB::transaction()` for state-dependent, multi-row changes.
- Use PostgreSQL unique constraints as the idempotency mechanism for retries (attendance marks, WhatsApp notification creation, payment confirmation) rather than an application-level "check then write" race.
- Use database timestamps (`now()`), not client-supplied dates, for authoritative event times.
- Use optimistic locking (a `lock_version`/`updated_at` check) where concurrent edits to the same row matter, e.g. two admins editing the same member simultaneously.
- Make reversal and audit operations append-only — no update/delete path in the model or controller layer.
- Never allow a Livewire component or client request to write authoritative aggregates or financial totals directly; totals are always recomputed or updated by a server-side Action class inside a transaction.

### 4.5 Actions and queued jobs

Keep Action classes small and single-purpose. Each one validates input (via the calling Form Request), authorizes the actor (via a Policy), resolves tenant context, performs the use case inside a transaction where needed, and returns a typed result.

Use Laravel queued jobs (Redis-backed, monitored with Horizon) for:

- Sending invitation, password-reset, and notification emails.
- Generating reports and exports beyond a small row count.
- Scheduled metric rollups (triggered by the Laravel scheduler, `app/Console/Kernel.php`'s `schedule()` method, running as the `scheduler` Compose service).
- Any operation that shouldn't block the HTTP/Livewire response.

Jobs must be idempotent where a client or the queue can retry after a timeout (`ShouldBeUnique` job interface, or an application-level idempotency key check, for jobs that must not run twice for the same business event).

---

## 5. Frontend Engineering Rules (Blade + Livewire)

### 5.1 Boundaries

- Blade views render markup; Livewire components hold page/interaction state and call into Action classes — they never contain business decisions directly (a Livewire component method should read as a short sequence of "authorize, validate, call an Action class, flash a result").
- Policies contain permission decisions — never a bare `@if(auth()->user()->role === 'admin')` scattered through views. Use `@can`/`@cannot` Blade directives backed by real Policy methods.
- Eloquent models and their scopes contain persistence rules; Livewire components should not build raw query builder chains inline for anything beyond a simple `find`/`where` on an already-scoped relation.

### 5.2 Livewire component conventions

- Public properties that back form inputs are validated with the same `rules()` as the equivalent Form Request where a matching one exists, to avoid rule drift between a full-page form and any future API surface.
- Use `wire:model.live.debounce` for search/filter inputs, not `wire:model` (fires on every keystroke), to bound the number of requests.
- Use Livewire's `#[Locked]` attribute (or explicit re-validation in mount) for any property that carries tenant/organisation/club identity so it cannot be tampered with via the client-side Livewire snapshot.
- Paginate with Livewire's built-in `WithPagination` trait; never render an unpaginated list of members/payments/attendance rows.
- Use Livewire events (or the newer `Livewire::dispatch`) sparingly, for real cross-component communication (e.g. "payment confirmed" refreshing a dashboard card), not as a general-purpose event bus.

### 5.3 Forms and validation

- Use Form Request classes for controller-driven writes and Livewire's `rules()`/`#[Validate]` attributes for component-driven writes, sharing the same underlying `Rule` objects (custom `Rule` classes for phone normalisation, club-code uniqueness, etc.) so validation logic is defined once.
- Normalise phone and email before persisting (a shared `Support\Validation\PhoneNumber` value object).
- Show field-level errors and a clear summary for long forms (`$errors->has()`/`$errors->first()` in Blade, live-validated by Livewire).
- Disable duplicate submission while a write is in progress (`wire:loading.attr="disabled"` on the submit button, plus a server-side idempotency check for anything financial).
- Preserve entered data after recoverable errors — Livewire keeps public property state across a failed validation automatically; don't reset the component on error.

### 5.4 Routing and access

- Resolve tenant context in middleware before any tenant-scoped route or Livewire component mounts.
- Use route-level `can:` middleware or `$this->authorize()` for permission guards.
- Re-check permissions inside every Action class as well — never rely on the route middleware alone, since a Livewire component can be invoked with a stale or tampered client payload.
- Keep deep links stable and bookmarkable (route-model binding on human-meaningful slugs/codes where appropriate, not just numeric IDs, though numeric IDs are fine when scoped by the global tenant scope).
- Redirect users to the relevant detail page after a successful mutation (`$this->redirectRoute(...)` in Livewire).
- Preserve filters and selected club when practical (persist as query-string state via Livewire's `#[Url]` attribute).

### 5.5 Performance

- Lazy-load heavier Livewire components (`wire:init` or Livewire's lazy loading) for dashboard panels that aren't immediately visible.
- Keep large admin reports out of the main page's initial render; load them via a queued job plus a Livewire polling/broadcast update.
- Virtualise or paginate large tables — never render thousands of rows to the DOM at once.
- Avoid rendering all clubs or members in memory for a dropdown; use a searchable, paginated Livewire select for large organisations.
- Measure before adding caching; use Laravel's query result caching (Redis) only for genuinely expensive, infrequently-changing reads (e.g. dashboard rollups).
- Compress images and limit upload sizes (validated in the Form Request, enforced again at the storage layer).
- Generate and store thumbnails for member photos and receipt previews rather than resizing on every request.
- Keep dashboard queries bounded and prefer the `organisation_daily_metrics` rollup table over live aggregation for large tenants.

### 5.6 WhatsApp actions

WhatsApp links are user-triggered browser actions, not server delivery. Generate the URL server-side (in the Blade view or a small Alpine `x-data` handler bound to server-rendered data) as:

```text
https://wa.me/{internationalNumber}?text={urlencode(message)}
```

Rules:

- Normalise the number and remove spaces, punctuation, and the leading `+` before it reaches the view (PHP-side, not client-side JavaScript).
- URL-encode the message server-side (PHP's `rawurlencode`) so the Blade-rendered `href` is already safe; don't re-encode in JavaScript.
- Open only after the admin explicitly clicks — a plain `<a target="_blank">`, not an automatic `window.open` on page load.
- Keep copy-to-clipboard (a small Alpine snippet) and a normal clickable link as fallbacks for popup blockers.
- Record `opened`, `skipped`, `unavailable`, or `failed` via a Livewire action fired on click; never record "delivered" from a deep-link action.
- Preserve the generated message snapshot and template version in the database, written before the link is ever shown.

---

## 6. Security and Privacy

### 6.1 Threat model to account for

At minimum, test against:

- A user tampering with a Livewire component's client-side snapshot to change an `organisation_id`, `club_id`, or `role` property.
- A staff user requesting another club's member by guessing or incrementing a route-bound ID.
- A staff user attempting to grant themselves admin permissions via a mass-assignment gap.
- A client marking a pending payment as confirmed by calling a Livewire method directly (not through the intended UI flow).
- Duplicate payment confirmation after a timeout or double-click.
- Replay of an invitation or mutation request.
- Export requests with broader filters than the user can access.
- Malicious receipt or image uploads (wrong MIME type, oversized files, embedded scripts in SVGs).
- XSS through names, notes, club labels, or message templates (Blade's `{{ }}` escaping is the default defense; never use `{!! !!}` on user-controlled content).
- WhatsApp message injection through member names or custom text (still URL-encode and length-limit even though it's a `wa.me` link, not a server-rendered page).
- Leaking personal data in logs, exports, error messages, or URLs.
- SQL injection via raw query fragments — always use Eloquent/query builder parameter binding, never string-interpolated SQL.

### 6.2 Authentication

- Use Laravel's session-based authentication for the first implementation; sessions stored in Redis so any app container replica can read them.
- Require verified email if the organisation's policy needs it (Laravel's built-in email verification).
- Revoke or disable access for deactivated users at both the `users` and `organisation_users` level, checked on every request via middleware, not only at login.
- Keep tenant membership and role in the database (`organisation_users`) as the single source of truth; don't cache role/permissions in the session beyond a short TTL that's invalidated on change.
- Use least privilege for the database role the application connects with (no `SUPERUSER`, and a separate, more restricted role for the migration/deploy step versus the runtime app connection if you want extra isolation).
- Rate-limit login attempts (Laravel's built-in throttle middleware).

### 6.3 Authorisation

Centralise permission constants in a PHP backed enum and enforce with Policy classes:

```php
Gate::authorize('confirm', $payment);
```

The same policy intent must be enforced in the Action class and reflected in the UI (`@can` in Blade). Hiding a button is not authorisation.

### 6.4 Input and output safety

- Validate all controller and Livewire input through Form Requests / `rules()`.
- Rely on Blade's automatic escaping (`{{ }}`) for all user-controlled text; treat any `{!! !!}` usage as a flagged, reviewed exception.
- Sanitise or reject HTML in notes and templates unless a reviewed sanitizer (e.g. `mews/purifier`) is deliberately introduced for a rich-text field.
- Allowlist message placeholders in a const map, never interpolate arbitrary field names into WhatsApp templates.
- Validate file type, size, and content (not just extension) before storage, using Laravel's file validation rules plus a MIME-sniffing check.
- Use private storage disks with signed, expiring URLs (Laravel's `Storage::temporaryUrl()`) for receipts and photos rather than public buckets.
- Rate-limit expensive searches, exports, invitations, and report jobs (Laravel's rate limiter, keyed by user and organisation).

### 6.5 Secrets and configuration

- Keep secrets out of Git and out of the built frontend bundle; only `VITE_`-prefixed env vars reach the browser, and none of them should be secrets.
- Use a distinct `.env` per environment (local, staging, production), loaded into containers via Docker Compose `env_file`, never baked into the image.
- Use `.env.example` with variable names but no real values, committed to the repo.
- Rotate `APP_KEY`, database credentials, and any third-party API keys on a documented schedule and immediately after any suspected exposure.
- Never send secrets through WhatsApp or store them in audit snapshots.
- In production, prefer Docker secrets or the host's secret manager over plain environment variables for the database password and `APP_KEY`, where the deployment target supports it (see Section 10.6).

---

## 7. Reporting, Rollups, and Long-Term Scale

### 7.1 Source of truth versus projections

Source-of-truth tables:

- Members.
- Club assignments and transfer history.
- Attendance.
- Subscriptions.
- Payments.
- Expenses.
- Audit events.

Derived projections:

- Dashboard counters.
- `organisation_daily_metrics` rollup rows.
- Club summary cards.
- Staff collection leaderboards.
- Report cache rows/exports.

A projection may be rebuilt from source records at any time. Never make a projection the only place a financial event exists.

### 7.2 Rollup strategy

For small tenants, scoped Eloquent queries with proper indexes may be sufficient at request time. For larger tenants:

- Use the Laravel scheduler plus queued jobs to update `organisation_daily_metrics` on a fixed cadence (e.g. nightly, or hourly for the current day).
- Track a rollup version and the last processed event/timestamp so jobs can resume safely.
- Make rollup jobs retryable and idempotent (`ShouldBeUnique`, or an upsert keyed by `(organisation_id, metric_date)`).
- Rebuild rollups when their definition changes (a dedicated Artisan command, not a manual SQL patch).
- Clearly label delayed or approximate metrics in the UI (e.g. "as of 03:00 today").
- Do not include pending/rejected payments in confirmed revenue rollups.

### 7.3 Scaling beyond a single database server

If a single PostgreSQL instance eventually becomes a bottleneck:

1. Add a read replica for reporting/export queries before considering sharding.
2. Move the largest, append-only tables (`audit_events`, `attendances`) to native PostgreSQL table partitioning by month, keyed by `organisation_id`/date, while keeping the same Eloquent model interface.
3. Only consider splitting large tenants into their own database/schema if a single organisation's data volume genuinely outgrows a shared schema — most gym/club tenants will not reach this scale.
4. Keep a rollback plan and a verified backup before any structural database change.

---

## 8. Testing Strategy

### 8.1 Unit tests (Pest)

Test pure domain logic with no database:

- Permission policy logic (given a role/permission map, is an action allowed).
- Tenant resolution rules (hostname normalisation, status handling).
- Money calculations (minor-unit arithmetic, currency formatting).
- Subscription date and balance calculations.
- Payment lifecycle transition rules (which transitions are legal).
- Member transfer rules.
- Attendance date boundary logic (organisation-timezone day boundaries).
- Phone normalisation.
- WhatsApp URL and message generation (encoding, allowlisted placeholders).
- Report filter logic.

### 8.2 Feature tests against real PostgreSQL

Run every feature test against an actual PostgreSQL database (a service container in CI, `RefreshDatabase` or `DatabaseTransactions` trait locally) — never mock the database, since unique constraints, JSONB behaviour, and transaction semantics are core to correctness here:

- Tenant-scoped reads and writes (the global scope actually excludes other tenants).
- Pagination.
- Duplicate and idempotent writes (unique-constraint-backed idempotency).
- Transactions and conflict behaviour (simulate concurrent confirmation attempts).
- Archive and reversal behaviour.
- Query filters and index usage for representative data volumes.

### 8.3 Policy tests

Required cases, run as Pest feature tests hitting real routes/Livewire components:

- Admin versus staff access.
- Assigned versus unassigned club.
- Organisation A versus organisation B (a staff/admin user from A must get a 404/403 on B's records).
- Deactivated users.
- Payment confirmation and reversal.
- Member transfer.
- Audit immutability (no route/method exists to update or delete an audit event).
- Notification ownership and phone protection.
- Export scope.

### 8.4 Browser/end-to-end tests (Dusk)

Cover, against a running instance of the full stack:

- Domain resolution and sign-in.
- Admin creates club, user, and member.
- User accesses only assigned clubs.
- Member transfer preserves history.
- Staff submits payment; admin confirms; balance updates once.
- Admin edits member and receives WhatsApp action panel.
- WhatsApp copy and popup-block fallback.
- Reports exclude unauthorised and unconfirmed records.

### 8.5 Performance tests

Measure realistic scenarios with k6 against a staging deployment:

- Large member search.
- Daily attendance for a full club.
- Payment confirmation under concurrent clicks (verify the unique constraint/transaction actually prevents double-application).
- Dashboard load for many clubs.
- Report generation with a long date range.
- Export generation and download.

Set budgets and fail CI or staging checks when regressions exceed agreed limits.

---

## 9. Observability and Operations

### 9.1 Structured logging

Configure a Laravel logging channel that emits JSON, including for every backend operation:

```text
request_id
organisation_id
actor_user_id
operation
entity_type
entity_id
result
latency_ms
error_code
```

Never log passwords, tokens, full phone numbers, bank details, message contents containing personal data, or full payment data unless explicitly required and protected.

### 9.2 Metrics

Track (via Laravel Horizon for queues, and application-level counters exported to your monitoring stack of choice — Prometheus via a package like `spatie/laravel-horizon-prometheus`-style exporters, or a hosted APM):

- Queue job latency and failure rate.
- PostgreSQL query volume, slow queries, and connection pool saturation.
- Authentication failures.
- Permission denials.
- Payment confirmation conflicts (unique-constraint violations caught and retried).
- Report/export job duration.
- Notification generation failures.
- WhatsApp link action failures.
- File upload failures.
- Frontend errors (a small Alpine error handler reporting to a logging endpoint) and largest-contentful-paint/route load time.

### 9.3 Backups and recovery

- Schedule `pg_dump` (or PostgreSQL's continuous WAL archiving for point-in-time recovery) from a dedicated backup process — see Section 10.7 for how this fits into the Compose stack.
- Back up object storage (S3 bucket versioning, or MinIO's own backup/replication) and document retention rules.
- Test restoring backups on a schedule, not only when an incident happens.
- Keep migration scripts versioned in the repository (Laravel migrations already provide this).
- Document incident response for tenant leakage, financial corruption, and account compromise.

### 9.4 Environments

Use separate `.env` configurations and separate PostgreSQL databases for:

- Local development (Docker Compose dev override, with mail caught by Mailpit).
- Shared staging (a scaled-down copy of the production Compose stack).
- Production.

Never run destructive migrations against production without first testing them in staging with a production-sized data snapshot. Protect production deploys with code review and CI checks.

---

## 10. Docker and Production Deployment

This is the authoritative production deployment design: a Docker Compose stack that runs the full application — no separate "how we actually deploy this" document.

### 10.1 Topology

```text
                        ┌────────────┐
        internet ─────▶ │   nginx    │  (TLS termination, static assets, reverse proxy)
                        └─────┬──────┘
                              │ FastCGI
                        ┌─────▼──────┐
                        │    app     │  (PHP-FPM, Laravel)
                        └──┬──────┬──┘
                           │      │
                 ┌─────────┘      └─────────┐
           ┌─────▼─────┐              ┌─────▼─────┐
           │ postgres  │              │   redis   │
           └───────────┘              └─────┬─────┘
                                             │
                        ┌────────────────────┼───────────────────┐
                  ┌─────▼─────┐        ┌─────▼─────┐       ┌─────▼─────┐
                  │  worker   │        │  horizon  │       │ scheduler │
                  │ (queue)   │        │ (monitor) │       │  (cron)   │
                  └───────────┘        └───────────┘       └───────────┘
```

All application-code containers (`app`, `worker`, `horizon`, `scheduler`) are built from the **same image**, differing only in their `command`. This guarantees the queue worker and the scheduler always run the exact code that was deployed, never a stale copy.

### 10.2 Dockerfile (multi-stage, production image)

```dockerfile
# syntax=docker/dockerfile:1

# ---- Stage 1: PHP dependencies ----
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --no-progress \
    --prefer-dist

# ---- Stage 2: frontend assets ----
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
COPY --from=vendor /app/vendor ./vendor
RUN npm run build

# ---- Stage 3: production runtime ----
FROM php:8.3-fpm-alpine AS app

RUN apk add --no-cache \
        libpq-dev \
        icu-dev \
        oniguruma-dev \
        libzip-dev \
        supervisor \
    && docker-php-ext-install \
        pdo_pgsql \
        intl \
        mbstring \
        zip \
        opcache \
        bcmath

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions redis

RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=192" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html

COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build
COPY . .

RUN addgroup -g 1000 laravel \
    && adduser -G laravel -u 1000 -D laravel \
    && chown -R laravel:laravel /var/www/html \
    && chmod -R 775 storage bootstrap/cache

USER laravel

RUN php artisan config:cache --no-interaction \
    && php artisan route:cache --no-interaction \
    && php artisan view:cache --no-interaction \
    && php artisan event:cache --no-interaction

EXPOSE 9000
HEALTHCHECK --interval=30s --timeout=5s --start-period=15s --retries=3 \
    CMD php artisan tinker --execute="exit(0);" || exit 1

CMD ["php-fpm"]
```

Notes:

- `config:cache`/`route:cache`/`view:cache` run at build time, baking the resolved configuration into the image. This means **every configuration value must come from environment variables present at build time or read at runtime through `env()` calls that are avoided outside `config/*.php`** — never call `env()` directly in application code, only in `config/` files, per Laravel convention, since `config:cache` freezes those values.
- The image runs as a non-root user (`laravel`), a security requirement for any production container.
- `opcache.validate_timestamps=0` requires a fresh container build (not a mounted volume) for every deploy — this is intentional; production never bind-mounts application code.

### 10.3 docker-compose.yml (production)

```yaml
name: gym-platform

x-app-image: &app-image
  image: ${IMAGE_TAG:-gym-platform:latest}
  build:
    context: .
    dockerfile: Dockerfile
  env_file:
    - .env
  depends_on:
    postgres:
      condition: service_healthy
    redis:
      condition: service_healthy
  networks:
    - internal
  restart: unless-stopped
  logging:
    driver: json-file
    options:
      max-size: "10m"
      max-file: "5"

services:
  nginx:
    image: nginx:1.27-alpine
    depends_on:
      - app
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./docker/nginx/conf.d:/etc/nginx/conf.d:ro
      - ./docker/nginx/certs:/etc/nginx/certs:ro
      - app-storage:/var/www/html/storage:ro
      - app-public-build:/var/www/html/public/build:ro
    networks:
      - internal
    restart: unless-stopped
    healthcheck:
      test: ["CMD", "wget", "-q", "--spider", "http://localhost/up"]
      interval: 30s
      timeout: 5s
      retries: 3

  app:
    <<: *app-image
    volumes:
      - app-storage:/var/www/html/storage
      - app-public-build:/var/www/html/public/build

  worker:
    <<: *app-image
    command: php artisan queue:work redis --queue=default,notifications,reports --sleep=3 --tries=3 --max-time=3600 --timeout=120
    volumes:
      - app-storage:/var/www/html/storage
    deploy:
      replicas: 2

  horizon:
    <<: *app-image
    command: php artisan horizon
    volumes:
      - app-storage:/var/www/html/storage
    stop_grace_period: 30s

  scheduler:
    <<: *app-image
    command: >
      sh -c "while true; do
        php artisan schedule:run --no-interaction --verbose 2>&1;
        sleep 60;
      done"
    volumes:
      - app-storage:/var/www/html/storage

  migrate:
    <<: *app-image
    command: php artisan migrate --force
    restart: "no"

  postgres:
    image: postgres:16-alpine
    environment:
      POSTGRES_DB: ${DB_DATABASE}
      POSTGRES_USER: ${DB_USERNAME}
      POSTGRES_PASSWORD: ${DB_PASSWORD}
    volumes:
      - postgres-data:/var/lib/postgresql/data
    networks:
      - internal
    restart: unless-stopped
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U ${DB_USERNAME} -d ${DB_DATABASE}"]
      interval: 10s
      timeout: 5s
      retries: 5
    deploy:
      resources:
        limits:
          memory: 1g

  redis:
    image: redis:7-alpine
    command: redis-server --appendonly yes --requirepass ${REDIS_PASSWORD}
    volumes:
      - redis-data:/data
    networks:
      - internal
    restart: unless-stopped
    healthcheck:
      test: ["CMD", "redis-cli", "-a", "${REDIS_PASSWORD}", "ping"]
      interval: 10s
      timeout: 5s
      retries: 5

  minio:
    image: minio/minio:latest
    command: server /data --console-address ":9001"
    environment:
      MINIO_ROOT_USER: ${MINIO_ROOT_USER}
      MINIO_ROOT_PASSWORD: ${MINIO_ROOT_PASSWORD}
    volumes:
      - minio-data:/data
    networks:
      - internal
    restart: unless-stopped
    profiles: ["self-hosted-storage"]
    healthcheck:
      test: ["CMD", "mc", "ready", "local"]
      interval: 15s
      timeout: 5s
      retries: 5

  backup:
    image: postgres:16-alpine
    entrypoint: >
      sh -c "while true; do
        PGPASSWORD=$$DB_PASSWORD pg_dump -h postgres -U $$DB_USERNAME -Fc $$DB_DATABASE
          > /backups/backup-$$(date +%Y%m%d-%H%M%S).dump;
        find /backups -type f -mtime +14 -delete;
        sleep 86400;
      done"
    env_file:
      - .env
    volumes:
      - ./backups:/backups
    networks:
      - internal
    depends_on:
      postgres:
        condition: service_healthy
    restart: unless-stopped

networks:
  internal:
    driver: bridge

volumes:
  postgres-data:
  redis-data:
  minio-data:
  app-storage:
  app-public-build:
```

Notes on choices:

- `minio` is behind the `self-hosted-storage` Compose profile — enable it with `docker compose --profile self-hosted-storage up -d` when you want self-hosted object storage instead of a managed S3 bucket. If using real S3, omit the profile and just set `AWS_*` env vars; nothing else in the stack changes.
- `migrate` is a one-shot service (`restart: "no"`) run explicitly during deploy (`docker compose run --rm migrate`), never automatically on every `app` container start — this prevents concurrent migration runs when `app` scales to multiple replicas.
- `worker` is scaled to 2 replicas by default; adjust with `docker compose up -d --scale worker=N` or the `deploy.replicas` value based on queue depth observed in Horizon.
- `backup` is a simple, dependency-free scheduled `pg_dump` loop suitable for a single-host deployment. For multi-host or higher-durability requirements, replace it with WAL-G/pgBackRest streaming backups to object storage.
- Named volumes (`app-storage`, `app-public-build`) let `nginx` read the same files the `app`/`worker` containers write, without either side needing a bind mount to the host. If you run `app` and `nginx` on different hosts, replace these with a shared network filesystem or move user uploads entirely to S3/MinIO (recommended once you scale beyond a single host).

### 10.4 nginx configuration (`docker/nginx/conf.d/app.conf`)

```nginx
server {
    listen 80;
    server_name _;

    root /var/www/html/public;
    index index.php;

    client_max_body_size 20m;

    location = /up {
        access_log off;
        return 200 "ok";
    }

    location /build/ {
        alias /var/www/html/public/build/;
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_read_timeout 120;
    }

    location ~ /\.(?!well-known) {
        deny all;
    }
}
```

Terminate TLS at `nginx` (mount real certificates into `docker/nginx/certs`, obtained via your DNS/certificate provider or a `certbot` sidecar) or, more commonly in production, put a managed load balancer / reverse proxy (e.g. the host provider's LB, or Traefik/Caddy if you prefer automatic certificate management) in front of this `nginx` service and keep this compose file focused on the application tier.

### 10.5 Environment variables (`.env` for production)

Keep a versioned `.env.example` with names only; real values live in the deployment host's secret store or CI/CD secret manager, materialised into `.env` at deploy time.

```env
APP_NAME="Gym Management Platform"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://example.com

LOG_CHANNEL=stack
LOG_LEVEL=info

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=gym_platform
DB_USERNAME=gym_platform
DB_PASSWORD=

REDIS_HOST=redis
REDIS_PASSWORD=
REDIS_PORT=6379

CACHE_STORE=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120
QUEUE_CONNECTION=redis

FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_ENDPOINT=
AWS_USE_PATH_STYLE_ENDPOINT=false

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"

MINIO_ROOT_USER=
MINIO_ROOT_PASSWORD=
```

When using the `minio` profile, point `AWS_ENDPOINT` at `http://minio:9000` and set `AWS_USE_PATH_STYLE_ENDPOINT=true`.

### 10.6 Secrets handling

- Never commit a real `.env` file; `.gitignore` it and ship `.env.example` only.
- On a single Docker host, use Docker Compose's `secrets:` top-level element (files mounted read-only at `/run/secrets/*`) for `DB_PASSWORD`, `REDIS_PASSWORD`, and `APP_KEY` instead of plain environment variables, if the deployment target supports Compose secrets (Docker Swarm mode) or your CI populates `.env` from a secret manager (e.g. 1Password, Vault, or the cloud provider's secret store) immediately before `docker compose up`.
- Rotate `DB_PASSWORD` and `REDIS_PASSWORD` by updating the secret store, then recreating the affected containers (`docker compose up -d --force-recreate postgres redis app worker horizon scheduler`) during a maintenance window.
- `APP_KEY` must never change after the first production deploy without a coordinated re-encryption of any `encrypted` Eloquent casts.

### 10.7 Deploy process

1. Build the image on CI: `docker build -t gym-platform:$(git rev-parse --short HEAD) .`
2. Push to a registry, then on the target host: `docker compose pull` (or load the built image if not using a registry).
3. Run migrations as a one-shot task: `docker compose run --rm migrate`.
4. Recreate the long-running services with the new image: `docker compose up -d app worker horizon scheduler nginx`.
5. Run a smoke test (a scripted `curl` against `/up` plus a Dusk smoke suite against staging before promoting to production).
6. Roll back by re-tagging the previous image as `latest` (or setting `IMAGE_TAG` to the previous digest) and repeating step 4 — migrations that are additive-only (never destructive in the same deploy as the code that depends on them) make this safe.

For true zero-downtime deploys across multiple hosts, put this Compose stack behind an orchestrator that supports rolling updates (Docker Swarm, or graduate to Kubernetes) once a single-host deployment is no longer sufficient; the container images and service boundaries defined here carry over unchanged.

### 10.8 Local development

Use a `docker-compose.override.yml` (auto-loaded by `docker compose up` alongside `docker-compose.yml`) that:

- Bind-mounts the source tree into `app`/`worker`/`scheduler` for live editing.
- Disables `opcache.validate_timestamps=0` (or sets it to `1`) for the dev build.
- Adds a `vite` service running `npm run dev` for hot module reloading.
- Adds `mailpit` (an open-source SMTP catcher) instead of a real `MAIL_HOST`.
- Skips the `backup` and `minio` services unless specifically testing storage/backup behaviour.
- Exposes `postgres`/`redis` ports to the host for local tooling (a GUI client, `psql`), which the production compose file deliberately does not do (those ports stay internal-only in production).

---

## 11. CI/CD and Quality Gates

Every pull request should run:

1. PHP syntax/lint check (`vendor/bin/pint --test`).
2. Larastan static analysis.
3. Pest unit and feature tests against a real PostgreSQL service container.
4. Policy/authorization test suite.
5. `composer audit` and `npm audit`.
6. Frontend build (`npm run build`) to catch asset compilation errors.
7. Docker image build (validates the Dockerfile still produces a working image).
8. A focused Dusk test for the changed feature where applicable.

Before production deployment:

- Run the full Dusk suite against a staging deployment of the actual Compose stack.
- Run `php artisan migrate --pretend` (or review the migration diff) to confirm no unexpected destructive operation.
- Review new/changed database indexes and query plans for expensive queries.
- Verify all required environment variables are present in the target secret store.
- Confirm the most recent backup restored successfully in a drill.
- Check bundle size and route load-time budgets.
- Run accessibility smoke tests (axe-core via Dusk).
- Use the rollback-tested deploy process from Section 10.7.

Do not merge code that authorizes by hiding a UI element instead of a Policy check, writes financial totals from client/Livewire state without server-side recomputation, weakens tenant scoping, or adds an unreviewed dependency without a documented reason.

---

## 12. Package Decision Table

| Need | Recommended free package | Rule |
|---|---|---|
| Framework | `laravel/framework` | Latest LTS-track release, PHP 8.3+ |
| Interactivity | `livewire/livewire` | Prefer over hand-written JS/API endpoints |
| Auth scaffolding | `laravel/fortify` (optional) | Only if you need headless 2FA/email-verification flows beyond Laravel's defaults |
| Styling | `tailwindcss` | Keep tokens in `tailwind.config.js` |
| Icons | `blade-ui-kit/blade-heroicons` | Accessible, labelled icon buttons |
| PDF export | `barryvdh/laravel-dompdf` | Use for print-style reports |
| QR codes | `simplesoftwareio/simple-qrcode` | Generate server-side, cache the image |
| Queue monitoring | `laravel/horizon` | Required once using Redis queues |
| Testing | `pestphp/pest`, `pestphp/pest-plugin-laravel` | Test behaviour, not implementation |
| Browser E2E | `laravel/dusk` | Run against the Compose stack in CI |
| Static analysis | `larastan/larastan` | Run at a strict level in CI |
| Formatting | `laravel/pint` | Run in CI, auto-fix locally |
| Load testing | `k6` (external tool) | Test realistic tenant sizes against staging |
| Accessibility | `axe-core` (via Dusk) | Add smoke checks to E2E |
| Object storage | `league/flysystem-aws-s3-v3` (bundled via Laravel's `s3` driver) | Use for all user uploads |

Package names are recommendations, not a requirement to install every row. Review current versions, licenses, maintenance, and the Docker image size impact before installation.

---

## 13. Anti-patterns to Avoid

- Business decisions or authorization checks living only in a Blade `@if`.
- Trusting a Livewire component's client-side public properties as an authority for `organisation_id`, `club_id`, or `role`.
- Calling `env()` outside of `config/*.php` files (breaks `config:cache`, which production relies on).
- Storing all tenants' uploaded files only on a single app container's local disk.
- Loading an entire table to implement search instead of a scoped, paginated, indexed query.
- Using offsets instead of cursor pagination for large, frequently-changing lists.
- Using PHP floats for money.
- Overwriting completed payments instead of reversing them.
- Updating a payment and its subscription balance in separate, non-transactional writes.
- Treating `organisation_daily_metrics` rollups as the financial source of truth.
- Sending WhatsApp messages automatically without an explicit admin click.
- Claiming WhatsApp delivery when only a browser link opened.
- Putting passwords, invitation tokens, or private audit details in messages.
- Bind-mounting application code into production containers (breaks the immutable-image, cached-config deploy model).
- Running `php artisan migrate` automatically and concurrently from multiple `app` replicas on boot.
- Writing a database migration without a tested backup and rollback plan.
- Logging personal data or secrets.
- Building reports with unbounded, unpaginated queries or in-memory loops over full tables.
- Allowing unbounded file uploads.
- Ignoring loading, empty, permission, offline, and error states in Livewire components.

---

## 14. Definition of Done for Every Feature

A feature is not complete until it has:

- A domain Action/Service class with clear business invariants.
- Database migrations with the right constraints, indexes, and foreign keys.
- Shared input validation (Form Request or Livewire `rules()`).
- Tenant scoping (global scope) and Policy-based permission checks.
- Loading, success, empty, error, offline, and permission UI states in the Blade/Livewire component.
- Audit behaviour where the action changes important data.
- Idempotency behaviour for retryable writes (unique constraint or `ShouldBeUnique` job).
- Pest unit tests for domain logic.
- Pest feature tests against real PostgreSQL for persistence and policy behaviour.
- A Dusk test for the primary user workflow where the feature is user-facing.
- Query/index review and pagination for lists.
- Accessibility review.
- Observability fields (structured log context) and useful error codes.
- Documentation of any new dependency, migration, or Compose service.

This file and `MEP.md` together are the implementation contract. When technology changes, preserve the business invariants, tenant boundaries, permission semantics, audit history, financial correctness, and the production Docker Compose deployment model.
