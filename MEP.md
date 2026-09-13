# Gym Management Platform
## MEP: Product and Engineering Specification

**Status:** Implementation-ready specification
**Primary stack:** Laravel (PHP), Blade templates with Livewire, PostgreSQL, Redis, Docker Compose
**Audience:** Product owner, UI designer, Laravel engineer, QA
**Tenant model:** One application serving multiple organisations, with organisation selected by hostname/domain

---

## 1. Product Summary

The Gym Management Platform is a multi-tenant application for managing gyms, fitness studios, and clubs. Each organisation operates in an isolated tenant boundary and can contain multiple clubs, staff users, members, financial accounts, transactions, attendance records, expenses, and reports.

The application has two operational roles:

- **Organisation admin:** Full access to the organisation and all its clubs, configuration, users, members, finance, reports, and audit history.
- **Staff user:** Access only to assigned clubs and explicitly granted permissions. A staff user may be assigned to multiple clubs.

The system must support organisation-specific terminology. For example, an organisation may call members "students" and staff users "instructors". These labels must appear consistently throughout navigation, headings, filters, forms, empty states, exports, and reports.

---

## 2. Goals and Non-goals

### Goals

1. Provide a fast daily operating system for gym and club staff.
2. Keep every record isolated inside its organisation.
3. Make club, member, staff, attendance, fee, and expense workflows discoverable from list and detail pages.
4. Give admins reliable financial visibility and reporting.
5. Support multiple clubs and staff-to-club assignments without duplicating users.
6. Preserve historical accuracy when people change clubs, plans, names, or statuses.
7. Make the interface usable on desktop, tablet, and mobile.
8. Provide consistent loading, success, empty, validation, permission, and error states.

### Non-goals for the first release

- Automated payment gateway settlement and reconciliation.
- Payroll processing.
- Workout programming or nutrition plans.
- Native mobile applications.
- Multi-currency accounting.
- Public member self-service portal.

These may be added later without changing the tenant model.

---

## 3. Tenant and Domain Resolution

### 3.1 Organisation resolution

Every HTTP request must resolve to exactly one organisation before organisation data is read or written. Resolution happens in a `ResolveTenant` middleware registered at the front of the web middleware group, before session, auth, and route-model-binding middleware that touch tenant data.

Recommended flow:

1. Read the incoming request hostname (`$request->getHost()`).
2. Normalise it to lowercase and remove a trailing dot.
3. Look up an active row in the `domains` table for that hostname, eager-loading its `organisation`.
4. If found and the organisation status is `active`, bind the organisation into the container (`app()->instance('tenant', $organisation)`) and share it with all views.
5. Store the resolved `organisation_id` only in server-side context (session + bound container instance) — never trust a value submitted by the browser.
6. Reject protected routes with a branded "Organisation not found" response until a tenant is resolved.

Example domain mappings:

- `gym.example.com` -> organisation A
- `another-gym.example.com` -> organisation B
- `localhost` / `*.test` -> development organisation selected only through local `.env` configuration or a seeded `domains` row

A domain mapping must have an explicit status: `active`, `pending`, `disabled`, or `reserved`.

### 3.2 Domain rules

- A domain can belong to only one organisation (enforced by a unique index on `hostname`).
- An organisation can have multiple domains.
- Disabled domains must not allow sign-in into the tenant; the `ResolveTenant` middleware treats `disabled` and `pending` the same as "not found" for anonymous visitors.
- Domain resolution must never trust an organisation ID supplied by the browser, request header, query string, or hidden form field.
- A user must have an active `organisation_users` membership row for the resolved organisation before seeing its data.
- If a hostname is unknown, render a branded "Organisation not found" page (a static Blade view) without querying tenant-scoped tables.

### 3.3 Platform operations

Platform-level organisation setup must be performed through a protected internal surface that is architecturally separate from the tenant-facing application — either Artisan console commands run by operators, or a route group under a dedicated hostname (for example `platform.example.com`) guarded by a `platform_admins` table that is entirely separate from `organisation_users`. Platform routes must never be reachable through a tenant domain.

Platform operations:

- Create, update, suspend, and archive an organisation.
- Add or remove domain mappings.
- Assign the first organisation admin.
- Configure defaults and feature flags.
- View tenant health and usage metadata.

All platform operations must write an `audit_events` row with a null `organisation_id` interpreted as a platform-level action, or a dedicated `platform_audit_events` table.

---

## 4. Roles and Permissions

### 4.1 Organisation admin

An organisation admin can:

- View and manage all clubs in the organisation.
- Create, edit, archive, and restore clubs.
- Change organisation name, logo, contact details, timezone, currency, and terminology.
- Invite, deactivate, reactivate, and assign staff users.
- Assign staff users to one or more clubs.
- Configure per-user permissions.
- Create, edit, transfer, archive, and restore members.
- View both member and staff attendance.
- Record, edit, reverse, and export financial transactions.
- Create and manage organisation bank/UPI accounts.
- View all dashboards, reports, and audit logs.

### 4.2 Staff user

A staff user can access only assigned clubs and permitted actions. Permissions should be explicit rather than inferred from the navigation.

Initial permission set (stored as boolean keys inside the `organisation_users.permissions` JSONB column):

- `members.view`
- `members.create`
- `members.edit`
- `members.transfer`
- `attendance.member.mark`
- `attendance.staff.mark`
- `fees.collect`
- `fees.view_own`
- `clubs.view_assigned`
- `reports.view_assigned`

The admin can grant or revoke permissions per user. Finance administration, bank account management, expenses, organisation settings, user management, exports, and global reporting are admin-only in the first release.

### 4.3 Permission evaluation

Every protected action must pass all of these checks, expressed as a Laravel authorization `Policy` or `Gate` and re-checked on the server regardless of what the UI shows:

1. An authenticated user exists (Laravel session guard).
2. The resolved organisation (from `ResolveTenant` middleware) is `active`.
3. The user has an `organisation_users` row for the resolved organisation.
4. That membership's status is `active`.
5. The role or `permissions` map allows the action.
6. The target club is allowed for the user (via `club_user_assignments` or admin override).
7. The target record's `organisation_id` matches the resolved tenant.

