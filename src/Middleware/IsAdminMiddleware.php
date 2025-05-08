<?php

namespace Sebastian\PhpEcommerce\Middleware;

use Sebastian\PhpEcommerce\Http\Request;

class IsAdminMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): mixed
    {
        if (!$request->isAdmin()) {
            header("Location: /unauthorized");
            exit();
        }

        return $next($request);
    }
}
