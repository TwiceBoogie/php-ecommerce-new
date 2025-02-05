<?php

namespace Sebastian\PhpEcommerce\Services\Impl;

use Sebastian\PhpEcommerce\DTO\ProductDTO;
use Sebastian\PhpEcommerce\Mapper\ProductMapper;
use Sebastian\PhpEcommerce\Repository\ProductRepository;
use Sebastian\PhpEcommerce\Services\ShopService;

class ShopServiceImpl implements ShopService
{
    private ProductRepository $productRepository;
    private ProductMapper $mapper;

    public function __construct(ProductRepository $productRepository, ProductMapper $mapper)
    {
        $this->productRepository = $productRepository;
        $this->mapper = $mapper;
    }

    public function getAllProduct(): array
    {
        $products = $this->productRepository->getAll();
        return $this->mapper->mapToProductDTOArray($products);
    }

    public function getProduct(string $productId): ProductDTO
    {
        $product = $this->productRepository->getProductById($productId);
        return $this->mapper->mapToProductDTO($product);
    }
}