<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Services\CartService;
use Sebastian\PhpEcommerce\Services\Response;
use function Sebastian\PhpEcommerce\Helpers\app;

class CartController
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $_SESSION['cart'] ?? [];
        return Response::send(['cart' => $cart], 200);
    }

    public function add()
    {
        $input = file_get_contents('php://input');
        $decodedInput = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return Response::send(['error' => 'Invalid JSON input'], 400);
        }

        $product_id = $decodedInput['product_id'] ?? null;
        $quantity = $decodedInput['product_quantity'] ?? 1;

        if (!$product_id) {
            return Response::send(['error' => 'Product ID is required'], 400);
        }

        $this->cartService->addToCart($product_id, $quantity);

        return Response::send([
            'message' => 'Product added to cart',
        ], 200);
    }

    public function clear()
    {
        unset($_SESSION['cart']);
        return Response::send(['message' => 'Cart cleared'], 200);
    }
}