The UI (Blade conditionals, Livewire component state) may hide unavailable actions, but Laravel Policies, Form Requests, and database constraints must enforce them independently. Hiding a button is never authorization.

---

## 5. Core Data Model

The data model is a normalised PostgreSQL schema. Every tenant-scoped table carries a required `organisation_id` foreign key (`organisations.id`, `restrict` on delete) so a single database serves all organisations while an Eloquent **global scope** (`OrganisationScope`) automatically constrains every query to the currently resolved tenant. Cross-tenant access requires explicitly bypassing the scope (`withoutGlobalScope`), which must never happen in tenant-facing code paths.

Tables below are described as Laravel migration column lists. Every table also has `id` (unsigned bigint, primary key) unless noted, plus `created_at`/`updated_at` timestamps unless noted.

### 5.1 Organisations

`organisations`

```text
id
name
slug                     unique
logo_path                nullable
status                   enum: active | suspended | archived
timezone
currency_code
locale
contact_email
contact_phone
address                  jsonb
terminology_member_singular
terminology_member_plural
terminology_user_singular
terminology_user_plural
terminology_club_singular
terminology_club_plural
created_by               nullable, references platform_admins.id
created_at
updated_at
```

### 5.2 Domains

`domains`

```text
id
organisation_id          references organisations.id
hostname                 unique, citext or lower(hostname) unique index
status                   enum: active | pending | disabled | reserved
is_primary               boolean, default false
created_at
updated_at
```

Uniqueness on `hostname` is enforced by a database unique index, not application logic alone, so a race between two writers cannot create a duplicate mapping.

### 5.3 Users and organisation membership

Authentication identity and tenant membership are separate tables, mirroring the fact that one person may belong to multiple organisations in the future.

`users` (global authentication identity)

```text
id
name
email                    unique
password                 hashed (bcrypt/argon2id via Laravel's Hash facade)
phone                    nullable
email_verified_at        nullable
remember_token
created_at
updated_at
```

`organisation_users` (tenant-specific membership and authority)

```text
id
organisation_id          references organisations.id
user_id                  references users.id
role                     enum: admin | user
status                   enum: invited | active | suspended | deactivated
permissions              jsonb, map of permission-key => boolean
club_ids                 jsonb array, denormalised read optimisation only
last_login_at            nullable
created_by               nullable, references organisation_users.id
created_at
updated_at

unique (organisation_id, user_id)
```

The `organisation_users` row is the tenant-specific authority for role and permissions — never the `users` row alone, and never a JWT/session claim by itself.

### 5.4 Clubs

`clubs`

```text
id
organisation_id          references organisations.id
name
code
logo_path                nullable
status                   enum: active | archived
phone
email
address                  jsonb
timezone
opening_hours            jsonb
created_by               references organisation_users.id
created_at
updated_at

unique (organisation_id, code)
```

Club codes must be unique within an organisation. Archived clubs remain visible in historical reports.

### 5.5 Club user assignments

`club_user_assignments`

```text
id
organisation_id          references organisations.id
club_id                  references clubs.id
organisation_user_id     references organisation_users.id
permissions_override     jsonb, nullable
status                   enum: active | ended
assigned_at
ended_at                 nullable
created_at
updated_at
```

Use assignment rows when membership history and auditability matter. `organisation_users.club_ids` can remain as a denormalised read optimisation but must not be the only source of truth — always resolve authorization against `club_user_assignments` with `status = active`.

### 5.6 Members

`members`

```text
id
organisation_id          references organisations.id
primary_club_id          references clubs.id, nullable
name
phone
email                    nullable
date_of_birth            nullable
gender                   nullable
photo_path               nullable
address                  jsonb, nullable
emergency_contact        jsonb, nullable
joined_at
status                   enum: active | paused | inactive | archived
notes                    text, nullable
created_by               references organisation_users.id
created_at
updated_at
```

A member has one current primary club in the first release. Transfers must create a separate history row rather than overwriting the past.

### 5.7 Member club history

`member_club_history`

```text
id
organisation_id          references organisations.id
member_id                references members.id
from_club_id             references clubs.id, nullable
to_club_id                references clubs.id
reason                   nullable
changed_at
changed_by               references organisation_users.id
```

Historical attendance and financial transactions must retain their original club ID even if the member later transfers — never cascade-update `club_id` on historical rows.

### 5.8 Plans and subscriptions

`plans`

```text
id
organisation_id          references organisations.id
name
description              nullable
price_minor              integer, smallest currency unit
currency_code
duration_days
session_limit            nullable
status                   enum: active | archived
created_at
updated_at
```

`member_subscriptions`

```text
id
organisation_id          references organisations.id
member_id                references members.id
club_id                  references clubs.id
plan_id                  references plans.id
start_date
end_date
amount_due_minor          integer
amount_paid_minor         integer
status                   enum: active | expired | paused | cancelled
created_at
updated_at
```

### 5.9 Attendance

`attendances`

```text
id
organisation_id          references organisations.id
club_id                  references clubs.id
subject_type             enum: member | user
subject_id               polymorphic reference into members.id or organisation_users.id
attendance_date          date
check_in_at              timestamp, nullable
action                   enum: present | absent | late | excused
marked_by                references organisation_users.id
source                   enum: manual | import
notes                    nullable
created_at
updated_at

unique (organisation_id, club_id, subject_type, subject_id, attendance_date)
```

The composite unique index guarantees at most one attendance record per organisation, club, subject, and date at the database level — a violated unique constraint, not application logic alone, is the source of truth for this invariant. Wrap writes in `DB::transaction()` with `upsert()` or a `firstOrCreate`-then-`update` pattern to avoid race conditions.

Staff users can mark member attendance only when permitted. Admins can mark member and staff attendance.

### 5.10 Financial accounts

`financial_accounts`

```text
id
organisation_id          references organisations.id
name
account_type             enum: bank | upi | cash | other
bank_name                nullable
account_number_last4     nullable
upi_id                   nullable
qr_payload               text, nullable
qr_image_path            nullable
status                   enum: active | archived
created_at
updated_at
```

