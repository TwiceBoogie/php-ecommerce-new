<?php

namespace Sebastian\PhpEcommerce\Services\Impl;

use Sebastian\PhpEcommerce\Mapper\OrderMapper;
use Sebastian\PhpEcommerce\Repository\OrderRepository;
use Sebastian\PhpEcommerce\Services\OrderService;
use Sebastian\PhpEcommerce\Services\SecureSession;

class OrderServiceImpl implements OrderService
{
    private OrderRepository $orderRepository;
    private OrderMapper $orderMapper;

    public function __construct(OrderRepository $orderRepository, OrderMapper $orderMapper)
    {
        $this->orderRepository = $orderRepository;
        $this->orderMapper = $orderMapper;
    }

    public function getOrders(): array
    {
        $userId = SecureSession::get('user_id');
        $orders = $this->orderRepository->getOrders($userId);
        return $this->orderMapper->mapToOrderDTOArray($orders);
    }
}