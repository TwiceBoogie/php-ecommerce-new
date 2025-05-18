<?php

namespace Sebastian\PhpEcommerce\Providers;

use Pimple\ServiceProviderInterface;
use Sebastian\PhpEcommerce\Controllers\OrderController;
use Sebastian\PhpEcommerce\Mapper\OrderMapper;
use Sebastian\PhpEcommerce\Repository\OrderRepository;
use Sebastian\PhpEcommerce\Services\OrderService;
use Sebastian\PhpEcommerce\Services\Impl\OrderServiceImpl;

class OrderServiceProvider implements ServiceProviderInterface
{
    public function register(\Pimple\Container $c): void
    {
        $c[OrderRepository::class] = fn($c) => new OrderRepository($c['db']);

        $c[OrderMapper::class] = fn($c) => new OrderMapper();

        $c[OrderService::class] = fn($c) =>
            new OrderServiceImpl(
                $c[OrderRepository::class],
                $c[OrderMapper::class]
            );

        $c[OrderController::class] = fn($c) => new OrderController($c[OrderService::class]);
    }
}