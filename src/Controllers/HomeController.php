<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Services\HomeService;
use Sebastian\PhpEcommerce\Services\SecureSession;
use function Sebastian\PhpEcommerce\Helpers\app;
use Sebastian\PhpEcommerce\Views\View;

class HomeController
{

    private HomeService $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function index()
    {
        $isAdmin = SecureSession::get('user_id') !== null && app('user')->isAdmin();
        $keyboards = $this->homeService->getProductsByCategory('keyboards', 4);
        $mice = $this->homeService->getProductsByCategory('mice', 4);

        return View::render('home.index', [
            'keyboards' => $keyboards,
            'mice' => $mice,
            'isAdmin' => $isAdmin,
        ]);
    }
}