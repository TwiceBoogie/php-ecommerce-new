<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Http\Request;
use Sebastian\PhpEcommerce\Services\ShopService;
use Sebastian\PhpEcommerce\Views\Models\ShopViewModel;
use Sebastian\PhpEcommerce\Views\View;

class ShopController
{

    private ShopService $shopService;

    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }

    public function index(Request $request): bool|string
    {
        $isAdmin = $request->isAdmin();
        $isAuthenticated = $request->isAuthenticated();
        $products = $this->shopService->getAllProduct();

        $shopViewModel = new ShopViewModel($isAdmin, $isAuthenticated, $products);

        return View::render('shop.index', [
            'viewModel' => $shopViewModel
        ]);
    }

    public function show(Request $request, string $id): bool|string
    {
        $isAdmin = $request->isAdmin();
        $isAuthenticated = $request->isAuthenticated();
        $product = $this->shopService->getProduct($id);

        $shopViewModel = new ShopViewModel($isAdmin, $isAuthenticated, [], $product);

        return View::render('shop.single', [
            'viewModel' => $shopViewModel
        ]);
    }
}