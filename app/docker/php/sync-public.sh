#!/bin/sh
set -e

# Refreshes ./public from the image's stable, unmounted copy.
#
# `public` is an `app-public` named volume shared read-only with nginx, and
# Docker only auto-populates a named volume from the image the first time it
# is created. Without this, every deploy after the first would keep serving
# the original image's index.php and assets forever.
if [ -d /opt/app-public ]; then
    # ./public is itself the mount point, so its contents are cleared in place
    # rather than removing and recreating the directory.
    find ./public -mindepth 1 -delete
    cp -a /opt/app-public/. ./public/
fi
