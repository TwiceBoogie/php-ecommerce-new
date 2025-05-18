<?php

namespace Sebastian\PhpEcommerce\Services\Impl;

use Sebastian\PhpEcommerce\DTO\ResponseDTO;
use Sebastian\PhpEcommerce\Http\Request\AddToCartRequest;
use Sebastian\PhpEcommerce\Mapper\CartMapper;
use Sebastian\PhpEcommerce\Repository\CartItemRepository;
use Sebastian\PhpEcommerce\Services\CartService;
use Sebastian\PhpEcommerce\Repository\CartRepository;
use Sebastian\PhpEcommerce\Repository\ProductRepository;
use Sebastian\PhpEcommerce\Services\SecureSession;

/**
 * Session Cart Format:
 * [
 *     'cartId' => id,
 *     'items' => [
 *          [
 *          'id' => id, // cartItemId
 *          'productId' => id,
 *          'quantity' => quantity,
 *          ]
 *      ],
 *     'totalAmount' => amount,
 *     'totalCound' => total
 * ]
 */
class CartServiceImpl implements CartService
{
    private CartRepository $cartRepository;
    private CartItemRepository $cartItemRepository;
    private ProductRepository $productRepository;
    private CartMapper $mapper;

    public function __construct(
        CartRepository $cartRepository,
        ProductRepository $productRepository,
        CartItemRepository $cartItemRepository,
        CartMapper $mapper
    ) {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->cartItemRepository = $cartItemRepository;
        $this->mapper = $mapper;
    }

    public function getCart(): array
    {
        $user = SecureSession::get('user');
        $userId = $user['id'] ?? null;
        $sessionCart = $user['cart'] ?? [];
        $cart = [];
        if (!$userId && !empty($sessionCart)) {
            $cart = $this->getGuestCart($sessionCart);
        }
        if ($userId) {
            $cart = $this->getUserCart($userId);
        }
        return $this->mapper->mapToCartDTOArray($cart);
    }

    public function addToCart(AddToCartRequest $request): ResponseDTO
    {
        $validationError = $this->validateAddToCartRequest($request);
        if ($validationError) {
            return $validationError;
        }

        $user = SecureSession::get('user');
        $userId = $user['id'] ?? null;
        $sessionCart = $user['cart'] ?? [
            'id' => 0,
            'items' => [],
            'totalAmount' => 0,
            'totalQuantity' => 0
        ];

        $productId = $request->getProductId();
        $quantity = $request->getProductQuantity();

        $newQuantity = 0;
        foreach ($sessionCart['items'] as $item) {
            if ($item['productId'] === $productId) {
                $newQuantity = $item['quantity'] + $quantity;
                break;
            }
        }

        // check stock
        if (!$this->productRepository->productStockAvailable($productId, $newQuantity)) {
            return new ResponseDTO(
                "error",
                "Product out of stock",
                [],
                [],
                400
            );
        }

        $sessionCart = $this->addToCartItem($sessionCart, $productId, $quantity);
        SecureSession::set('user', ['cart' => $sessionCart]);
        if (!$userId) {
            return new ResponseDTO(
                "success",
                "Product added to cart",
                [],
                [],
                200
            );
        }
        // if productId exist then cartItemId exist as well

        // if cartItemId does not exist then create a new entry
        return new ResponseDTO(
            "success",
            "Product added to cart",
            [],
            [],
            200
        );
    }

    public function removeFromCart(int $productId, int $quantity): void
    {

    }

    public function clearCart(string $identifer): void
    {

    }

    private function getGuestCart(array $sessionCart): array
    {
        // grab product ids
        $productIds = array_keys($sessionCart);
        // fetch data using product ids
        $products = $this->productRepository->getProductByIds($productIds);

        $cart = [];
        $index = 1;
        foreach ($products as $product) {
            $productId = $product['id'];
            $cart[] = [
                'id' => $index++, // since guest user don't have data in the cart table
                'quantity' => $sessionCart[$productId],
                'product' => $product
            ];
        }
        return $cart;
    }

    private function getUserCart(int $userId): array
    {
        $cartRows = $this->cartRepository->getCartByUserId($userId);
        $productIds = array_column($cartRows, 'product_id');
        $products = $this->productRepository->getProductByIds($productIds);
        $cart = [];
        $productMap = [];

        foreach ($products as $product) {
            $productMap[$product['id']] = $product;
        }

        foreach ($cartRows as $row) {
            $productId = $row['productId'];
            $cart[] = [
                'id' => $row['id'],
                'quantity' => $row['quantity'],
                'product' => $productMap[$productId]
            ];
        }
        return $cart;
    }

    private function validateAddToCartRequest(AddToCartRequest $request): ?ResponseDTO
    {
        if ($request->fails()) {
            return new ResponseDTO(
                "error",
                "Validation error",
                [],
                $request->errors(),
                400
            );
        }
        return null;
    }

    private function addToCartItem(array $cart, int $productId, int $qtyToAdd): array
    {
        $found = false;
        foreach ($cart['items'] as &$item) {
            if ($item['productId'] === $productId) {
                $item['quantity'] += $qtyToAdd;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart['items'][] = [
                'productId' => $productId,
                'quantity' => $qtyToAdd,
            ];
        }
        $cart['totalQuantity'] += $qtyToAdd;

        return $cart;
    }
}