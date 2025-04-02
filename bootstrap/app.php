<?php

use App\Http\Middleware\RoleByUser;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->redirectGuestsTo('/itlogin');
        // Using a closure...
        $middleware->redirectGuestsTo(fn(Request $request) => route('itlogin'));
        $middleware->validateCsrfTokens(except: [
            '/livewire/*',
        ]);
        $middleware->alias([
            'role' => RoleByUser::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
