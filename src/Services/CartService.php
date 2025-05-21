<?php

namespace Sebastian\PhpEcommerce\Services;

use Sebastian\PhpEcommerce\DTO\ResponseDTO;
use Sebastian\PhpEcommerce\DTO\CartDTO;
use Sebastian\PhpEcommerce\Http\Request\CartRequest;

interface CartService
{
    public function getCart(): CartDTO;
    public function updateCart(CartRequest $request): ResponseDTO;
    public function removeFromCart(int $productId, int $quantity): void;
    public function clearCart(string $identifer): void;
}