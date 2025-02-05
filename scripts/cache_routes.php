<?php

use Sebastian\PhpEcommerce\Routing\Router;

// Load the autoloader and application bootstrap
require_once __DIR__ . '/../vendor/autoload.php';
$container = require __DIR__ . '/../bootstrap/app.php';

// Generate and cache routes
$router = new Router($container);
$router->generateRouteCache();

echo "Routes cached successfully.\n";
