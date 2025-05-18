<?php

// Load the autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use Sebastian\PhpEcommerce\Middleware\InjectAuthContextMiddleware;
use Sebastian\PhpEcommerce\Routing\Router;

// Bootstrap the application
$container = require_once __DIR__ . '/../bootstrap/app.php';

$router = $container[Router::class];
$router->loadCachedRoutes();
// Load routes
$router->addGlobalMiddlewares(InjectAuthContextMiddleware::class);
require_once __DIR__ . '/../src/Helpers/HelperFunctions.php';

// Get the current path and method
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
// Handle the request
$router->handleRequest($path, $method);
