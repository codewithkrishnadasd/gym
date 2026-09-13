<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * Refuses to run against anything but the dedicated testing database.
     *
     * RefreshDatabase truncates whatever it is pointed at, so a
     * misconfigured environment is destructive rather than merely wrong.
     * tests/bootstrap.php forces the correct value; this verifies the
     * connection that actually resulted.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $database = $this->app['db']->connection()->getDatabaseName();

        if (! str_ends_with((string) $database, '_testing')) {
            throw new RuntimeException(
                "Refusing to run tests against database [{$database}]: the test database name must end in '_testing'."
            );
        }
    }
}
