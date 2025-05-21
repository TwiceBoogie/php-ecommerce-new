<?php

namespace Sebastian\PhpEcommerce\Services\Impl;

use Sebastian\PhpEcommerce\Repository\ProductRepository;
use Sebastian\PhpEcommerce\Repository\Projections\ProductProjection;
use Sebastian\PhpEcommerce\Services\ProductService;

class ProductServiceImpl implements ProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProductProjectionById(int $productId): ?ProductProjection
    {
        return $this->productRepository->getProductById($productId);
    }

    public function getProductProjectionByIds(array $productIds): array
    {
        if (empty($productIds)) {
            return [];
        }
        return $this->productRepository->getProductByIds($productIds);
    }

    public function isStockAvailable(int $productId, int $quantity): bool
    {
        return $this->productRepository->productStockAvailable($productId, $quantity);
    }
}