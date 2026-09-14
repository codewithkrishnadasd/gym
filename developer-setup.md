# Developer setup

How to run the gym management platform on your own machine, work on it, and
check your work before pushing. Everything here happens inside the `app/`
directory unless it says otherwise.

The stack: PHP 8.4, Laravel 13, Livewire 4 with Alpine.js, Tailwind CSS v4
built by Vite, PostgreSQL 16, Redis 7 (queues, cache, sessions, Horizon).
Tests run with Pest against a second PostgreSQL database.

## 1. Prerequisites

Install these once:

| Tool | Version | Notes |
|---|---|---|
| PHP | 8.4 | with `pdo_pgsql`, `intl`, `mbstring`, `gd`, `redis`, `zip`, `bcmath`, `pcntl` extensions |
| Composer | 2.x | |
| Node.js | 20 | npm comes with it |
| Docker Desktop (or Docker Engine + Compose v2) | current | runs PostgreSQL, Redis and Mailpit for you |
| Git | | |

On macOS with Homebrew: `brew install php@8.4 composer node` and
`pecl install redis`. Docker Desktop from docker.com.

You do not need PostgreSQL or Redis installed natively — the Compose file
provides them and publishes their ports to your machine.

## 2. Get the code and install dependencies

```bash
git clone <repository-url> gym
cd gym/app

composer install
npm install
```

If `npm run dev` later fails with a message about a missing Rolldown native
binding on Apple Silicon, run `npm install --no-save @rolldown/binding-darwin-arm64`.

## 3. Environment file

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and set the database and Redis passwords — Compose reads the same
file to create the containers, so whatever you put here becomes the password
of the PostgreSQL and Redis it starts:

```dotenv
APP_URL=http://localhost:8000
PLATFORM_HOSTNAME=localhost

DB_HOST=127.0.0.1
DB_DATABASE=gym_platform
DB_USERNAME=gym_platform
DB_PASSWORD=secret

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=secret
```

Leave the rest as it is. `.env` is git-ignored.

## 4. Start the supporting services

```bash
docker compose up -d postgres redis mailpit
```

`docker compose up` automatically merges `docker-compose.override.yml`, which
is what publishes PostgreSQL on `5432`, Redis on `6379` and Mailpit on
`8025`/`1025` for local use, and creates the `gym_platform_testing` database
the test suite needs. (Production never uses this override — see
`production-setup.md`.)

Check they are healthy with `docker compose ps`.

## 5. Database

```bash
php artisan migrate
php artisan db:seed
```

`migrate` includes a migration that resets the platform to a single platform
admin (`9539439229` / password `9539439229`). `db:seed` then adds a realistic
demo set on top:

| Who | Where | Login | Password |
|---|---|---|---|
| Platform admin ("root") | `http://localhost:8000` | `919000000001` | `password` |
| FitZone organisation admin | `http://fitzone.test:8000` | `919000000002` | `password` |
| FitZone staff | `http://fitzone.test:8000` | `919000000003` | `password` |
| PowerHouse organisation admin | `http://powerhouse.test:8000` | `919000000002` | `password` |

For a much fuller data set (members, plans, payments, attendance history) on
one organisation, run `php artisan db:seed --class=DemoDataSeeder -- --slug=fitzone`.
It is safe to re-run.

### Tenant hostnames

Every organisation is reached on its own hostname; the platform console lives
on `PLATFORM_HOSTNAME`. Point the demo hostnames at your machine by adding to
`/etc/hosts` (Windows: `C:\Windows\System32\drivers\etc\hosts`):

```
127.0.0.1   fitzone.test powerhouse.test
```

When you create your own organisation in the platform console, add its
hostname the same way.

## 6. Run it

One command starts the PHP server, Vite, Horizon and the log tail together:

```bash
composer run dev
```

Or run them separately in their own terminals:

```bash
php artisan serve          # http://localhost:8000
npm run dev                # Vite, hot-reloads CSS/JS
php artisan horizon        # queue workers (WhatsApp message composition, reports)
php artisan pail           # live log tail
```

Mailpit (any outgoing email) is at `http://localhost:8025`.

If you would rather run PHP inside Docker too, `docker compose up -d` brings up
the whole stack with your working tree bind-mounted; the site is then on
`http://localhost:8000` via the bundled nginx. Native `php artisan serve` is
the usual choice because it is faster to iterate with.

## 7. Day-to-day commands

