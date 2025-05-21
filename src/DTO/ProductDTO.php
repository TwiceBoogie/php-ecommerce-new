<?php

namespace Sebastian\PhpEcommerce\DTO;

class ProductDTO
{
    private int $id;
    private string $name;
    private string $category;
    private float $price;
    private string $main_image;
    private ?array $images;
    private ?string $description;
    private ?int $stock_quantity;

    public function __construct(
        int $id,
        string $name,
        string $category,
        float $price,
        string $main_image = "",
        ?array $images = [],
        ?string $description = null,
        ?int $stock_quantity = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
        $this->price = $price;
        $this->main_image = $main_image;
        $this->images = $images;
        $this->description = $description ?? '';
        $this->stock_quantity = $stock_quantity;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getPrice(): float
    {
        return $this->price;
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
        return $this->description ?? '';
    }

    public function getQuantity(): int
    {
        return $this->stock_quantity;
    }
}
