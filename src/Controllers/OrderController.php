<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Services\OrderService;
use Sebastian\PhpEcommerce\Services\SecureSession;
use function Sebastian\PhpEcommerce\Helpers\app;
use Sebastian\PhpEcommerce\Views\View;

class OrderController
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $isAdmin = SecureSession::get('user_id') !== null && app('user')->isAdmin();
        $order = $this->orderService->getOrders();
    }
}