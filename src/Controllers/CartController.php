<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Http\Request;
use Sebastian\PhpEcommerce\Http\Request\CartRequest;
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
        $addToCartRequest = new CartRequest($request->getBody());
        $response = $this->cartService->updateCart($addToCartRequest);
        return Response::send($response->toArray(), 200);
    }

    public function clear(Request $request)
    {

        return Response::send(['message' => 'Cart cleared'], 200);
    }
}