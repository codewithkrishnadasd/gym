#!/bin/sh
set -e

# Dev mode skips the production entrypoint's config/route/view caching (dev
# config must be read live). It also deliberately skips sync-build-assets.sh:
# docker-compose.override.yml bind-mounts ./public/build from the working
# tree, so the assets a developer just compiled are already in place and
# re-syncing from the image would overwrite them with a stale bundle.

exec "$@"
