# Production setup

How to put the gym management platform on a server, keep it running, and roll
out updates. It assumes one Linux server (Ubuntu 22.04/24.04 or similar) with
Docker, fronted either by Cloudflare or by the server's own nginx with a
Let's Encrypt certificate. All commands run from the `app/` directory of the
checkout unless stated otherwise.

What runs where:

```
Internet ──► Cloudflare (optional) ──► host nginx :80/:443
                                         │  proxies to 127.0.0.1:801
                                         ▼
                              docker compose stack (gym-platform)
                                nginx ── app (php-fpm) ── postgres
                                         worker ×2, horizon,   redis
                                         scheduler, backup     (minio, optional)
```

The stack publishes **HTTP only**, on a loopback port. TLS is terminated in
front of it. Everything inside is on a private Docker network.

## 1. Server prerequisites

- 2 vCPU, 4 GB RAM, 40 GB disk is comfortable for a few organisations.
- Docker Engine 24+ and Docker Compose v2 (`docker compose version`).
- nginx installed on the host (`apt install nginx`) — it is the public face.
- certbot with the nginx plugin if the host terminates TLS
  (`apt install certbot python3-certbot-nginx`). Not needed behind Cloudflare
  with an origin certificate or "Flexible" mode.
- Git.
- DNS: an `A` record for the platform hostname (e.g. `gym.example.com`) and
  one for every organisation domain, all pointing at this server.

Ports 80 and 443 open to the world; nothing else. PostgreSQL and Redis are not
published in production.

## 2. Get the code

```bash
sudo mkdir -p /srv/gym && sudo chown $USER /srv/gym
git clone <repository-url> /srv/gym
cd /srv/gym/app
```

## 3. Production `.env`

```bash
cp .env.example .env
```

Then edit `.env`. These are the values that matter in production; leave the
others at their defaults.

```dotenv
APP_NAME="Gym Management Platform"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://gym.example.com        # scheme matters — see §7
APP_KEY=                               # generated in the next step

PLATFORM_HOSTNAME=gym.example.com      # the platform-admin console
HTTP_PORT=127.0.0.1:801                # where the stack listens; host nginx proxies here

LOG_LEVEL=warning

DB_CONNECTION=pgsql
DB_HOST=postgres                       # container name — NOT 127.0.0.1
DB_PORT=5432
DB_DATABASE=gym_platform
DB_USERNAME=gym_platform
DB_PASSWORD=<long random>

REDIS_HOST=redis                       # container name
REDIS_PASSWORD=<long random>
SESSION_DRIVER=redis
CACHE_STORE=redis
QUEUE_CONNECTION=redis

FILESYSTEM_DISK=local                  # organisation logos live on the app-storage volume

# Optional: WhatsApp numbers (digits only, international) allowed into /horizon
PLATFORM_HORIZON_PHONES=919876543210

# Optional: how long password-reset links stay valid (minutes)
PASSWORD_RESET_LINK_MINUTES=60
```

Generate the application key **before** the first start and never change it
afterwards — it encrypts sessions and the per-organisation S3 credentials in
the database:

```bash
docker run --rm -v "$PWD":/app -w /app composer:2.8 composer install --no-dev --ignore-platform-reqs --no-scripts -q
docker run --rm -v "$PWD":/app -w /app php:8.4-cli-alpine php artisan key:generate --show
```

Paste the printed `base64:…` value into `APP_KEY`. (Or generate it once the
stack is running with `docker compose -f docker-compose.yml exec app php
artisan key:generate --show` and restart — the first method avoids a restart.)

Passwords: `DB_PASSWORD` and `REDIS_PASSWORD` are read by Compose to create the
containers, so set them before the first `up`. Changing them later means
changing the container data too.

## 4. Build and start the stack

Always pass `-f docker-compose.yml` in production. Without it, Compose also
loads `docker-compose.override.yml`, which is the **development** overlay: it
bind-mounts the source tree, publishes the database to the network and
disables the production entrypoint.

```bash
docker compose -f docker-compose.yml build
docker compose -f docker-compose.yml up -d
docker compose -f docker-compose.yml run --rm migrate
```

`migrate` creates the schema and a single platform administrator:

> Phone **9539439229**, password **9539439229** — change the password before
> anything else. There is no self-service screen for platform admins, so set
> it from the command line (the `password` cast hashes it for you):
>
> ```bash
> docker compose -f docker-compose.yml exec app php artisan tinker --execute \
>   'App\Models\PlatformAdmin::where("phone", "919539439229")->firstOrFail()->update(["password" => "a-long-new-password"]);'
> ```

