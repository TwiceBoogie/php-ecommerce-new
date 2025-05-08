<?php

namespace Sebastian\PhpEcommerce\Middleware;

use Sebastian\PhpEcommerce\Http\Request;
use Sebastian\PhpEcommerce\Services\AuthService;

class InjectAuthContextMiddleware implements MiddlewareInterface
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function handle(Request $request, callable $next): mixed
    {
        $userId = $this->authService->getAuthenticatedUserId();
        $isAuthenticated = $userId !== 0;
        $isAdmin = $isAuthenticated && $this->authService->isAdmin();

        $request->setAuthenticated($isAuthenticated);
        $request->setAdmin($isAdmin);

        return $next($request);
    }
}