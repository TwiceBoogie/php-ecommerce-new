<?php

namespace Sebastian\PhpEcommerce\DTO;

class CartDTO
{
    private string $id;
    private ProductDTO $product;
    private int $quantity;

    public function __construct(string $id, ProductDTO $product, int $quantity)
    {
        $this->id = $id;
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getId(): string
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