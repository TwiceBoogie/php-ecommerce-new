<?php

namespace Sebastian\PhpEcommerce\Views\Models;

use Sebastian\PhpEcommerce\DTO\ProductDTO;

class ShopViewModel extends BaseViewModel
{
    /**
     * @var ProductDTO[]
     */
    private array $products = [];
    private ?ProductDTO $product;

    public function __construct(bool $isAdmin, bool $isAuthenticated, array $products = [], ?ProductDTO $product = null)
    {
        parent::__construct($isAdmin, $isAuthenticated);
        $this->products = $products;
        $this->product = $product;
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