#!/bin/sh
set -e

# Refreshes ./public/build from the image's stable, unmounted copy. Needed
# because app-public-build is a named volume shared read-only with nginx
# (docker-compose.yml) — Docker only auto-populates a named volume from the
# image the first time it's created, so without this, every deploy after
# the first would keep serving the original build's stale assets forever.
if [ -d /opt/app-build/public-build ]; then
    # ./public/build is itself a mount point (the named volume), so its
    # contents are cleared in place rather than removing the directory.
    find ./public/build -mindepth 1 -delete
    cp -a /opt/app-build/public-build/. ./public/build/
fi
