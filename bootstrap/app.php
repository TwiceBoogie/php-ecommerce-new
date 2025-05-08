<?php

use Sebastian\PhpEcommerce\Middleware\InjectAuthContextMiddleware;
use Sebastian\PhpEcommerce\Routing\Router;
use Sebastian\PhpEcommerce\Services\Container;
use Sebastian\PhpEcommerce\Services\SecureSession;

$container = require __DIR__ . '/../config/services.php';
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

Container::setContainer($container);

$router = new Router($container);
$router->loadCachedRoutes();
$router->addGlobalMiddlewares(InjectAuthContextMiddleware::class);