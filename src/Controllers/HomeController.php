<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Http\Request;
use Sebastian\PhpEcommerce\Services\HomeService;
use Sebastian\PhpEcommerce\Views\Models\HomeViewModel;
use Sebastian\PhpEcommerce\Views\View;

class HomeController
{

    private HomeService $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function index(Request $request): bool|string
    {
        $isAdmin = $request->isAdmin;
        $keyboards = $this->homeService->getProductsByCategory('keyboards', 4);
        $mice = $this->homeService->getProductsByCategory('mice', 4);

        $homeViewModel = new HomeViewModel($isAdmin, $mice, $keyboards);

        return View::render('home.index', [
            'home' => $homeViewModel
        ]);
    }
}