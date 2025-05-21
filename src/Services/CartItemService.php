<?php

namespace Sebastian\PhpEcommerce\Services;

use Sebastian\PhpEcommerce\Repository\Projections\ProductProjection;
use Sebastian\PhpEcommerce\DTO\CartItemDTO;

interface CartItemService
{
    public function applyDelta(array $cart, array $product, int $delta): array;
    public function insertItem(array $cart, array $product, int $quantity): array;
    public function cartHasProduct(array $cart, int $productId): bool;
    public function getCartItemsByCartId(int $cartId): array;
    /**
     * Summary of createGuestCartItems
     * @param ProductProjection[] $productProjections
     * @param array $quantities
     * @return CartItemDTO[] An array of CartItemDTO's
     */
    public function createGuestCartItems(array $productProjections, array $quantities): array;
}