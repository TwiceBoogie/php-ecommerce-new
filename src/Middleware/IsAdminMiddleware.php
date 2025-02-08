<?php

namespace Sebastian\PhpEcommerce\Middleware;

use Sebastian\PhpEcommerce\Http\Request;
use Sebastian\PhpEcommerce\Services\AuthService;

class IsAdminMiddleware implements MiddlewareInterface
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function handle(Request $request, callable $next): mixed
    {
        $isAdmin = $this->authService->getAuthenticatedUserId() && $this->authService->isAdmin();
        $request->isAdmin = $isAdmin;

        return $next($request);
    }
}
