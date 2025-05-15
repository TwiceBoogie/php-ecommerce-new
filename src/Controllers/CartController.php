<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Http\Request;
use Sebastian\PhpEcommerce\Http\Request\AddToCartRequest;
use Sebastian\PhpEcommerce\Services\CartService;
use Sebastian\PhpEcommerce\Services\Response;
use Sebastian\PhpEcommerce\Views\Models\CartViewModel;
use Sebastian\PhpEcommerce\Views\View;

class CartController
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        $cart = $this->cartService->getCart();
        $isAdmin = $request->isAdmin();
        $isAuthenticated = $request->isAuthenticated();
        $cart = $this->cartService->getCart();
        $cartViewModel = new CartViewModel($isAdmin, $isAuthenticated, $cart);
        return View::render('cart.index', [
            'viewModel' => $cartViewModel
        ]);
    }

    public function add(Request $request)
    {
        $addToCartRequest = new AddToCartRequest($request->getBody());
        $response = $this->cartService->addToCart($addToCartRequest);
        return Response::send([
            'message' => 'Product added to cart',
        ], 200);
    }

    public function clear(Request $request)
    {
        unset($_SESSION['cart']);
        return Response::send(['message' => 'Cart cleared'], 200);
    }
}