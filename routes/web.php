<?php

use Sebastian\PhpEcommerce\Controllers\CartController;
use Sebastian\PhpEcommerce\Controllers\HomeController;
use Sebastian\PhpEcommerce\Controllers\ShopController;
use Sebastian\PhpEcommerce\Services\Response;

$router = [
    '/' => function () {
        $controller = new HomeController();
        return $controller->index();
    },
    '/products' => function () {
        $controller = new ShopController();
        return $controller->index();
    },
    '/product/{id}' => function ($id) {
        $controller = new ShopController();
        return $controller->show($id);
    },
    '/api/v1/cart' => function () {
        $controller = new CartController();

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET': // View the cart
                return $controller->index();
            case 'POST': // Add to cart
                return $controller->add();
            // case 'DELETE': // Remove from cart
            //     return $controller->remove();
            default:
                return Response::send(['error' => 'Method not allowed'], 405);
        }
    },
    '/api/v1/cart/clear' => function () {
        $controller = new CartController();
        return $controller->clear();
    },
];

// Get the current path
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Check if the path exists in the router (including dynamic routes)
$found = false;

foreach ($router as $route => $callback) {
    // Replace dynamic placeholders (e.g., {id}) with a regex
    $routePattern = preg_replace('/\{[^\}]+\}/', '([^/]+)', $route);
    $routePattern = str_replace('/', '\/', $routePattern); // Escape slashes for regex

    // Match the current path against the route pattern
    if (preg_match('/^' . $routePattern . '$/', $path, $matches)) {
        $found = true;

        // Remove the first match (full path) and pass remaining matches as arguments
        array_shift($matches);
        echo call_user_func_array($callback, $matches);
        break;
    }
}

if (!$found) {
    http_response_code(404);
    echo "404 - Page Not Found";
}