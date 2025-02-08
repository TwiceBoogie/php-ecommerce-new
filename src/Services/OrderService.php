<?php

namespace Sebastian\PhpEcommerce\Services;

use Sebastian\PhpEcommerce\DTO\ResponseDTO;

interface OrderService
{
    public function getOrders(): ResponseDTO;
}