<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\hasAdmin;
use App\Http\Middleware\hasAuth;
use App\Http\Middleware\notAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);

        $middleware->alias([
            'hasAuth' => hasAuth::class,
            'notAuth' => notAuth::class,
            'hasAdmin' => hasAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
