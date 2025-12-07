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
        \App\Http\Middleware\DebugSession::class, // TAMBAHKAN INI
    ]);
    
    $middleware->alias([
        'admin' => \App\Http\Middleware\Admin::class,
        'security' => \App\Http\Middleware\Security::class,
        'manager' => \App\Http\Middleware\Manager::class,
        'cleaning' => \App\Http\Middleware\Cleaning::class,
        'kantoran' => \App\Http\Middleware\Kantoran::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
