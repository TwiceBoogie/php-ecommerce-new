<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Http\Request;
use Sebastian\PhpEcommerce\Services\OrderService;
use Sebastian\PhpEcommerce\Services\Response;

class OrderController
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $response = $this->orderService->getOrders();

        return Response::send($response->toArray(), $response->getStatusCode());
    }
}