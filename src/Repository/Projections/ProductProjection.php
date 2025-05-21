<?php

namespace Sebastian\PhpEcommerce\Repository\Projections;

class ProductProjection
{
    public function __construct(
        public int $id,
        public string $name,
        public string $category,
        public float $price,
        public string $mainImage,
        public array $images = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'price' => $this->price,
            'mainImage' => $this->mainImage,
            'images' => $this->images
        ];
    }
}