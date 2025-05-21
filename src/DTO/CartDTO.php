<?php

namespace Sebastian\PhpEcommerce\DTO;

class CartDTO
{
    private int $id;
    /**
     * @var CartItemDTO[]
     */
    private array $items;
    private int $totalQuantity;
    private int $totalCost;

    public function __construct(int $id, array $items, int $totalCost, int $totalQuantity)
    {
        $this->id = $id;
        $this->items = $items;
        $this->totalCost = $totalCost;
        $this->totalQuantity = $totalQuantity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return CartItemDTO[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotalQuantity(): int
    {
        return $this->totalQuantity;
    }

    public function getTotalCost(): int
    {
        return $this->totalCost;
    }
}