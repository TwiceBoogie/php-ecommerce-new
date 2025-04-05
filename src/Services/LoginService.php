<?php

namespace Sebastian\PhpEcommerce\Services;

use Sebastian\PhpEcommerce\DTO\ResponseDTO;
use Sebastian\PhpEcommerce\Http\Request\LoginRequest;

interface LoginService
{
    public function login(LoginRequest $request): ResponseDTO;
    public function logout(): ResponseDTO;
}