Check everything is up:

```bash
docker compose -f docker-compose.yml ps
curl -s -o /dev/null -w '%{http_code}\n' -H 'Host: gym.example.com' http://127.0.0.1:801/up   # 200
```

Services in the stack and what they do:

| Service | Role |
|---|---|
| `nginx` | Serves static files and hands PHP to `app`. Listens on `HTTP_PORT` only. |
| `app` | php-fpm. On start it rebuilds config/route/view caches from `.env` and syncs `public/` for nginx. |
| `worker` ×2 | `queue:work` on the `default`, `notifications`, `reports` queues. |
| `horizon` | Queue supervisor and dashboard (`/horizon`, gated by `PLATFORM_HORIZON_PHONES`). |
| `scheduler` | Runs `schedule:run` every minute (prunes expired password-reset links, etc.). |
| `migrate` | One-shot; run it after every deploy. |
| `postgres`, `redis` | Data. Persisted in named volumes `postgres-data`, `redis-data`. |
| `backup` | Nightly `pg_dump` into `./backups/`, keeps 14 days. |
| `minio` | Optional S3-compatible storage; only with `--profile self-hosted-storage`. |

## 5. Host nginx and TLS

The ready-made site file is `docker/nginx/gym.nginx.conf`. It proxies to
`127.0.0.1:801`, forwards the real scheme, and carries the list of hostnames.

```bash
sudo cp docker/nginx/gym.nginx.conf /etc/nginx/sites-available/gym.example.com
sudo sed -i 's/gym.tutomento.com/gym.example.com/g' /etc/nginx/sites-available/gym.example.com
sudo ln -s /etc/nginx/sites-available/gym.example.com /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

**TLS at the host (no Cloudflare, or Cloudflare "Full"):**

```bash
sudo certbot --nginx -d gym.example.com
```

certbot adds the `listen 443 ssl` block and the redirect itself. Do not add a
443 block by hand before the certificate exists — `nginx -t` would fail and
block the reload certbot needs.

**TLS at Cloudflare (orange cloud):** nothing more to do on the host; the site
file already trusts `X-Forwarded-Proto`. Set SSL mode to *Full* if you also
ran certbot, otherwise *Flexible*.

Whichever you choose, `APP_URL` must start with `https://` — see §7.

## 6. First sign-in and adding an organisation

1. Open `https://gym.example.com` and sign in with the platform admin above
   (after changing its password as shown in §4).
2. **Organisations → New** — name, timezone, currency, contact details, and
   the first organisation admin (name, WhatsApp number, initial password). A
   default "Cash" account, default expense categories and terminology are
   created for it.
3. Open the organisation → **Domains** → add its hostname (e.g.
   `citygym.example.com`) and mark it primary.
4. Under the organisation's **Members**, **Reset password** issues a one-time
   link (valid `PASSWORD_RESET_LINK_MINUTES`) to send the admin on WhatsApp so
   they set their own password and are signed in automatically.

Every organisation domain needs three things, in this order:

| Where | What |
|---|---|
| DNS | `A` record → this server (or proxied through Cloudflare). |
| Host nginx | Add the hostname to the `server_name` list under "ORGANISATION DOMAINS" in the site file, then `sudo nginx -t && sudo systemctl reload nginx`. If TLS is on the host, extend the certificate: `sudo certbot --nginx --cert-name gym.example.com -d gym.example.com -d citygym.example.com` (repeat every name already covered). |
| Platform console | Organisation → Domains. Until this step the app answers "Organisation not found" for the host. |

The organisation's own admin then completes set-up inside the app: logo,
clubs (with admission fee and per-plan discounts), plans, financial accounts,
message templates, reference prefixes, task categories, and optionally
per-organisation S3 buckets for member/staff documents (Settings → Storage;
credentials are stored encrypted with `APP_KEY`).

## 7. Why `APP_URL` and the forwarded scheme matter

The application builds every asset URL from the scheme it believes the
request used. If it thinks the request was plain HTTP while the visitor is on
HTTPS, the browser blocks the stylesheet and JavaScript as mixed content and
the site appears unstyled and dead. Two things keep this right:

- `APP_URL=https://…` makes the app force `https` for generated URLs.
- The host nginx forwards `X-Forwarded-Proto` (already in the site file).

To verify:

