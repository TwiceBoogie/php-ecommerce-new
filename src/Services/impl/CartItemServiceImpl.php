<?php

namespace Sebastian\PhpEcommerce\Services\Impl;

use Sebastian\PhpEcommerce\Mapper\CartItemMapper;
use Sebastian\PhpEcommerce\Repository\CartItemRepository;
use Sebastian\PhpEcommerce\Services\CartItemService;
use Sebastian\PhpEcommerce\Services\SecureSession;

class CartItemServiceImpl implements CartItemService
{
    private CartItemRepository $cartItemRepository;
    private CartItemMapper $mapper;

    public function __construct(
        CartItemRepository $cartItemRepository,
        CartItemMapper $mapper
    ) {
        $this->cartItemRepository = $cartItemRepository;
        $this->mapper = $mapper;
    }

    public function applyDelta(array $cart, array $product, int $delta): array
    {
        $userId = SecureSession::get('userId');
        $productId = $product['id'];
        $price = $product['price'];

        foreach ($cart['items'] as $i => &$item) {
            if ($item['productId'] === $productId) {
                $originalQty = $item['quantity'];
                $newQty = $originalQty + $delta;

                if ($newQty <= 0) { // remove item from cart
                    unset($sessionCart['items'][$i]);
                    $cart['totalQuantity'] -= $originalQty;
                    $cart['totalCost'] -= $originalQty * $price;

                    if ($userId && isset($item['id'])) {
                        $this->cartItemRepository->delete($item['id']);
                    }

                } else {
                    $item['quantity'] = $newQty;
                    $cart['totalQuantity'] += $newQty;
                    $cart['totalCost'] += $price * $newQty;

                    if ($userId && isset($item['id'])) {
                        $this->cartItemRepository->save([
                            'id' => $item['id'],
                            'quantity' => $item['quantity']
                        ]);
                    }
                }

                return $cart;
            }
        }

        return $cart; // no matching product
    }

    public function insertItem(array $cart, array $product, int $quantity): array
    {
        $userId = SecureSession::get('userId');
        $cartId = $cart['id'];
        $productId = $product['id'];
        $price = $product['price'];
        $newItem = [
            'productId' => $product['id'],
            'quantity' => $quantity
        ];

        if ($userId) {
            $cartItem = $this->cartItemRepository->save([
                'cart_id' => $cartId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
            $newItem['id'] = $cartItem['id'];
        }

        $cart['items'][] = $newItem;
        $cart['totalQuantity'] += $quantity;
        $cart['totalCost'] += $price * $quantity;

        return $cart;
    }

    public function cartHasProduct(array $cart, int $productId): bool
    {
        foreach ($cart['items'] as $item) {
            if ($item['productId'] === $productId) {
                return true;
            }
        }
        return false;
    }

    public function getCartItemsByCartId(int $cartId): array
    {
        return $this->cartItemRepository->getCartItemsByCartId($cartId);
    }

    public function createGuestCartItems(array $productProjections, array $quantities): array
    {
        $items = [];

        foreach ($productProjections as $idx => $product) {
            $productId = $product->id;
            $quantity = $quantities[$productId];
            $items[] = [
                'id' => $idx, // since guest don't have a cart in the db
                'quantity' => $quantity,
                'product' => $product->toArray()
            ];
        }

        return $this->mapper->mapToCartItemDTOArray($items);
    }
}