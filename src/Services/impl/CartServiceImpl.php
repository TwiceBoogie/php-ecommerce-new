<?php

namespace Sebastian\PhpEcommerce\Services\Impl;

use Sebastian\PhpEcommerce\DTO\CartDTO;
use Sebastian\PhpEcommerce\DTO\ResponseDTO;
use Sebastian\PhpEcommerce\Http\Request\CartRequest;
use Sebastian\PhpEcommerce\Mapper\CartMapper;
use Sebastian\PhpEcommerce\Services\CartItemService;
use Sebastian\PhpEcommerce\Services\CartService;
use Sebastian\PhpEcommerce\Repository\CartRepository;
use Sebastian\PhpEcommerce\Repository\ProductRepository;
use Sebastian\PhpEcommerce\Services\ProductService;
use Sebastian\PhpEcommerce\Services\SecureSession;

/**
 * Session Cart Format:
 * [
 *     'id' => cartId,
 *     'items' => [
 *         [
 *             'id' => cartItemId,
 *             'productId' => id,
 *             'quantity' => quantity,
 *         ]
 *     ],
 *     'totalAmount' => amount,
 *     'totalQuantity' => total
 * ]
 *
 * DTO Cart Format:
 * [
 *     'id' => ...,
 *     'totalAmount' => ...,
 *     'totalQuantity' => ...,
 *     'items' => [
 *         [
 *             'id' => ...,
 *             'quantity' => ...,
 *             'product' => [
 *                 'id' => ...,
 *                 'name' => ...,
 *                 'category' => ...,
 *                 'price' => ...
 *             ]
 *         ]
 *     ]
 * ]
 */
class CartServiceImpl implements CartService
{
    private CartRepository $cartRepository;
    private CartItemService $cartItemService;
    private ProductService $productService;
    private CartMapper $mapper;

    public function __construct(
        CartRepository $cartRepository,
        ProductService $productService,
        CartItemService $cartItemService,
        CartMapper $mapper
    ) {
        $this->cartRepository = $cartRepository;
        $this->productService = $productService;
        $this->cartItemService = $cartItemService;
        $this->mapper = $mapper;
    }

    public function getCart(): CartDTO
    {
        $userId = SecureSession::get('userId');
        $sessionCart = SecureSession::get('cart') ?? [
            'id' => 0,
            'items' => [],
            'totalCost' => 0,
            'totalQuantity' => 0
        ];
        $cart = [];

        if (!$userId && !empty($sessionCart['items'])) {
            $cart = $this->getGuestCart($sessionCart);
        }
        if ($userId) {
            $cart = $this->getUserCart($userId);
        }
        return $this->mapper->mapToCartDTO($cart);
    }

    public function updateCart(CartRequest $request): ResponseDTO
    {
        $validationError = $this->validateCartRequest($request);
        if ($validationError) {
            return $validationError;
        }

        $productId = $request->getProductId();
        $delta = $request->getOperation() === 'remove'
            ? -$request->getProductQuantity()
            : $request->getProductQuantity();

        if ($delta > 0) { // its adding to the cart
            if (!$this->validateAndCheckStock($productId, $delta)) {
                return new ResponseDTO(
                    "error",
                    "Product out of stock",
                    [],
                    [],
                    400
                );
            }
        }

        $product = $this->productService->getProductProjectionById($productId);
        $sessionCart = $this->updateCartItem($product, $delta);
        SecureSession::set('cart', $sessionCart);

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
        $productIdAndQuantity = []; // [productId => quantity]
        foreach ($sessionCart['items'] as $item) {
            $productIdAndQuantity[$item['productId']] = $item['quantity'];
        }
        // fetch data using product ids
        $products = $this->productService->getProductProjectionByIds(array_keys($productIdAndQuantity));
        $cartItems = $this->cartItemService->createGuestCartItems($products, $productIdAndQuantity);

        $totalCost = 0;
        $totalQuantity = 0;
        foreach ($cartItems as $item) {
            $totalCost += $item->getQuantity() * $item->getProduct()->getPrice();
        }
        foreach ($products as $idx => $product) {
            $productId = $product->id;
            $cart['totalCost'] += $product->price * $productIdAndQuantity[$productId];
            $cart['totalQuantity'] += $productIdAndQuantity[$productId];
            $cart['items'][] = [
                'id' => $idx, // since guest user don't have data in the cart_item table
                'quantity' => $productIdAndQuantity[$productId],
                'product' => $product
            ];
        }
        return $cart;
    }

    private function getUserCart(int $userId): array
    {
        $cartDb = $this->cartRepository->getCartByUserId($userId)[0];
        $cartItems = $this->cartItemService->getCartItemsByCartId($cartDb['id']);
        $cart = [
            'id' => $cartDb['id'],
            'totalCost' => $cartDb['total_amount'],
            'totalQuantity' => $cartDb['total_quantity'],
            'items' => $cartItems
        ];
        return $cart;
    }

    private function updateCartItem(array $product, int $delta): array
    {
        $userId = SecureSession::get('userId');
        $sessionCart = SecureSession::get('cart') ?? [
            'id' => 0,
            'items' => [],
            'totalCost' => 0,
            'totalQuantity' => 0
        ];
        $productId = $product['id'];
        $cartId = $sessionCart['id'];

        // apply to an existing item or leave as is
        $updatedCart = $this->cartItemService->applyDelta($sessionCart, $product, $delta);

        if (!$this->cartItemService->cartHasProduct($updatedCart, $productId) && $delta > 0) {
            $updatedCart = $this->cartItemService->insertItem($updatedCart, $product, $delta);
        }

        if ($userId) {
            $this->cartRepository->save([
                'id' => $cartId,
                'total_quantity' => $updatedCart['totalQuantity'],
                'total_amount' => $updatedCart['totalCost']
            ]);
        }

        return $updatedCart;
    }

    private function validateAndCheckStock(int $productId, int $qtyToAdd): bool
    {
        $sessionCart = SecureSession::get('cart') ?? ['items' => []];
        $currentQty = 0;

        foreach ($sessionCart['items'] as $item) {
            if ($item['productId'] === $productId) {
                $currentQty = $item['quantity'];
                break;
            }
        }

        $newQty = $currentQty + $qtyToAdd;
        return $this->productService->isStockAvailable($productId, $newQty);
    }

    private function validateCartRequest(CartRequest $request): ?ResponseDTO
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
}