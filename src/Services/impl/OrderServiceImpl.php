<?php

namespace Sebastian\PhpEcommerce\Services\Impl;

use Sebastian\PhpEcommerce\DTO\ResponseDTO;
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

    public function getOrders(): ResponseDTO
    {
        $userId = SecureSession::get('user_id');
        $orders = $this->orderRepository->getOrders($userId);
        $ordersDto = $this->orderMapper->mapToOrderDTOArray($orders);
        return new ResponseDTO(
            "success",
            "orders fetch successfully",
            $ordersDto
        );
    }
}