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
        $isAdmin = $request->isAdmin;
        $products = $this->shopService->getAllProduct();

        $shopViewModel = new ShopViewModel($isAdmin, $products);

        return View::render('shop.index', [
            'shop' => $shopViewModel
        ]);
    }

    public function show(Request $request, string $id): bool|string
    {
        $isAdmin = $request->isAdmin;
        $product = $this->shopService->getProduct($id);

        $shopViewModel = new ShopViewModel($isAdmin, [], $product);

        return View::render('shop.single', [
            'shop' => $shopViewModel
        ]);
    }
}