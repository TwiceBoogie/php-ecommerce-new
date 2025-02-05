<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Services\SecureSession;
use Sebastian\PhpEcommerce\Services\ShopService;
use function Sebastian\PhpEcommerce\Helpers\app;
use Sebastian\PhpEcommerce\Views\View;

class ShopController
{

    private ShopService $shopService;

    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }

    public function index()
    {
        $isAdmin = SecureSession::get('user_id') !== null && app('user')->isAdmin();
        $products = $this->shopService->getAllProduct();

        return View::render('shop.index', [
            'products' => $products,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function show(string $id)
    {
        $isAdmin = SecureSession::get('user_id') !== null && app('user')->isAdmin();
        $product = $this->shopService->getProduct($id);

        return View::render('shop.single', [
            'product' => $product,
            'isAdmin' => $isAdmin,
        ]);
    }
}