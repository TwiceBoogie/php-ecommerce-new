<?php

// Load the autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Bootstrap the application
$config = require __DIR__ . '/../bootstrap/app.php';

// Load routes
require_once __DIR__ . '/../src/Helpers/HelperFunctions.php';

// Get the current path and method
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Handle the request
$router->handleRequest($path, $method);
