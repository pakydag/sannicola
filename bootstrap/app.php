<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocaleMiddleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'webhook/spoki',
            'webhook/*',
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'agent' => \App\Http\Middleware\IsAgent::class,
        ]);

        $middleware->redirectTo(
            guests: function (\Illuminate\Http\Request $request) {
                if ($request->is('booking*')) {
                    return route('public.booking.login.view');
                }
                return route('login');
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
