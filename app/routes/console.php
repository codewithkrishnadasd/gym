<?php

use App\Models\PasswordResetLink;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * Nightly metric rollup (MEP.md 8.4). It runs a couple of hours after
 * midnight UTC so that organisations in most timezones have finished their
 * day, and re-rolls the previous three days to absorb any late-confirmed
 * payments or backdated attendance corrections. The command is idempotent —
 * each run overwrites its own rows rather than accumulating.
 */
Schedule::command('metrics:roll-up', ['--days' => 3])
    ->dailyAt('02:15')
    ->withoutOverlapping()
    ->onOneServer();

/*
 * Deletes password reset links a week after they stop working. They cannot be
 * redeemed by then; this only keeps the table from growing without limit. The
 * fact that a link was issued lives in the audit trail, not here.
 */
Schedule::command('model:prune', ['--model' => [PasswordResetLink::class]])
    ->dailyAt('03:05')
    ->withoutOverlapping()
    ->onOneServer();
