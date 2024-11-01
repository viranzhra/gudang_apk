<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up', 
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'jwt_token' => \App\Http\Middleware\AuthToken::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            // 'initialize_permissions' => \App\Http\Middleware\InitializePermissions::class,
        ]);
    })    
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
