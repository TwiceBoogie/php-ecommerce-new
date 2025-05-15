<?php

namespace Sebastian\PhpEcommerce\Http\Request;

class AddToCartRequest
{
    private array $errors = [];
    private ?int $productId = null;
    private ?int $productQuantity = null;

    public function __construct(array $input)
    {
        $this->productId = (isset($input['productId']) && is_numeric($input['productId']))
            ? (int) $input['productId'] : null;
        $this->productQuantity = (isset($input['productQuantity']) && is_numeric($input['productQuantity']))
            ? (int) $input['productQuantity'] : null;

        $this->validate();
    }

    private function validate(): void
    {
        if ($this->productId === null || $this->productId <= 0) {
            $this->errors['productId'] = 'Valid product Id is required';
        }
        if ($this->productQuantity === null || $this->productQuantity <= 0) {
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
}