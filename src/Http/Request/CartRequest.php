<?php

namespace Sebastian\PhpEcommerce\Http\Request;

class CartRequest
{
    private array $errors = [];
    private int $productId;
    private int $productQuantity;
    private string $operation;

    public function __construct(array $input)
    {
        $this->productId = (isset($input['productId']) && is_numeric($input['productId']))
            ? (int) $input['productId'] : 0;
        $this->productQuantity = (isset($input['productQuantity']) && is_numeric($input['productQuantity']))
            ? (int) $input['productQuantity'] : 0;
        $this->operation = in_array($input['operation'] ?? 'add', ['add', 'remove']) ? $input['operation'] : 'add';

        $this->validate();
    }

    private function validate(): void
    {
        if ($this->productId <= 0) {
            $this->errors['productId'] = 'Valid product Id is required';
        }
        if ($this->productQuantity <= 0) {
            $this->errors['productQuantity'] = 'Quantity must be at least 1';
        }
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function getProductQuantity(): ?int
    {
        return $this->productQuantity;
    }

    public function getOperation(): string
    {
        return $this->operation;
    }
}