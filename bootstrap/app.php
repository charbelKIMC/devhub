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
            'isAdmin'  => \App\Http\Middleware\IsAdmin::class,
            'isMentor' => \App\Http\Middleware\IsMentor::class,
        ]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\UpdateDerniereActivite::class,
        ]);
    })
    
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'not.admin' => \App\Http\Middleware\NotAdmin::class,
            'is.admin'  => \App\Http\Middleware\IsAdmin::class,
            'is.mentor' => \App\Http\Middleware\IsMentor::class,
            'not.mentor' => \App\Http\Middleware\NotMentor::class,
        ]);
    })
    
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();