| Task | Command |
|---|---|
| Run the whole test suite | `php artisan test --compact` |
| Run one test file / test | `php artisan test --compact tests/Feature/TasksTest.php` · `--filter="renews"` |
| Format PHP you changed | `vendor/bin/pint --dirty --format agent` |
| Static analysis (level 8) | `vendor/bin/phpstan analyse --memory-limit=1G` |
| Check every Blade view compiles | `php artisan view:clear && php artisan view:cache` |
| Rebuild front-end assets | `npm run build` |
| New migration / model / test | `php artisan make:migration …` · `make:model … --factory` · `make:test --pest …` |
| Inspect routes | `php artisan route:list --except-vendor` |

Before pushing, all of the first four should be clean. The suite uses the
`gym_platform_testing` database (`phpunit.xml`) and refreshes it on every run,
so your development data is never touched.

## 8. How the project is put together

Read `MEP.md` (product and behaviour specification) and `technology.md`
(architecture decisions) at the repository root before changing anything
substantial. The short version:

- **Multi-tenancy by hostname.** `ResolveTenant` middleware maps the request
  host to an organisation through the `domains` table and binds it as
  `app('tenant')`. Every tenant model uses the `BelongsToOrganisation` trait,
  which scopes queries automatically. Two auth guards: `platform` for the
  console, `web` for organisations.
- **Money is integer minor units** (`App\Support\Money`). Never store floats.
- **Dates in the UI are dd/mm/yyyy** through `x-ui.date-input`; properties and
  the database stay ISO (`Y-m-d`). Compare "today" in the organisation's
  timezone, not the server's.
- **Payments are a lifecycle.** `RecordFeePayment` → `ConfirmFeePayment` /
  `RejectFeePayment` → `ReverseFeePayment`, each in a locked transaction, each
  writing an `AuditEvent`. Do not update balances anywhere else.
- **WhatsApp is never sent by the server.** Actions create a
  `WhatsappActionNotification`; `MessageComposer` renders a template with an
  allow-listed variable set; an operator opens the deep link. When you add a
  notification type or entity, update the CHECK constraints in the
  notifications migration and the enum labels together.
- **Permissions** live in `App\Enums\Permission` with `requires()`
  dependencies; policies use `EvaluatesMembership`. Admins bypass permission
  checks; staff are additionally limited to their assigned clubs.
- **Livewire 4 conventions.** Components are class-based under `app/Livewire`
  with views under `resources/views/livewire`. Use `wire:model` (deferred) by
  default and `.live` only where the UI must react as the user types. Server
  actions open and close `x-ui.modal` with `$this->dispatch('open-modal', 'name')`
  / `close-modal`. Reusable UI lives in `resources/views/components/ui`.

Things that have bitten us, so you do not have to rediscover them:

- `@js()` is not compiled inside an `<x-component>` tag attribute — put
  `x-data` on a plain `<div>` instead.
- `Livewire::test()` does not render nested Livewire children; assert those
  through an HTTP `GET` instead.
- A child Livewire component does not re-read its props when the parent
  re-renders; dispatch an event to it.
- PostgreSQL refuses `FOR UPDATE` together with aggregates — lock the parent
  row (see `InvoiceNumber`).
- New Tailwind classes only appear after `npm run dev`/`build` picks them up;
  if a style is missing, check the build first.

## 9. Laravel Boost and the AI guidelines

`CLAUDE.md` / `AGENTS.md` in `app/` hold the conventions an AI assistant is
asked to follow (Pint before finishing, Pest for tests, no new dependencies
without agreement, and so on). They are equally good reading for people.
Laravel Boost (`boost.json`) exposes the same project knowledge as an MCP
server for editors that support it.

## 10. Troubleshooting

| Symptom | Fix |
|---|---|
| "Organisation not found" on `fitzone.test:8000` | Add the hostname to `/etc/hosts`; make sure the domain exists in the platform console and is active. |
| Login page unstyled / console shows mixed-content errors | You are on `https` but `APP_URL` is `http`. Set `APP_URL` to match how you open the site. |
| `SQLSTATE … database "gym_platform_testing" does not exist` | The test database is created by the postgres container on first start. `docker compose down -v postgres` and `up -d postgres` again, or create it by hand. |
| Changes to a Blade view do not show | `php artisan view:clear`. |
| Queue-driven things (message composition, reports) never happen | Horizon is not running — `php artisan horizon`. |
| `Unable to locate file in Vite manifest` | Run `npm run dev` (or `npm run build`). |