Do not store full bank credentials or secrets in PostgreSQL. QR payloads must be validated before saving. Display a QR code only for an active account with a valid payload.

### 5.11 Fee payments

`fee_payments`

```text
id
organisation_id                  references organisations.id
club_id                          references clubs.id
member_id                        references members.id
subscription_id                  references member_subscriptions.id, nullable
payer_name
amount_minor                     integer
currency_code
payment_method                   enum: cash | bank_transfer | upi | card | other
financial_account_id             references financial_accounts.id, nullable
transaction_reference            nullable
payment_date
collected_by                     references organisation_users.id
notes                            nullable
confirmation_status              enum: pending_admin_confirmation | confirmed | rejected | reversed
confirmed_by                     references organisation_users.id, nullable
confirmed_at                     nullable
rejection_reason                 nullable
reversal_reason                  nullable
whatsapp_status                  enum: not_sent | ready | opened | skipped | failed
whatsapp_message_template_version nullable
whatsapp_message_snapshot        text, nullable
created_at
updated_at
```

A payment submitted by a staff user is initially a `pending_admin_confirmation` record. It must not increase revenue, mark a subscription as paid, or appear as confirmed income until an organisation admin confirms it. A confirmed payment is a historical financial event. Editing a confirmed payment should be restricted; prefer reversal plus a corrected replacement record. `confirmation_status` is the single canonical payment lifecycle field. The canonical lifecycle is:

```text
pending_admin_confirmation -> confirmed
pending_admin_confirmation -> rejected
confirmed -> reversed
```

