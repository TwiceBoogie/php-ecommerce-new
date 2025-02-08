<?php

namespace Sebastian\PhpEcommerce\Services;

use Sebastian\PhpEcommerce\DTO\ProductDTO;

interface ShopService
{
    /**
     * Fetches all products
     * @return ProductDTO[]
     */
    public function getAllProduct(): array;

    /**
     * Fetches info from one product via its id
     * @param string $productId
     * @return ProductDTO
     */
    public function getProduct(string $productId): ProductDTO;
}