<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\CheckActiveUser;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\CheckPeriodeAccess;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            CheckActiveUser::class,
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'active' => CheckActiveUser::class,
            'superadmin' => SuperAdminMiddleware::class,
            'periode' => CheckPeriodeAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();