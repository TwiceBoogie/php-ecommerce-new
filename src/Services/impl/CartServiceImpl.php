<?php

namespace Sebastian\PhpEcommerce\Services\Impl;

use Sebastian\PhpEcommerce\Mapper\CartMapper;
use Sebastian\PhpEcommerce\Services\CartService;
use Sebastian\PhpEcommerce\Repository\CartRepository;
use Sebastian\PhpEcommerce\Repository\ProductRepository;
use Sebastian\PhpEcommerce\Services\SecureSession;

class CartServiceImpl implements CartService
{
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;
    private CartMapper $mapper;

    public function __construct(CartRepository $cartRepository, ProductRepository $productRepository, CartMapper $mapper)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->mapper = $mapper;
    }

    public function getCart()
    {
        $sessionId = SecureSession::getSessionId();
        $userId = SecureSession::get('user')['id'];
        if (!$userId) {
            $cart = $this->cartRepository->getCartBySessionId($sessionId);
            return;
        }
        return;
    }

    public function addToCart(int $productId, int $quantity): void
    {
        $sessionId = SecureSession::getSessionId();
        $userId = SecureSession::get('user')['id'];

        $column = $userId ? 'user_id' : 'session_id';
        $identifier = $userId ?? $sessionId;

        if ($this->productRepository->productStockAvailable($productId, $quantity)) {
            $cart = $this->cartRepository->getCart($identifier, $column);
            if (count($cart) === 0) {
                $this->productRepository->updateProductStock($productId, $quantity);
                $this->cartRepository->insertItemIntoCart($identifier, $column, $productId, $quantity);
            } else {
                $this->productRepository->updateProductStock($productId, $quantity);
                $this->cartRepository->updateCartItemQuantity($identifier, $column, $productId, $quantity);
            }
        }
    }

    public function removeFromCart(int $productId, int $quantity): void
    {

    }

    public function clearCart(string $identifer): void
    {

    }

    private function getCartIdentifier(): string
    {
        $sessionId = SecureSession::getSessionId();
        $userId = SecureSession::get('user')['id'];
        return $userId ?: $sessionId;
    }
}