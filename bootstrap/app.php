<?php
use App\Http\Middleware\AdminMiddleware;
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
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Ini untuk Alias Middleware yang sudah ada
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);

        // 2. Menambahkan Pengecualian CSRF untuk Callback Xendit di sini
        $middleware->validateCsrfTokens(except: [
            '/xendit/callback', // Pastikan route ini sesuai dengan yang di daftarkan di dashboard Xendit
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();