<?php

namespace Sebastian\PhpEcommerce\Services;

use Sebastian\PhpEcommerce\DTO\ResponseDTO;

interface LoginService
{
    public function login(array $input): ResponseDTO;
    public function logout(): ResponseDTO;
}