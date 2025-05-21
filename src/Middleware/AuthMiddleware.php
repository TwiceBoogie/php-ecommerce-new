<?php

namespace Sebastian\PhpEcommerce\Middleware;

use Sebastian\PhpEcommerce\Http\Request;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): mixed
    {
        if (!$request->isAuthenticated()) {
            // Redirect to login if the user is not logged in
            header("Location: /login");
            exit();
        }

        return $next($request);
    }
}
