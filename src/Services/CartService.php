<?php

namespace Sebastian\PhpEcommerce\Services;

interface CartService
{
    public function getCart();
    public function addToCart(int $productId, int $quantity): void;
    public function removeFromCart(int $productId, int $quantity): void;
    public function clearCart(string $identifer): void;
}