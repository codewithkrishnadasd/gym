<?php

use App\Http\Middleware\ResolveTenant;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(prepend: [ResolveTenant::class]);

        // In production the stack sits behind a TLS-terminating reverse proxy,
        // so the scheme and client IP arrive as X-Forwarded-* headers. Without
        // this, every generated URL and redirect comes out as http:// even
        // though the visitor is on https://.
        //
        // Trusting any proxy is only safe because the container's port is
        // published to 127.0.0.1 (see HTTP_PORT), which means nothing but the
        // host's own reverse proxy can reach it and forge those headers. If you
        // ever publish the port on 0.0.0.0, replace '*' with the proxy's IP.
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
