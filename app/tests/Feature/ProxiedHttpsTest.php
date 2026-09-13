<?php

declare(strict_types=1);

/**
 * Production terminates TLS at a reverse proxy, so the application only knows
 * the visitor was on https because a header says so. When that breaks, the page
 * still renders — but every asset URL comes back as http:// and the browser
 * blocks it as mixed content, leaving an unstyled page with dead JavaScript.
 * Nothing about that failure shows up in the application's own logs, so it is
 * covered here instead.
 */
it('builds https asset URLs when the proxy reports an https visitor', function (): void {
    $response = $this->get('/', ['X-Forwarded-Proto' => 'https']);

    $response->assertOk();

    expect((string) $response->getContent())
        ->toContain('https://localhost:8000/build/')
        ->not->toContain('http://localhost:8000/build/');
});

it('leaves URLs on http when no proxy is in front', function (): void {
    $response = $this->get('/');

    $response->assertOk();

    expect((string) $response->getContent())->toContain('http://localhost:8000/build/');
});
