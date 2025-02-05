<?php

namespace Sebastian\PhpEcommerce\Middleware;

interface MiddlewareInterface
{
    public function handle(array $request, callable $next): mixed;
}