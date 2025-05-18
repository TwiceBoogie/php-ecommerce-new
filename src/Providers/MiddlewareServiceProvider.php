<?php

namespace Sebastian\PhpEcommerce\Providers;

use Pimple\ServiceProviderInterface;
use Sebastian\PhpEcommerce\Services\AuthService;
use Sebastian\PhpEcommerce\Middleware\AuthMiddleware;
use Sebastian\PhpEcommerce\Middleware\InjectAuthContextMiddleware;
use Sebastian\PhpEcommerce\Middleware\IsAdminMiddleware;
use Sebastian\PhpEcommerce\Middleware\RedirectIfAuthMiddleware;

class MiddlewareServiceProvider implements ServiceProviderInterface
{
    public function register(\Pimple\Container $c): void
    {
        $c[InjectAuthContextMiddleware::class] = fn($c) => new InjectAuthContextMiddleware($c[AuthService::class]);
        $c[AuthMiddleware::class] = fn($c) => new AuthMiddleware();
        $c[IsAdminMiddleware::class] = fn($c) => new IsAdminMiddleware();
        $c[RedirectIfAuthMiddleware::class] = fn($c) => new RedirectIfAuthMiddleware();
    }
}