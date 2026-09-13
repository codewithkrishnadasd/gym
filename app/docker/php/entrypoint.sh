#!/bin/sh
set -e

. "$(dirname "$0")/sync-public.sh"

# Config/route/view caches are rebuilt at container start rather than image
# build time, because real secrets (DB_PASSWORD, APP_KEY, ...) only exist as
# environment variables injected by Docker Compose at runtime — baking them
# into the image at build time would leak them into image layers.
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction
php artisan event:cache --no-interaction

exec "$@"
