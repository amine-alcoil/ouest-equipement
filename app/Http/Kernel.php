<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareAliases = [
        'admin.session' => \App\Http\Middleware\AdminSession::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'admin.guest' => \App\Http\Middleware\RedirectIfAdminAuthenticated::class,
    ];
}