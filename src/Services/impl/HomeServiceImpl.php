<?php

namespace Sebastian\PhpEcommerce\Services\Impl;

use Sebastian\PhpEcommerce\Mapper\ProductMapper;
use Sebastian\PhpEcommerce\Services\HomeService;
use Sebastian\PhpEcommerce\Repository\ProductRepository;

class HomeServiceImpl implements HomeService
{
    private ProductRepository $productRepository;
    private ProductMapper $productMapper;

    public function __construct(ProductRepository $productRepository, ProductMapper $productMapper)
    {
        $this->productRepository = $productRepository;
        $this->productMapper = $productMapper;
    }

    public function getProductsByCategory(string $category, int $limit): array
    {
        $rawProducts = $this->productRepository->getProductsByCategory($category, $limit);
        return $this->productMapper->mapToProductDTOArray($rawProducts);
    }
}