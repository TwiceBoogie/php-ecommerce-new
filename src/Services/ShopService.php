<?php

namespace Sebastian\PhpEcommerce\Services;

use Sebastian\PhpEcommerce\DTO\ProductDTO;

interface ShopService
{
    public function getAllProduct(): array;
    public function getProduct(string $productId): ProductDTO;
}