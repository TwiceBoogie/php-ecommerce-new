<?php

namespace Sebastian\PhpEcommerce\Providers;

use Pimple\ServiceProviderInterface;
use Sebastian\PhpEcommerce\Mapper\ProductMapper;
use Sebastian\PhpEcommerce\Repository\ProductRepository;
use Sebastian\PhpEcommerce\Services\ShopService;
use Sebastian\PhpEcommerce\Services\Impl\ShopServiceImpl;
use Sebastian\PhpEcommerce\Controllers\ShopController;

class ShopServiceProvider implements ServiceProviderInterface
{
    public function register(\Pimple\Container $c): void
    {
        $c[ProductRepository::class] = fn($c) => new ProductRepository($c['db']);
        $c[ProductMapper::class] = fn($c) => new ProductMapper();

        $c[ShopService::class] = fn($c) =>
            new ShopServiceImpl(
                $c[ProductRepository::class],
                $c[ProductMapper::class]
            );

        $c[ShopController::class] = fn($c) => new ShopController($c[ShopService::class]);
    }
}