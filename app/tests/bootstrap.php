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
foreach (['DB_DATABASE' => 'gym_platform_testing', 'DB_URL' => ''] as $key => $value) {
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
    putenv("{$key}={$value}");
}
