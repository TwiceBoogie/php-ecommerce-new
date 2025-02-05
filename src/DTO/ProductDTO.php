<?php

namespace Sebastian\PhpEcommerce\DTO;

class ProductDTO
{
    private int $id;
    private string $product_name;
    private string $product_category;
    private float $product_price;
    private string $main_image;
    private ?array $images;
    private ?string $product_description;
    private ?int $stock_quantity;

    public function __construct(
        int $id,
        string $product_name,
        string $product_category,
        float $product_price,
        string $main_image,
        ?array $images = [],
        ?string $product_description = null,
        ?int $stock_quantity = null
    ) {
        $this->id = $id;
        $this->product_name = $product_name;
        $this->product_category = $product_category;
        $this->product_price = $product_price;
        $this->main_image = $main_image;
        $this->images = $images;
        $this->product_description = $product_description ?? '';
        $this->stock_quantity = $stock_quantity;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->product_name;
    }

    public function getCategory(): string
    {
        return $this->product_category;
    }

    public function getPrice(): float
    {
        return $this->product_price;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function getPrimaryImage(): string
    {
        return $this->main_image;
    }

    public function getDescription(): string
    {
        return $this->product_description ?? '';
    }

    public function getQuantity(): int
    {
        return $this->stock_quantity;
    }
}
