<?php

namespace Sebastian\PhpEcommerce\Views\Models;

use Sebastian\PhpEcommerce\DTO\ProductDTO;

class ShopViewModel
{
    private bool $isAdmin;
    /**
     * @var ProductDTO[]
     */
    private array $products = [];
    private ?ProductDTO $product;

    public function __construct(bool $isAdmin, array $products = [], ?ProductDTO $product = null)
    {
        $this->isAdmin = $isAdmin;
        $this->products = $products;
        $this->product = $product;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @return ProductDTO[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    public function getProduct(): ?ProductDTO
    {
        return $this->product;
    }
}