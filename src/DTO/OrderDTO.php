<?php

namespace Sebastian\PhpEcommerce\DTO;

class OrderDTO
{
    private int $id;
    private float $order_cost;
    private string $order_status;
    private string $order_date;

    public function __construct(int $id, float $order_cost, string $order_status, string $order_date)
    {
        $this->id = $id;
        $this->order_cost = $order_cost;
        $this->order_status = $order_status;
        $this->order_date = $order_date;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderCost(): float
    {
        return $this->order_cost;
    }

    public function getOrderStatus(): string
    {
        return $this->order_status;
    }

    public function getOrderDate(): string
    {
        return $this->order_date;
    }
}