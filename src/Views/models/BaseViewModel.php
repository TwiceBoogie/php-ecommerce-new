<?php

namespace Sebastian\PhpEcommerce\Views\Models;

abstract class BaseViewModel
{
    protected bool $isAdmin;
    protected bool $isAuthenticated;

    public function __construct(bool $isAdmin, bool $isAuthenticated)
    {
        $this->isAdmin = $isAdmin;
        $this->isAuthenticated = $isAuthenticated;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }
}