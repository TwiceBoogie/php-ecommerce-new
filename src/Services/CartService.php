<?php

namespace Sebastian\PhpEcommerce\Services;

use Sebastian\PhpEcommerce\DTO\ResponseDTO;
use Sebastian\PhpEcommerce\DTO\CartDTO;
use Sebastian\PhpEcommerce\Http\Request\AddToCartRequest;

interface CartService
{
    /**
     * Summary of getCart
     * @return CartDTO[]
     */
    public function getCart(): array;
    public function addToCart(AddToCartRequest $request): ResponseDTO;
    public function removeFromCart(int $productId, int $quantity): void;
    public function clearCart(string $identifer): void;
}