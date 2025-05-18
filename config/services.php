<?php

use Pimple\Container;

use Sebastian\PhpEcommerce\Providers\AuthServiceProvider;
use Sebastian\PhpEcommerce\Providers\ContactServiceProvider;
use Sebastian\PhpEcommerce\Providers\CoreServiceProvider;
use Sebastian\PhpEcommerce\Providers\HomeServiceProvider;
use Sebastian\PhpEcommerce\Providers\MiddlewareServiceProvider;
use Sebastian\PhpEcommerce\Providers\OrderServiceProvider;
use Sebastian\PhpEcommerce\Providers\ShopServiceProvider;
use Sebastian\PhpEcommerce\Providers\CartServiceProvider;

// Lazy-loading services by using closures.
// while the container is re-instantiated,
// services are only instantiated when accessed
$container = new Container();

$container->register(new CoreServiceProvider());
$container->register(new AuthServiceProvider());
$container->register(new CartServiceProvider());
$container->register(new ShopServiceProvider());
$container->register(new OrderServiceProvider());
$container->register(new HomeServiceProvider());
$container->register(new ContactServiceProvider());
$container->register(new MiddlewareServiceProvider());

return $container;