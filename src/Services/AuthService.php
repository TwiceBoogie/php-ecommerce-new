<?php

namespace Sebastian\PhpEcommerce\Services;

interface AuthService
{
    public function getAuthenticatedUserId(): int;
    public function isAdmin(): bool;
}