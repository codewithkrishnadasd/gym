#!/bin/bash
# Local/dev convenience only: provisions a second database for the Pest
# test suite (see phpunit.xml) alongside the main POSTGRES_DB. Not used in
# the production compose file, which only ever creates one database.
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
    SELECT 'CREATE DATABASE ${POSTGRES_DB}_testing OWNER ${POSTGRES_USER}'
    WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = '${POSTGRES_DB}_testing')\gexec
EOSQL
