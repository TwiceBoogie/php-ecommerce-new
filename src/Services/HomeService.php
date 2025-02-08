<?php

namespace Sebastian\PhpEcommerce\Services;

use Sebastian\PhpEcommerce\DTO\ProductDTO;

interface HomeService
{
    /**
     * Fetch a list of products by category with an optional limit.
     *
     * @param string $category The category of the products (e.g., 'keyboards', 'mice').
     * @param int $limit The maximum number of products to fetch.
     * @return ProductDTO[] Array of ProductDTO objects.
     */
    public function getProductsByCategory(string $category, int $limit): array;
}