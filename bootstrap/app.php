<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
         $middleware->alias([
        'checkLoginClient' => \App\Http\Middleware\CheckLoggedInClients::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        // 'auth' => \App\Http\Middleware\Authenticate::class,
        'users' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'check.auth.admin' => \App\Http\Middleware\RedirectIfAuthenticatedAdmin::class,
        'permission' => \App\Http\Middleware\CheckPermisson::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // BẮT LỖI 404
        $exceptions->render(function (NotFoundHttpException $e, $request) {

            // Trang admin
            if ($request->is('admin/*')) {
                return response()->view('admin.errors.404', [], 404);
            }

            // Trang client
            return response()->view(
                'clients.errors.404',
                ['title' => '404'],
                404
            );
        });

    })
    ->create();
