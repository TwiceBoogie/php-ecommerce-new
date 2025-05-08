<?php

namespace Sebastian\PhpEcommerce\Services;

use Sebastian\PhpEcommerce\DTO\ResponseDTO;
use Sebastian\PhpEcommerce\Http\Request\RegisterRequest;

interface RegisterService
{
    public function register(RegisterRequest $request): ResponseDTO;
}