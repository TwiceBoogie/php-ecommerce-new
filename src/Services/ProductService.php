<?php

namespace Sebastian\PhpEcommerce\Services;

use Sebastian\PhpEcommerce\Repository\Projections\ProductProjection;

interface ProductService
{
    public function getProductProjectionById(int $productId): ?ProductProjection;
    /**
     * Summary of getProductProjectionByIds
     * @param array $productIds
     * @return ProductProjection[]
     */
    public function getProductProjectionByIds(array $productIds): array;
    public function isStockAvailable(int $productId, int $quantity): bool;
}