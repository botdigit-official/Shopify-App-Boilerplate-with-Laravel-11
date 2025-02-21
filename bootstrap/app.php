<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ShopifyFrameAncestor;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.shopify' => \App\Http\Middleware\ShopifyAuth::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        $middleware->web(append: [
            ShopifyFrameAncestor::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // You can add exception handling logic here if needed
    })
    ->withCommands([
        // Register your command here
        \App\Console\Commands\SetupApp::class,
    ])
    ->create();