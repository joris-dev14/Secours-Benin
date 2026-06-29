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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.citoyen'     => \App\Http\Middleware\AuthCitoyen::class,
            'auth.regulateur'  => \App\Http\Middleware\AuthRegulateur::class,
            'auth.ambulancier' => \App\Http\Middleware\AuthAmbulancier::class,
            'auth.admin'       => \App\Http\Middleware\AuthAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();