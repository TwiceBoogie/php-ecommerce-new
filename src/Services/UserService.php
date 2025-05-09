<?php

namespace Sebastian\PhpEcommerce\Services;

use Sebastian\PhpEcommerce\DTO\UserDetailsDTO;
use Sebastian\PhpEcommerce\Http\Request\UpdateEmailRequest;
use Sebastian\PhpEcommerce\Http\Request\UpdateUserDetailsRequest;
use Sebastian\PhpEcommerce\DTO\ResponseDTO;

interface UserService
{
    public function getUserDetails(): UserDetailsDTO;
    public function updateUserDetails(UpdateUserDetailsRequest $request): ResponseDTO;
    public function updateEmail(UpdateEmailRequest $request): ResponseDTO;
}