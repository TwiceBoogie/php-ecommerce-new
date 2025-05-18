<?php

namespace Sebastian\PhpEcommerce\Providers;

use Pimple\ServiceProviderInterface;
use Sebastian\PhpEcommerce\Controllers\HomeController;
use Sebastian\PhpEcommerce\Mapper\ProductMapper;
use Sebastian\PhpEcommerce\Repository\ProductRepository;
use Sebastian\PhpEcommerce\Services\HomeService;
use Sebastian\PhpEcommerce\Services\Impl\HomeServiceImpl;

class HomeServiceProvider implements ServiceProviderInterface
{
    public function register(\Pimple\Container $c): void
    {
        $c[HomeService::class] = fn($c) =>
            new HomeServiceImpl(
                $c[ProductRepository::class],
                $c[ProductMapper::class]
            );

        $c[HomeController::class] = fn($c) => new HomeController($c[HomeService::class]);
    }
}