Every transition stores the actor, timestamp, and reason where applicable. Staff users can create pending records for permitted clubs but cannot confirm, reject, reverse, or alter the amount of a submitted payment. Admin confirmation must run inside a single `DB::transaction()` (a database-level transaction, PostgreSQL's native ACID guarantee) so the payment, subscription balance, audit event, and confirmation metadata change atomically.

### 5.11.1 Payment confirmation and WhatsApp receipt

When an admin confirms a pending payment, a dedicated Action class (for example `ConfirmFeePaymentAction`) runs inside `DB::transaction()`:

1. Validate the payment, member, subscription, club, amount, and organisation boundary.
2. Mark the payment `confirmed` and store `confirmed_by` and `confirmed_at`.
3. Apply the payment to the related subscription or member balance exactly once.
4. Write an `audit_events` row containing the previous and new lifecycle state.
5. Return a confirmation result with the member's normalised phone number and a generated message preview (rendered server-side in a Livewire component).
6. Offer an explicit "Open WhatsApp" action that opens a browser URL using `https://wa.me/{internationalNumber}?text={urlEncodedMessage}`.

The application must not silently open a new tab or send a message without an admin click. WhatsApp delivery cannot be assumed from opening the URL, so `whatsapp_status` means "link opened" rather than "message delivered". If the member has no valid phone number, show "WhatsApp unavailable" and allow the admin to copy the message instead.

Default message template, using organisation terminology and local date/currency formatting (rendered via a Blade string template or a dedicated PHP formatter class, never string concatenation of raw user input):

```text
Hi {memberName}, your {memberLabel} fee payment of {currency} {amount} has been confirmed by {organisationName}.
Plan: {planName}
Club: {clubName}
Payment date: {paymentDate}
Valid until: {subscriptionEndDate}
Receipt/reference: {transactionReferenceOrPaymentId}

Thank you.
```

The admin may preview and optionally edit the message before opening WhatsApp, but the system must preserve the original generated receipt data in the payment record. Do not include full bank details, authentication information, or sensitive personal data in the message. Use an international phone number without spaces, punctuation, or a leading `+` in the `wa.me` path. If the organisation later adds message templates, store the template version used on the payment for auditability.

### 5.12 Expenses

`expenses`

```text
id
organisation_id                  references organisations.id
club_id                          references clubs.id, nullable
category
amount_minor                     integer
currency_code
expense_date
paid_from_financial_account_id   references financial_accounts.id, nullable
payee                            nullable
description
receipt_path                     nullable
created_by                       references organisation_users.id
status                           enum: completed | reversed
target_type                      enum: organisation | club | member | user, nullable
target_id                        nullable
created_at
updated_at
```

Expenses may be organisation-wide, club-specific, member-specific, or user-specific via the optional `target_type`/`target_id` pair.

### 5.13 Audit events

`audit_events`

```text
id
organisation_id           references organisations.id
actor_user_id             references organisation_users.id
actor_role
action
entity_type
entity_id
before                    jsonb, nullable
after                     jsonb, nullable
metadata                  jsonb
created_at
```

Audit events have no `updated_at` and no application-level update or delete path — enforce append-only behaviour with a database trigger or by simply never exposing an update/delete route or Eloquent method for this model. Do not expose sensitive before/after values to staff users.

### 5.14 WhatsApp action notifications

`whatsapp_action_notifications`

```text
id
organisation_id                  references organisations.id
recipient_type                   enum: member | user
recipient_id
recipient_name
recipient_phone
entity_type                      enum: member | user | fee_payment | subscription | attendance
entity_id
action_type
message_template_version
message_snapshot                 text
status                           enum: ready | opened | skipped | unavailable | failed
created_by                       references organisation_users.id
created_at
opened_by                        references organisation_users.id, nullable
opened_at                        nullable
failure_reason                   nullable

unique (entity_id, action_type, operation_id)
```

This table stores the notification context created after a successful admin action. It is not a delivery log: `opened` means the admin launched the WhatsApp deep link, not that WhatsApp delivered or the recipient read the message. Store only the minimum message data needed for the preview, copy fallback, and audit trail. Never include passwords, one-time invitation tokens, access credentials, full bank details, or private internal audit data.

The unique index on `(entity_id, action_type, operation_id)` is the idempotency guarantee — retries of the same operation hit a unique-constraint violation instead of creating a duplicate notification row. The same completed action may be re-opened from its detail page without creating a second business event.

Supported `action_type` values for the first release:

```text
member_created
member_profile_updated
member_club_transferred
member_plan_created
member_plan_renewed
member_plan_paused
member_plan_cancelled
member_status_changed
member_attendance_marked
fee_payment_confirmed
user_invited
user_profile_updated
user_club_assignment_changed
user_permissions_changed
user_status_changed
user_attendance_marked
```

Attendance notifications should be enabled only when the organisation explicitly wants them, because daily attendance can create high message volume. All other listed admin actions are enabled by default when the recipient has a valid phone number, subject to organisation notification settings.

Organisation notification settings may configure whether action notifications are enabled, which action types are enabled, the default message language, and whether the admin must preview before opening WhatsApp. The admin can always choose `Skip` or `Copy message` for an individual action.

---

## 6. Main Application Areas

### 6.1 Authentication and entry

- Domain-aware sign-in page with organisation branding (resolved by `ResolveTenant` middleware before the login form renders).
- Email/password authentication using Laravel's session guard for the first release.
- Password reset via Laravel's built-in password-broker and signed, expiring email links.
- Invitation acceptance via a signed URL (Laravel signed routes) rather than a raw token stored in plain text.
- Session expiration handling with a graceful re-authentication prompt.
- Suspended-user and suspended-organisation handling (checked in middleware immediately after authentication).
- Route guard while tenant context and permissions are loading — for Livewire, this means the component's `mount()` performs the check before rendering.

### 6.2 Admin dashboard

Show configurable date range, selected club filter, and comparison period.

Core cards:

- Active members.
- New members this period.
- Expiring subscriptions.
- Revenue collected.
- Outstanding fees.
- Expenses.
- Net cash movement.
- Attendance rate.
- Active staff users.
- Today's member attendance.
- Pending fee confirmations requiring admin action.

Supporting panels:

- Revenue and expenses trend.
- Attendance trend.
- Club comparison.
- Recent payments.
- Pending staff-collected payments with collector, club, member, amount, and age.
- Recent expenses.
- Members needing follow-up.
- Staff collection leaderboard.
- Alerts for overdue fees, inactive clubs, and expiring plans.

### 6.3 Staff dashboard

Limit all data to assigned clubs and permissions.

- Today's attendance shortcut.
- Member search.
- Members with expiring plans.
- Fees collected by the current user.
- Submission status for staff-collected fees, including pending and rejected payments.
- Quick actions allowed for the user.
- Assigned club selector when multiple clubs are available.
- Recent activity.

### 6.4 Club management

List view:

- Search by name and code.
- Status filter.
- Member count.
- Active staff count.
- Current-period revenue and attendance summary.

Detail view:

- Overview and KPIs.
- Club settings.
- Assigned users.
- Members.
- Attendance.
- Fees.
- Club-specific expenses.
- Reports.

Club form:

- Name, code, logo, contact information, address, timezone, opening hours.
- Assign existing users during creation.
- Validate unique code within the organisation (Laravel `unique` validation rule scoped by `organisation_id`).

### 6.5 User management

Admin-only list and detail pages:

- Search by name, email, phone, role, status, or club.
- Invite a new user.
- Assign multiple clubs.
- Configure permissions.
- Suspend or deactivate access.
- View member attendance marked by the user.
- View fees collected by the user.
- View audit activity.

When adding a user, club assignment and permissions should be available in the same guided form.

Admin user-action notification rules:

- Inviting a user sends an invitation message containing only the organisation name, role label, assigned club names, and the approved sign-in or invitation link. Never include a password or raw invitation token.
- Changing a user's display name or contact information sends an information-updated message.
- Changing club assignments sends the current assigned club names.
- Changing permissions sends a generic access-updated message rather than an internal permission dump. The user can see exact permissions after signing in.
- Suspending, deactivating, or reactivating a user sends the new account status and support contact.
- Marking user attendance sends a confirmation only when attendance notifications are enabled.

### 6.6 Member management

List view:

- Search by name, phone, email, member ID, or plan.
- Filter by club, status, plan, joining date, expiry date, and outstanding amount.
- Sort by name, recent attendance, expiry, or balance.
- Bulk export for admins.

Member detail:

- Identity and contact information.
- Current club and transfer history.
- Current and past plans.
- Attendance calendar and rate.
- Payment ledger.
- Notes and follow-up status.
- Related expenses when applicable.
- Contextual actions: edit, transfer, renew plan, collect fee, mark attendance, archive.
- After an admin action completes, show the generated WhatsApp message preview with `Open WhatsApp`, `Copy message`, and `Skip` actions.

Admin member-action notification rules:

- Creating a member sends a welcome/profile-created message.
- Changing name, phone, email, address, emergency contact, notes, or status sends an information-updated message without exposing private fields unnecessarily.
- Transferring a member sends the new club and effective date.
- Creating, renewing, pausing, or cancelling a plan sends the plan status, dates, and amount where appropriate.
- Confirming a fee sends the payment and renewal/receipt details defined in the payment workflow.
- Marking attendance sends an attendance confirmation only when the organisation has enabled attendance notifications.
- Archiving a member sends a neutral account-status message and must not expose internal reasons unless the admin includes them in an approved message field.

### 6.7 Attendance

Provide separate tabs or modes for:

- Member attendance.
- Staff/user attendance.

Daily workflow:

1. Select date and club.
2. Search or filter the roster.
3. Mark present, absent, late, or excused.
4. Save with clear progress feedback (Livewire `wire:loading` states).
5. Show last updated time and marker.
6. Allow corrections according to permission.

Useful features:

- "Mark all present" with confirmation.
- Unmarked count.
- Search by name or phone.
- Bulk update.
- Attendance history.
- Date navigation.
- Mobile-friendly large tap targets.

### 6.8 Fees and payments

Admin view:

- Payment ledger across the organisation.
- Separate confirmation queue for staff-submitted payments.
- Filters by date, club, member, collector, payment method, account, plan, lifecycle status, and confirmation age.
- Collection totals, confirmed revenue, pending collections, rejected collections, and outstanding fees.
- Payment detail with immutable history.
- Confirmation workflow showing payment details, collector, member contact, subscription context, and validation warnings.
- Reject workflow requiring a reason and leaving the original submission in the audit trail.
- Reversal workflow requiring a reason.
- After confirmation, a WhatsApp message preview and explicit "Open WhatsApp" action.
- Copy-message fallback when the member has no usable WhatsApp number or the admin chooses not to open WhatsApp.

Permitted staff workflow:

- Search member.
- Select plan or invoice context.
- Enter amount and payment method.
- Optionally select receiving account if allowed by admin policy.
- Add transaction reference and note.
- Submit the fee for admin confirmation; show a clear pending status and do not claim that the payment is confirmed.
- Allow the staff user to view the submission and its decision, but not modify the amount after submission.

Confirmation workflow:

1. Admin opens the pending confirmation queue.
2. Admin reviews member, club, plan, amount, payment method, collector, account, date, and reference.
3. Admin confirms or rejects. Rejection requires a reason.
4. On confirmation, a database transaction updates the subscription and writes the audit event atomically.
5. The admin sees the generated WhatsApp message and chooses "Open WhatsApp", "Copy message", or "Skip".
6. The payment detail records the WhatsApp action status and remains available in the member ledger.

General admin-action workflow:

1. The admin submits a valid member or user action through a Form Request-validated Livewire component or controller.
2. A database transaction completes the business update and audit event.
3. The response includes the completed action ID and a generated notification snapshot when the action is eligible.
4. The UI displays a success state followed by a WhatsApp action panel for the affected person.
5. The admin selects `Open WhatsApp`, `Copy message`, or `Skip`.
6. The UI records the chosen link action against the notification record and never presents opening WhatsApp as delivery confirmation.

For browser compatibility, the application should attempt to open WhatsApp from the same user gesture where possible. If a popup blocker or browser policy prevents that after the asynchronous save, keep the action panel visible with a normal clickable fallback link and copy button.

Default action message templates:

```text
Member created:
Hi {memberName}, your profile has been created with {organisationName}.
Club: {clubName}
Member ID: {memberId}
Please contact us if any information needs to be corrected.

Member information updated:
Hi {memberName}, your information with {organisationName} was updated successfully.
Updated item: {safeChangedItemLabel}
Club: {clubName}

Club transfer:
Hi {memberName}, your club with {organisationName} has been changed.
New club: {newClubName}
Effective date: {effectiveDate}

Plan created or renewed:
Hi {memberName}, your {memberLabel} plan with {organisationName} is {planAction}.
Plan: {planName}
Club: {clubName}
Valid from: {startDate}
Valid until: {endDate}

User access update:
Hi {userName}, your access to {organisationName} was updated.
Assigned clubs: {clubNames}
Please sign in to view your current access.

Account status change:
Hi {recipientName}, your account status with {organisationName} is now {safeStatusLabel}.
Please contact {supportContact} if you need assistance.
```

`safeChangedItemLabel` and `safeStatusLabel` must come from an allowlisted set of display labels defined in PHP (an enum or const map), never raw field names, permission codes, internal reasons, or before/after values. The final rendered text must be stored in `message_snapshot` before the admin opens or copies it.

### 6.9 Financial accounts

Admin-only:

- List active and archived accounts.
- Add bank, UPI, cash, or other account.
- Store display name, bank name, optional UPI ID, and QR payload.
- Show a QR code in a focused account detail view (generated server-side, e.g. with a PHP QR-code library, and cached as an image).
- Copy UPI ID and download/print QR image.
- Archive an account without changing historical transactions.
- Show money received and spent by account.

The QR screen must clearly show the account name, UPI ID where available, and a fallback payment instruction.

### 6.10 Expenses

Admin-only:

- Record organisation, club, member, or user-related expense.
- Select optional funding account.
- Attach receipt image or PDF to object storage.
- Categorise and describe the expense.
- Edit draft records; reverse completed records with reason.
- Filter by club, category, target, account, and date.

### 6.11 Reports and exports

Reports must respect selected date range, club scope, and user permissions.

Required reports:

- Member growth and churn.
- Active, inactive, paused, and archived members.
- Plan sales, renewals, expiries, and outstanding fees.
- Daily, weekly, and monthly attendance.
- Attendance by member, club, plan, and staff marker.
- Staff collection totals and payment-method split.
- Pending, confirmed, rejected, and reversed payment totals.
- Admin confirmation turnaround time and pending-payment ageing.
- Revenue by club, plan, collector, and account.
- Expense totals by category, club, target, and account.
- Net cash movement.
- Member payment ledger.
- Account statement.
- Club performance comparison.

Payment reporting rules:

- Staff submissions appear in a pending collection view immediately after submission.
- Only confirmed payments contribute to revenue, paid subscription amounts, account statements, and net cash movement.
- Rejected payments remain searchable for audit and staff follow-up but do not contribute to financial totals.
- Reversed payments remain in historical reports with the reversal clearly shown and excluded from current confirmed totals.

Exports:

- CSV for tables (streamed, not loaded fully into memory for large exports).
- PDF summary for printable reports (rendered server-side).
- Include organisation name, selected filters, date generated, and timezone.
- Never export records outside the user's permission scope; enforce the same query scoping used for on-screen lists.

---

## 7. Navigation and Interaction Rules

Use a persistent shell (a Blade layout component) with:

- Organisation logo and terminology-aware name.
- Club selector where the current page supports club scope.
- Role-aware navigation.
- Global search or fast search.
- Notifications/alerts.
- User menu and sign out.

Every list row should provide a direct route to the entity detail page. Every detail page should expose only actions allowed for the current user. After create, edit, transfer, payment, expense, or attendance actions, redirect to the relevant detail or list state and preserve filters where practical (Livewire's `redirectRoute` or a full navigation with query-string filters preserved).

Recommended route shape:

```text
/dashboard
/clubs
/clubs/{club}
/users
/users/{user}
/members
/members/{member}
/attendance/members
/attendance/users
/finance/payments
/finance/payments/pending-confirmation
/finance/payments/{payment}
/finance/expenses
/finance/accounts
/reports
/settings/organisation
/settings/terminology
/audit-log
```

Every route is registered under a `tenant` middleware group (`ResolveTenant`, `auth`, permission checks) in `routes/web.php`. Route-model binding should be scoped so that `{club}`/`{member}`/`{payment}` bindings resolve only within the current tenant (a custom binding resolver or a global Eloquent scope on the underlying models makes an out-of-tenant ID resolve to a 404, not a 403 — avoiding tenant existence leakage).

---

## 8. Application, Database, and Deployment Architecture

### 8.1 Request/response responsibilities

- Controllers and Livewire components render pages and handle form submissions.
- Blade views render server-side HTML; Livewire adds interactivity (live validation, partial re-renders, polling) without a separate JSON API layer.
- Use Laravel Form Requests for validation and authorization on every write.
- Use database timestamps (`now()`/`CURRENT_TIMESTAMP`) for authoritative event times, not client-supplied dates for financial or attendance events.
- Use optimistic UI (Livewire's `wire:loading.remove`/`wire:target`) only for reversible low-risk actions such as attendance toggles.
- Never make security decisions based only on client-side (Alpine.js/Blade conditional) state.

### 8.2 Business logic and background processing

Use dedicated Action/Service classes (plain PHP classes, one responsibility each, invoked from controllers or Livewire components) for sensitive workflows, and Laravel **queued jobs** for anything that should not block the request:

- Create organisation and domain mappings (platform operation).
- Invite users (queues an email notification).
- Transfer a member.
- Submit a staff-collected fee for admin confirmation.
- Confirm or reject a pending fee in an idempotent database transaction.
- Complete admin member/user actions and create an idempotent WhatsApp notification snapshot.
- Record WhatsApp deep-link actions without treating them as delivery receipts.
- Reverse a payment or expense.
- Generate receipts and reports (queued job for anything beyond a few hundred rows; the UI polls or is notified via Livewire when the job finishes).
- Create audit events.
- Validate unique organisation/domain/club constraints (database unique index plus a friendly validation message).
- Generate or validate QR payloads where required.
- Run scheduled metric rollups (Laravel's task scheduler, `php artisan schedule:run`, driving `App\Console\Commands`).

The synchronous business update, audit event, and notification snapshot must complete inside one database transaction before any WhatsApp deep-link is offered to the admin. Opening WhatsApp is always an explicit client action after the business action succeeds. The application must not claim delivery merely because the deep link opened. If message templates or receipt snapshots are generated server-side, save the template version and rendered message snapshot on the notification record, and save payment-specific copies on the payment record.

### 8.3 Authorization enforcement

Enforce with Laravel Policies (one per model: `MemberPolicy`, `FeePaymentPolicy`, `ClubPolicy`, etc.) registered against Eloquent models, checked both in route middleware (`can:` middleware or `$this->authorize()`) and inside Livewire component methods before any mutation:

- Authenticated access only.
- Tenant equality between the resolved organisation and the record's `organisation_id` (the `OrganisationScope` global scope plus an explicit policy check as defense in depth).
- Active membership status.
- Club assignment for staff users.
- Admin-only settings, finance, account, expense, and audit operations.
- Staff users may create only `pending_admin_confirmation` fee records for assigned clubs and permitted actions.
- Only admins may confirm, reject, reverse, or change the lifecycle of a fee payment.
- The application must never allow a client request to mark a pending payment as confirmed or update a subscription balance directly without going through the confirmation Action class.
- Only admins may create action notifications for member or user changes; a notification must reference a completed action in the same organisation.
- A user or member may only receive a notification generated for their own record, and only a valid normalised phone number may be placed in a WhatsApp URL.
- Immutable or restricted updates to financial records (enforced by omitting update routes/methods for confirmed payments, not just UI hiding).
- No client-created organisation membership escalation (the `role`/`permissions` columns on `organisation_users` are never mass-assignable from a plain user-facing form).

Write a Pest/PHPUnit policy test for both the allowed and denied case for every policy method and every tenant/club boundary.

### 8.4 Query and performance requirements

- Paginate every potentially large list (Laravel's cursor pagination, `cursorPaginate()`, for large or frequently-changing lists; standard `paginate()` elsewhere).
- Add database indexes for every common filter combination (composite indexes matching real `WHERE`/`ORDER BY` clauses, verified with `EXPLAIN ANALYZE`).
- Keep dashboard aggregation rows (`organisation_daily_metrics`) or scheduled summaries for expensive reports.
- Avoid loading entire tables into PHP memory; use `chunk()`/`lazy()` for batch operations and streamed exports.
- Debounce search input (Livewire's `wire:model.live.debounce.400ms`) and require a minimum search length for remote search.
- Use denormalised counters only where their update path is reliable and auditable (e.g. updated inside the same transaction as the source-of-truth write).
- Prefer scheduled rollups (queued jobs on the scheduler) over live aggregation for large tenants.
- Exclude pending and rejected payments from confirmed revenue, subscription payment totals, account statements, and net cash movement; expose them in dedicated pending/rejected metrics.
- Use the database unique-index idempotency pattern (Section 5.9, 5.14) for fee submission, confirmation, rejection, reversal, and subscription balance updates.

Suggested rollup table (Section 5's `organisation_daily_metrics`):

```text
organisation_id
metric_date
total_members
new_members
active_members
attendance_present
revenue_collected_minor
expenses_recorded_minor
net_movement_minor
by_club                  jsonb
updated_at
```

Rollups are reporting accelerators, not the source of truth.

### 8.5 Deployment topology

Production runs as a set of Docker Compose services: an application container (PHP-FPM running Laravel behind nginx), a queue worker container, a scheduler container, PostgreSQL, and Redis. Full service definitions, the Dockerfile, and operational detail live in `technology.md` Section 10. This specification only requires that:

- The application is stateless across requests — no in-memory session or tenant state that would break horizontal scaling.
- File uploads (photos, receipts, QR images) go to object storage (S3-compatible), not the local container filesystem, so any app replica can serve them.
- Database migrations run as an explicit deploy step, never automatically on container boot in a way that could run concurrently from multiple replicas.

---

## 9. UI, Accessibility, and Feedback States

### 9.1 Design direction

Use a calm, professional operations interface with strong information hierarchy. The design should feel appropriate for repeated daily use rather than like a marketing site.

- Responsive layout for desktop, tablet, and mobile.
- Clear typography and compact data tables.
- Distinct status colors with text labels, not color alone.
- High-contrast controls and visible focus states.
- Consistent spacing and predictable page actions.
- Avoid deeply nested cards; use full-width sections and focused panels.

### 9.2 Required states

Every asynchronous operation (every Livewire action) must have:

- Initial loading skeleton or progress indicator.
- Disabled action state while submitting (`wire:loading.attr="disabled"`).
- Success confirmation.
- Inline validation errors (Livewire's real-time validation, backed by the same Form Request rules used server-side).
- Recoverable failure message with retry.
- Empty state with a relevant next action.
- Permission-denied state.
- Offline or connectivity state where relevant.

Use a consistent loader system:

- Page loading: skeleton layout.
- Table loading: row skeletons.
- Button action: compact spinner plus preserved button label where possible (`wire:loading` targeted at the specific action).
- Long report generation: queue the job, show a progress state, and notify on completion (Livewire polling or a broadcast event over Laravel Echo/Reverb).
- Attendance bulk save: progress count and safe retry.
- Payment confirmation: disable confirm/reject controls while the transaction runs, then show the WhatsApp preview and action result.
- WhatsApp opening: show a non-blocking status such as "WhatsApp opened" and retain copy/skip actions; never display this as delivery confirmation.
- Admin member/user actions: after the save succeeds, show a compact success state and notification panel with recipient, action summary, message preview, `Open WhatsApp`, `Copy message`, and `Skip`.
- Notification preparation: show a loading state while the message is generated and a fallback when the recipient has no valid phone number.
- Popup-blocked WhatsApp: preserve a normal clickable link and copy action instead of losing the completed action result.

### 9.3 Accessibility baseline

- Keyboard navigable forms and tables.
- Labels for all inputs.
- Accessible dialog focus management (Alpine.js-driven modals with proper focus trapping).
- Screen-reader announcements for success and errors (`aria-live` regions updated by Livewire).
- Minimum 44px touch target for important mobile controls.
- Do not rely on hover-only actions.
- Date and currency formatting must respect locale and timezone (PHP's `IntlDateFormatter`/`NumberFormatter` or Carbon with the organisation's timezone).

---

## 10. Validation and Business Rules

- Names are required and trimmed.
- Phone numbers are normalised before search and duplicate detection (a shared PHP normalisation helper used in Form Requests and search queries alike).
- Email addresses are lowercased for identity comparisons.
- Club code is unique inside an organisation (database unique index plus a scoped Laravel validation rule).
- A member transfer requires a destination club and optional reason.
- Historical attendance and payments retain their original club context.
- Staff-collected fees begin as `pending_admin_confirmation` and do not count as confirmed revenue until an admin confirms them.
- Confirmation and subscription balance updates must be atomic (`DB::transaction()`) and idempotent (unique-index-backed).
- Rejection requires an admin reason and must preserve the original submitted payment.
- A confirmed payment may open WhatsApp only through an explicit admin action; opening the link is not proof of delivery.
- WhatsApp numbers must be normalised to international format before a deep link is generated.
- Eligible admin member/user actions must complete successfully before a notification is created or shown.
- Notification creation and the underlying admin action must be idempotent; retries must not repeat the member/user mutation.
- Notifications must use the affected person's current approved contact number and must not expose credentials, invitation tokens, full bank details, or private audit data.
- If a phone number is missing or invalid, the action still completes and the admin receives a copyable message or an explicit unavailable state.
- Message templates must use organisation terminology, local date/time and currency formatting, and the organisation's configured language where supported.
- Admins may skip a notification without rolling back the completed business action.
- Completed payment amounts must be positive.
- Payment reversal requires a reason and authorised user.
- Expenses must have a category, amount, date, and description or payee.
- Archived clubs cannot receive new attendance or payments unless an admin explicitly selects a historical correction flow.
- Deactivated users cannot sign in or perform new actions (checked at authentication and again per-request in middleware).
- Terminology changes affect display labels but never database field names or audit history.
- Organisation timezone controls daily attendance dates and report boundaries.

---

## 11. Observability, Reliability, and Operations

Track:

- Authentication failures.
- Permission denials.
- Queued job errors and latency (Laravel Horizon dashboards).
- PostgreSQL read/write volume and slow queries.
- Report generation duration.
- Failed file uploads.
- Tenant resolution failures.
- Attendance and payment write conflicts (unique-constraint violations).
- Payment confirmation, rejection, reversal, and WhatsApp-link action failures.
- Admin member/user action notification creation, message-generation, popup-block, and deep-link action failures.

Use structured logs (Laravel's logging channels, JSON-formatted for aggregation) with organisation ID, user ID, operation, and request ID. Do not log passwords, full bank details, payment secrets, or unnecessary personal data.

Reliability requirements:

- Policy and authorization tests run in CI.
- Feature/integration tests against a real PostgreSQL test database (Laravel's `RefreshDatabase` trait).
- Scheduled `pg_dump` backups and a tested restore procedure for production.
- Soft-delete or archive (Eloquent `SoftDeletes` where "delete" actually means "archive") for operational entities.
- Idempotent queued jobs for payment, transfer, and report jobs (jobs are safe to retry after a timeout).
- Clear migration strategy for schema changes (Laravel migrations, reviewed and tested in staging before production).

---

## 12. Delivery Plan

### Phase 0: Foundation

- Laravel application skeleton, routing, and Blade layout shell.
- Docker Compose environment for local development (app, PostgreSQL, Redis, mail catcher) mirroring production topology.
- Authentication and tenant/domain resolution middleware.
- Organisation membership and permission context (policies, Form Requests).
- Base migrations, model factories, validation rules, error/exception handling, and the shared loader/state components.
- CI pipeline running the automated test suite against a real PostgreSQL service container.

### Phase 1: Daily operations

- Organisation settings and terminology.
- Club CRUD and club selector.
- User invitation, assignment, and permission management.
- Member CRUD and member transfer.
- Member attendance.
- Staff attendance for admins.
- Admin and staff dashboards.

### Phase 2: Plans and collections

- Plans and subscriptions.
- Fee collection.
- Staff payment submission queue with pending status.
- Admin confirmation and rejection workflow.
- Generic post-action WhatsApp notification panel for member and user actions.
- Payment ledger and receipts.
- Staff collection view.
- Outstanding fee reports.

### Phase 3: Finance

- Financial accounts.
- UPI fields and QR display.
- Confirmed-payment WhatsApp message preview and deep-link action.
- Member/user action message templates, notification snapshots, copy fallback, and popup-blocked link handling.
- Expenses and receipt uploads (object storage).
- Account statements.
- Revenue, expense, and net movement reports.
- Audit log.

### Phase 4: Scale and polish

- Scheduled metric rollups (queue + scheduler).
- Advanced filters and exports.
- Saved report filters.
- Bulk import with validation preview.
- Performance tuning for large tenants (query plans, indexes, caching).
- Accessibility audit and browser-based regression suite.
- Production Docker Compose hardening: resource limits, health checks, zero-downtime deploy process, and backup verification.

---

## 13. Acceptance Criteria

The first production-ready release is complete when:

1. An operator can create an organisation and map one or more domains to it.
2. A user visiting a mapped domain sees only that organisation's branded application.
3. An organisation admin can create clubs and assign existing users during club creation.
4. An admin can invite a user, assign multiple clubs, and set permissions in one flow.
5. A member can be created, viewed, edited, archived, and transferred between clubs.
6. Transfer history is preserved and historical records keep their original club context.
7. Staff can mark member attendance only for permitted clubs.
8. Admins can mark both member and staff attendance.
9. Users see separate dashboards and navigation based on role and permission.
10. Admins can record payments, identify the collector, and select a receiving account.
11. Staff-submitted payments appear in the admin confirmation queue with collector, member, club, plan, amount, and submission time.
12. Staff cannot confirm, reject, reverse, or alter the amount of their submitted payment.
13. Admin confirmation atomically updates the payment lifecycle, subscription balance, and audit event, and repeated confirmation requests do not double-apply the payment.
14. Admin rejection requires a reason and preserves the submitted record for audit and staff visibility.
15. Only confirmed payments affect revenue, paid subscription totals, account statements, and net cash movement.
16. After confirmation, admins can preview the generated renewal/receipt message and explicitly open WhatsApp using a correctly encoded international-number deep link.
17. WhatsApp-unavailable members receive a copy-message fallback, and the UI never claims that a message was delivered merely because WhatsApp opened.
18. After an eligible admin member/user action completes, the affected person receives a generated message preview with `Open WhatsApp`, `Copy message`, and `Skip` actions.
19. Name, contact, club transfer, plan, status, invitation, assignment, permission, and configured attendance actions use the correct action-specific message without exposing secrets or private audit data.
20. Admins can create and archive bank/UPI accounts and display a usable QR code.
21. Admins can record expenses against an organisation, club, member, or user and optionally select a funding account.
22. Dashboards and reports filter by date and club and never expose unauthorised data.
23. Financial corrections use reversal/audit history rather than silently destroying completed records.
24. All important async actions show loading, success, validation, empty, error, and permission states.
25. Laravel Policies, Form Requests, and database constraints prevent cross-organisation and cross-club access even when the client is modified.
26. The core workflows pass automated tests against a real PostgreSQL database in CI.
27. The application remains usable on mobile widths without losing access to primary actions.
28. The production Docker Compose stack starts from a clean checkout with a documented set of environment variables and passes a smoke test.

Payment-specific automated tests must cover:

- Staff submission creates a pending record and does not increase revenue.
- A staff user cannot confirm or reject any payment.
- An admin can confirm only a payment in the resolved organisation and permitted club scope.
- Duplicate confirmation requests apply the subscription credit once.
- Rejection requires a reason and does not change the subscription balance.
- Reversal requires a reason and does not erase the original confirmation.
- Missing, malformed, and international phone numbers produce the correct WhatsApp availability state.
- Message text is URL encoded correctly, contains the selected plan and payment details, and excludes sensitive bank data.
- Opening WhatsApp updates only the link-action status and never delivery status.
- Admin member creation creates one idempotent notification snapshot with the correct recipient and message.
- Admin member profile edit and club transfer create the correct action type and do not include unnecessary private fields.
- Admin plan renewal and fee confirmation include the correct plan, amount, dates, club, and reference.
- Admin user invitation never includes a password or raw invitation token.
- Admin user assignment, permission, and status changes create safe generic messages.
- Non-admin users cannot create notifications for admin-only actions.
- Missing phone numbers, malformed numbers, popup blockers, skipped messages, and copy fallback are handled without rolling back the completed action.
- Repeating a client request or refreshing the detail page does not duplicate the underlying action or notification snapshot.

---

## 14. Recommended Build Conventions

- PHP with `declare(strict_types=1)` in every file.
- Shared validation rules expressed as reusable Laravel Form Request classes and custom `Rule` objects, not duplicated across controllers/components.
- Action/Service classes for business operations rather than fat controllers or fat Livewire components.
- Central permission constants (a PHP enum or const class) and Policy classes as the single source of truth for authorization.
- UTC timestamps in storage (PostgreSQL `timestamptz`), organisation timezone for display and date boundaries (Carbon's timezone conversion at the presentation layer only).
- Cursor pagination for large lists.
- Small, composable Blade components and Livewire components organised by feature.
- No direct trust in client-provided organisation IDs, roles, or totals — every value is re-derived server-side from the authenticated session and resolved tenant.
- Every new feature must document its database migrations, indexes, policies, loading states, and tests.

This specification is the source of truth for the initial implementation. Any feature that changes tenant boundaries, financial history, or permission semantics must update this document and the policy/authorization test suite together.
