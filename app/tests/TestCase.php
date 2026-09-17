<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Livewire\Livewire;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /** Set by a test that wants pages to defer as they do in the browser. */
    public static bool $lazyPages = false;

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

        // Pages defer their content behind a skeleton (LazyPage); tests read
        // the page as a whole. Livewire clears the switch after every render
        // it tests, so it is set again each time. LazyPageTest covers the
        // deferral itself and opts back in.
        Livewire::withoutLazyLoading();
        \Livewire\after('flush-state', static fn () => static::$lazyPages || Livewire::withoutLazyLoading());

        static::$lazyPages = false;

        $database = $this->app['db']->connection()->getDatabaseName();

        if (! str_ends_with((string) $database, '_testing')) {
            throw new RuntimeException(
                "Refusing to run tests against database [{$database}]: the test database name must end in '_testing'."
            );
        }
    }
}
