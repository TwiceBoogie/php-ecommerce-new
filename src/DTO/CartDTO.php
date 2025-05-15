<?php

namespace Sebastian\PhpEcommerce\DTO;

class CartDTO
{
    private int $id;
    private ProductDTO $product;
    private int $quantity;

    public function __construct(int $id, ProductDTO $product, int $quantity)
    {
        $this->id = $id;
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): ProductDTO
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}