```bash
curl -s -H 'Host: gym.example.com' -H 'X-Forwarded-Proto: https' http://127.0.0.1:801/ | grep -o 'https://[^"]*app-[^"]*\.css' | head -1
sudo nginx -T | grep -n 'X-Forwarded-Proto'
```

HTTPS is also what lets Chrome offer **Install as desktop app** in the sidebar.

## 8. Deploying an update

```bash
cd /srv/gym/app
git pull
docker compose -f docker-compose.yml build
docker compose -f docker-compose.yml up -d
docker compose -f docker-compose.yml run --rm migrate
```

- The image build runs `composer install --no-dev` and `npm run build`, so
  PHP and front-end dependencies never need installing on the host.
- Config, route and view caches are rebuilt by the entrypoint on every
  container start, so there is nothing to clear by hand.
- `up -d` recreates only the containers whose image changed; workers and
  Horizon pick up new code because they are restarted with it. Horizon is
  given 30 s to finish in-flight jobs.
- Run `migrate` every time; it is a no-op when there is nothing new.

Rolling back: `git checkout <previous tag>` and repeat the three Docker
commands. Migrations are written to be reversible where data allows, but
prefer restoring a backup (§9) to rolling migrations back on a live system.

## 9. Backups and restore

The `backup` service dumps the database every 24 hours to `app/backups/`
(custom format, 14-day retention). Copy that directory off the server — a
nightly `rsync`/`rclone` to object storage is the minimum.

Also back up:

- `.env` — it holds `APP_KEY`; without it encrypted data (bucket credentials)
  and existing sessions are unreadable.
- The `app-storage` volume — organisation logos and favicons, plus locally
  stored expense receipts if `FILESYSTEM_DISK=local`:
  `docker run --rm -v gym-platform_app-storage:/data -v "$PWD":/out alpine tar czf /out/app-storage.tgz -C /data .`

Restore a dump into a running stack:

```bash
docker compose -f docker-compose.yml stop app worker horizon scheduler
docker compose -f docker-compose.yml exec -T postgres sh -c 'PGPASSWORD="$POSTGRES_PASSWORD" pg_restore -U "$POSTGRES_USER" -d "$POSTGRES_DB" --clean --if-exists' < backups/backup-YYYYMMDD-HHMMSS.dump
docker compose -f docker-compose.yml start app worker horizon scheduler
```

## 10. Operations

| Need | Command |
|---|---|
| Logs (all / one service) | `docker compose -f docker-compose.yml logs -f --tail=200` · `… logs -f app` |
| Application log file | `docker compose -f docker-compose.yml exec app tail -f storage/logs/laravel.log` |
| Artisan | `docker compose -f docker-compose.yml exec app php artisan <command>` |
| Queue health | `https://gym.example.com/horizon` (phones in `PLATFORM_HORIZON_PHONES`) |
| Restart everything | `docker compose -f docker-compose.yml restart` |
| Disk usage of volumes | `docker system df -v` |
| Prune old images after deploys | `docker image prune -f` |

Scheduled work is handled by the `scheduler` container; no host cron is needed.
Email is not used for anything user-facing — all member communication is
WhatsApp deep links opened by staff — so `MAIL_*` can stay at defaults.

## 11. Troubleshooting

| Symptom | Cause / fix |
|---|---|
| `403 Forbidden` from the stack on `/` | The `app-public` volume is stale or empty. `docker compose -f docker-compose.yml up -d --force-recreate app nginx`; the entrypoint re-syncs `public/`. |
| Site unstyled, console shows blocked `http://…css` | See §7: set `APP_URL` to `https://…`, confirm `X-Forwarded-Proto` is forwarded, `up -d` to rebuild the config cache. |
| "Organisation not found" for a gym's domain | Missing in one of the three places in §6 — most often the Domains tab in the console. |
| Login works but messages/reports never appear | Workers or Horizon down: `docker compose -f docker-compose.yml ps`, then `logs horizon`. |
| `SQLSTATE[08006]` / connection refused | `DB_HOST` must be `postgres` (the container), not `127.0.0.1`; same for `REDIS_HOST=redis`. |
| Backups are 0 bytes | Do not edit the `backup` entrypoint to split the `pg_dump … >` redirect across lines; the file has a comment explaining why. |
| `The payment date field must be a date before or equal to today` in the evening | Fixed in code (dates compare in the organisation's timezone). If you see it, the organisation's timezone in the console is wrong. |
| Install-as-app button missing in Chrome | Requires HTTPS and a first successful load of `/sw.js` — check the host nginx proxies it. |
