<?php

use App\Console\Commands\CreateAdmin;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureSuperAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([CreateAdmin::class, PromoteSuperAdmin::class])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['admin' => EnsureAdmin::class, 'superadmin' => EnsureSuperAdmin::class]);
        $middleware->redirectGuestsTo('/admin/login');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
