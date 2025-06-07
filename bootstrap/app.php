<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    // Tambahkan blok withProviders jika belum ada
    ->withProviders([
        // Daftarkan provider Anda di sini
        App\Providers\AuthServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        // DAFTARKAN ALIAS ANDA DI SINI
        $middleware->alias([
            'is-admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
