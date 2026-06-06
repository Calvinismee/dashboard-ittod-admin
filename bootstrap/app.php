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
        // Mendaftarkan alias middleware untuk tugas Orang ke-3
        $middleware->alias([
            /**
             * REQ-09: Middleware untuk mengunci data peserta (Data Freezing Logic)
             * Menjamin data tidak bisa diubah jika is_verified = true
             */
            'data_frozen' => \App\Http\Middleware\EnsureDataNotFrozen::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();