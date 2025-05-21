<?php

namespace Sebastian\PhpEcommerce\Providers;

use Pimple\ServiceProviderInterface;
use Sebastian\PhpEcommerce\Controllers\CartController;
use Sebastian\PhpEcommerce\Mapper\CartMapper;
use Sebastian\PhpEcommerce\Repository\CartItemRepository;
use Sebastian\PhpEcommerce\Repository\CartRepository;
use Sebastian\PhpEcommerce\Repository\ProductRepository;
use Sebastian\PhpEcommerce\Services\CartService;
use Sebastian\PhpEcommerce\Services\CartItemService;
use Sebastian\PhpEcommerce\Services\Impl\CartItemServiceImpl;
use Sebastian\PhpEcommerce\Services\Impl\CartServiceImpl;

class CartServiceProvider implements ServiceProviderInterface
{
    public function register(\Pimple\Container $c): void
    {
        $c[CartRepository::class] = fn($c) => new CartRepository($c['db']);
        $c[CartItemRepository::class] = fn($c) => new CartItemRepository($c['db']);
        // $c[ProductRepository::class] = fn($c) => new ProductRepository($c['db']);
        $c[CartMapper::class] = fn($c) => new CartMapper();

        $c[CartItemService::class] = fn($c) => new CartItemServiceImpl($c[CartItemRepository::class]);
        $c[CartService::class] = fn($c) =>
            new CartServiceImpl(
                $c[CartRepository::class],
                $c[ProductRepository::class],
                $c[CartItemService::class],
                $c[CartMapper::class]
            );

        $c[CartController::class] = fn($c) => new CartController($c[CartService::class]);
    }
}