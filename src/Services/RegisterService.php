<?php

namespace Sebastian\PhpEcommerce\Services;

use Sebastian\PhpEcommerce\DTO\ResponseDTO;

interface RegisterService
{
    public function register(array $input): ResponseDTO;
}