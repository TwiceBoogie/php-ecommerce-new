<?php

namespace Sebastian\PhpEcommerce\Services;

use Sebastian\PhpEcommerce\DTO\UserDetailsDTO;

interface UserService
{
    public function getUserDetails(): UserDetailsDTO;
}