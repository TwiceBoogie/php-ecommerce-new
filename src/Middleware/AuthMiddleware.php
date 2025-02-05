<?php

namespace Sebastian\PhpEcommerce\Middleware;

use Sebastian\PhpEcommerce\Services\SecureSession;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(array $request, callable $next): mixed
    {
        if (!SecureSession::get('user_id')) {
            // Redirect to login if the user is not logged in
            header("Location: /login");
            exit();
        }

        return $next($request);
    }
}
