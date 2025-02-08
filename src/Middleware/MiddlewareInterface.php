<?php

namespace Sebastian\PhpEcommerce\Middleware;

use Sebastian\PhpEcommerce\Http\Request;

interface MiddlewareInterface
{
    public function handle(Request $request, callable $next): mixed;
}