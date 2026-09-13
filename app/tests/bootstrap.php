<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

/*
 * Docker Compose injects DB_* into the real process environment via
 * `env_file: .env`, and Laravel's Env reader prefers $_SERVER over the
 * <env> entries in phpunit.xml — even ones marked force="true", which only
 * set $_ENV and putenv().
 *
 * The result is that `php artisan test` inside the app container would
 * connect RefreshDatabase to the *development* database and truncate it.
 * Forcing the value here, before the framework boots, is the only place
 * that reliably wins. TestCase then re-checks the live connection as a
 * second line of defence.
 */
$forced = [
    'DB_DATABASE' => 'gym_platform_testing',
    'DB_URL' => '',
    // The dev compose override sets APP_ENV=local in the container. Laravel
    // reads that in preference to phpunit.xml, so `runningUnitTests()` was
    // false and CSRF verification stayed on, failing every POST with a 419.
    'APP_ENV' => 'testing',
    'APP_DEBUG' => 'true',
];

foreach ($forced as $key => $value) {
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
    putenv("{$key}={$value}");
}
