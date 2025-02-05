<?php

namespace Sebastian\PhpEcommerce\Middleware;

use Sebastian\PhpEcommerce\Services\SecureSession;
use Sebastian\PhpEcommerce\Services\Response;

class RedirectIfAuthMiddleware implements MiddlewareInterface
{
    public function handle(array $request, callable $next): mixed
    {
        if (SecureSession::get('user_id')) {
            if ($this->isApiRequest($request)) {
                // Return JSON response instead of redirecting
                return Response::send(['error' => 'Already authenticated'], 403);
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
    private function isApiRequest(array $request): bool
    {
        return isset($request['path']) && str_starts_with($request['path'], '/api/');
    }
}
