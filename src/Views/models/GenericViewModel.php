<?php

namespace Sebastian\PhpEcommerce\Views\Models;

class GenericViewModel extends BaseViewModel
{
    public function __construct(bool $isAdmin, bool $isAuthenticated)
    {
        parent::__construct($isAdmin, $isAuthenticated);
    }
}