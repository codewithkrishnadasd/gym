<?php

declare(strict_types=1);

use App\Models\Domain;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Support\PageQuery;
use Illuminate\Http\Request;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Pages arrive in two steps (App\Livewire\Concerns\LazyPage): the shell with
 * a skeleton at once, the content on a follow-up request. Every other test
 * switches the deferral off to read a page whole; these switch it back on.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create();
    Domain::factory()->create(['organisation_id' => $this->organisation->id, 'hostname' => 'lazy.test', 'status' => 'active', 'is_primary' => true]);
    app()->instance('tenant', $this->organisation);

    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    TestCase::$lazyPages = true;
    app('livewire')->flushState();
});

it('answers a page with its shell and a skeleton, and loads the content on arrival', function (): void {
    Member::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Deferred Dev']);

    $this->get('http://lazy.test/members')
        ->assertOk()
        // The navigation and the tab title are there straight away…
        ->assertSee('<title>Members', false)
        ->assertSee('page-skeleton')
        // …the content is asked for the moment the page lands, not on scroll…
        ->assertSee('x-init="$wire.__lazyLoad(', false)
        // …and no query has run for it yet.
        ->assertDontSee('Deferred Dev');

    // A detail page gets the detail skeleton; a form the form one.
    $member = Member::query()->firstOrFail();
    $this->get('http://lazy.test/members/'.$member->id)->assertOk()->assertSee('page-skeleton')->assertSee('<title>Members', false);
    $this->get('http://lazy.test/members/create')->assertOk()->assertSee('page-skeleton');
});

it('reads the page query string from the referer once the content is loading', function (): void {
    $this->app->instance('request', Request::create('/livewire/update', 'POST', server: [
        'HTTP_X_LIVEWIRE' => '1',
        'HTTP_REFERER' => 'http://lazy.test/tasks/create?member=42&who=&club=abc',
    ]));

    expect(PageQuery::integer('member'))->toBe(42)
        ->and(PageQuery::has('who'))->toBeTrue()
        ->and(PageQuery::get('who'))->toBe('')
        ->and(PageQuery::integer('club'))->toBeNull()
        ->and(PageQuery::has('missing'))->toBeFalse();

    // On the page request itself it is the request's own query string.
    $this->app->instance('request', Request::create('/tasks/create?member=7', 'GET'));

    expect(PageQuery::integer('member'))->toBe(7);
});

/**
 * Plays the browser's part: takes the placeholder's snapshot and the encoded
 * mount parameters from the page, and sends the follow-up request that
 * loads the content.
 */
function loadDeferred(TestResponse $page, string $referer): TestResponse
{
    $html = $page->getContent();

    preg_match('/wire:snapshot="([^"]+)"/', $html, $snapshot);
    preg_match('/__lazyLoad\(&#039;([^&]+)&#039;\)/', $html, $encoded);

    if ($encoded === []) {
        preg_match("/__lazyLoad\\('([^']+)'\\)/", $html, $encoded);
    }

    expect($snapshot)->not->toBeEmpty()->and($encoded)->not->toBeEmpty();

    return test()->withHeaders(['X-Livewire' => '1', 'Referer' => $referer])
        ->postJson('http://lazy.test'.route('default-livewire.update', absolute: false), [
            '_token' => csrf_token(),
            'components' => [[
                'snapshot' => html_entity_decode($snapshot[1], ENT_QUOTES),
                'updates' => [],
                'calls' => [['path' => '', 'method' => '__lazyLoad', 'params' => [$encoded[1]]]],
            ]],
        ]);
}

it('loads the deferred content with the page\'s own query string honoured', function (): void {
    Member::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Deferred Dev']);
    Member::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Filtered Out', 'status' => 'archived']);

    $url = 'http://lazy.test/members?search=Deferred';
    $page = $this->get($url)->assertOk()->assertDontSee('Deferred Dev');

    $response = loadDeferred($page, $url)->assertOk();
    $html = (string) data_get($response->json(), 'components.0.effects.html');

    expect($html)->toContain('Deferred Dev')
        ->not->toContain('Filtered Out')
        ->not->toContain('page-skeleton');
});

it('loads a detail page for the model in the address', function (): void {
    $member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Bound By Route']);

    $url = 'http://lazy.test/members/'.$member->id;
    $page = $this->get($url)->assertOk()->assertDontSee('Bound By Route');

    $html = (string) data_get(loadDeferred($page, $url)->assertOk()->json(), 'components.0.effects.html');

    expect($html)->toContain('Bound By Route');

    // And a missing one is still a proper 404 page, not a broken placeholder.
    $this->get('http://lazy.test/members/999999')->assertNotFound();
});
