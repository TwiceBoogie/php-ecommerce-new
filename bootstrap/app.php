<?php


use Pimple\Container;

use Sebastian\PhpEcommerce\Providers\{
    CoreServiceProvider,
    AuthServiceProvider,
    CartServiceProvider,
    ShopServiceProvider,
    OrderServiceProvider,
    HomeServiceProvider,
    ContactServiceProvider,
    MiddlewareServiceProvider
};
use Sebastian\PhpEcommerce\Services\SecureSession;

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
// Load configuration
$config = $container['config'];

// Set error handling based on config
if ($config['App']['Debug']) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Set up logging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../storage/logs/php_errors.log');

// Start the session using SecureSession
SecureSession::startSession($config);

return $container;