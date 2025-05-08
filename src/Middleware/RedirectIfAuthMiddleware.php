<?php

namespace Sebastian\PhpEcommerce\Middleware;

use Sebastian\PhpEcommerce\Http\Request;
use Sebastian\PhpEcommerce\Services\Response;

class RedirectIfAuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): mixed
    {
        if ($request->isAuthenticated()) {
            if ($this->isApiRequest($request)) {
                // Return JSON response instead of redirecting
                Response::send(['error' => 'Already authenticated'], 403);
                exit(); // Ensure execution stops
            }

            // If it's a normal web request, redirect to /account
            header("Location: /account");
            exit();
        }

        return $next($request);
    }

    /**
     * Check if the request is an API call.
     */
    private function isApiRequest(Request $request): bool
    {
        return str_starts_with($request->getPath(), '/api/');
    }
}

