<?php

declare(strict_types=1);

use App\Models\Club;
use App\Models\Domain;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Illuminate\Support\Facades\File;

/**
 * Destructive actions ask through the application's own dialog rather than
 * window.confirm(), which cannot be styled or themed, renders as a cramped
 * strip at the top of a phone screen, and on some mobile browsers offers a
 * "block further dialogs" checkbox that silently disables every later
 * confirmation — turning a guarded delete into a one-tap one.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create();

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'confirm.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $user->id,
    ]);
    $this->actingAs($user);
});

it('asks through the styled dialog, not the browser', function (): void {
    $club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $club->id,
        'name' => 'Alex Morgan',
    ]);

    $html = (string) $this->get('http://confirm.test/members')->assertOk()->getContent();

    expect($html)
        ->toContain('data-confirm-dialog')
        ->toContain('data-confirm=')
        ->toContain('Remove this member?')
        // wire:confirm is Livewire's own call to window.confirm().
        ->not->toContain('wire:confirm')
        ->not->toContain('return confirm(');
});

it('mounts exactly one dialog per page', function (): void {
    $html = (string) $this->get('http://confirm.test/dashboard')->assertOk()->getContent();

    // Two would both answer the event and fire the action twice.
    expect(substr_count($html, 'data-confirm-dialog'))->toBe(1);
});

it('mounts the dialog on the signed-out pages too', function (): void {
    // Not needed today, but the interceptor lets a click through when no dialog
    // is present rather than swallowing it — mounting everywhere keeps a future
    // confirmable action from quietly bypassing the question.
    $this->post('http://confirm.test/logout');

    $this->get('http://confirm.test/')->assertOk()->assertSee('data-confirm-dialog', escape: false);
});

it('leaves no native confirm anywhere in the views', function (): void {
    $offenders = [];

    foreach (File::allFiles(resource_path('views')) as $file) {
        $contents = $file->getContents();

        if (str_contains($contents, 'wire:confirm') || str_contains($contents, 'return confirm(')) {
            $offenders[] = $file->getRelativePathname();
        }
    }

    expect($offenders)->toBe([]);
});

it('gives every confirmation a heading, so none falls back to "Are you sure?"', function (): void {
    $missing = [];

    foreach (File::allFiles(resource_path('views')) as $file) {
        $contents = $file->getContents();

        // Count the two attributes rather than parsing: a control carrying
        // data-confirm without a title reads as a generic browser prompt, which
        // is the thing being replaced.
        $confirms = substr_count($contents, 'data-confirm="');
        $titles = substr_count($contents, 'data-confirm-title="');

        if ($confirms > $titles) {
            $missing[] = $file->getRelativePathname();
        }
    }

    expect($missing)->toBe([]);
});
