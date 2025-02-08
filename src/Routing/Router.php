<?php

namespace Sebastian\PhpEcommerce\Routing;

use Sebastian\PhpEcommerce\Controllers\AccountController;
use Sebastian\PhpEcommerce\Controllers\ContactController;
use Sebastian\PhpEcommerce\Controllers\LoginController;
use Sebastian\PhpEcommerce\Controllers\OrderController;
use Sebastian\PhpEcommerce\Controllers\RegisterController;
use Sebastian\PhpEcommerce\Middleware\AuthMiddleware;
use Sebastian\PhpEcommerce\Middleware\IsAdminMiddleware;
use Sebastian\PhpEcommerce\Middleware\RedirectIfAuthMiddleware;
use Sebastian\PhpEcommerce\Services\Response;
use Sebastian\PhpEcommerce\Controllers\HomeController;
use Sebastian\PhpEcommerce\Controllers\ShopController;
use Sebastian\PhpEcommerce\Controllers\CartController;
use Sebastian\PhpEcommerce\Views\View;
use Sebastian\PhpEcommerce\Http\Request;

class Router
{
    private array $routes = [];
    private array $middlewares = [];
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function register(string $path, string $controller, string $method, array $methods = ['GET'], array $middlewares = []): void
    {
        $this->routes[] = [
            'path' => $path,
            'controller' => $controller,
            'method' => $method,
            'http_methods' => $methods,
            'middlewares' => $middlewares,
        ];
    }

    public function loadCachedRoutes(): void
    {
        $cacheFile = __DIR__ . '/../../storage/cache/routes.php';
        if (file_exists($cacheFile)) {
            $this->routes = include $cacheFile;
        } else {
            $this->generateRouteCache();
        }
    }

    public function generateRouteCache(): void
    {
        // Dynamically scan controllers and collect routes
        $this->register('/', HomeController::class, 'index', ['GET'], [IsAdminMiddleware::class]);
        $this->register('/products', ShopController::class, 'index', ['GET'], [IsAdminMiddleware::class]);
        $this->register('/product/{id}', ShopController::class, 'show', ['GET'], [IsAdminMiddleware::class]);
        $this->register('/contact', ContactController::class, 'index', ['GET'], [AuthMiddleware::class]);
        $this->register('/login', LoginController::class, 'index', ['GET'], [RedirectIfAuthMiddleware::class]);
        $this->register('/register', RegisterController::class, 'index', ['GET'], [RedirectIfAuthMiddleware::class]);
        $this->register('/account', AccountController::class, 'index', ['GET'], [IsAdminMiddleware::class]);

        $this->register('/api/v1/cart', CartController::class, 'index', ['GET']);
        $this->register('/api/v1/cart/add', CartController::class, 'add', ['POST']);
        $this->register('/api/v1/cart/clear', CartController::class, 'clear', ['DELETE']);
        $this->register('/api/v1/auth/logout', LoginController::class, 'logout', ['GET']);
        $this->register('/api/v1/auth/login', LoginController::class, 'login', ['POST'], [RedirectIfAuthMiddleware::class]);
        $this->register('/api/v1/auth/register', RegisterController::class, 'register', ['POST'], [RedirectIfAuthMiddleware::class]);
        $this->register('/api/v1/orders', OrderController::class, 'index', ['GET'], [IsAdminMiddleware::class]);

        file_put_contents(
            __DIR__ . '/../../storage/cache/routes.php',
            '<?php return ' . var_export($this->routes, true) . ';'
        );
    }

    public function handleRequest($path, $method)
    {
        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches) && in_array($method, $route['http_methods'])) {
                array_shift($matches);

                $controller = $this->container[$route['controller']] ?? null;
                $action = $route['method'];

                if (!$controller) {
                    // Response::send(['error' => "Controller not found: {$route['controller']}"], 500);
                    return $this->handleNotFound($path);
                }

                if (!method_exists($controller, $action)) {
                    // Response::send(['error' => "Method '{$action}' not found in controller '{$route['controller']}'"], 500);
                    return $this->handleNotFound($path);
                }

                // Execute middleware before calling the controller
                $this->runMiddlewares($route['middlewares'], function () use ($controller, $action, $matches) {
                    // Create a proper Request object
                    $request = new Request();

                    // Pass $request to the controller
                    $response = $controller->$action($request, ...array_values($matches));

                    if (is_string($response)) {
                        echo $response;
                    } elseif (is_array($response) || is_object($response)) {
                        Response::send((array) $response);
                    } else {
                        Response::send(['error' => 'Invalid response type'], 500);
                    }
                });

                return;
            }
        }

        // Response::send(['error' => '404 - Page Not Found'], 404);
        return $this->handleNotFound($path);
    }

    private function handleNotFound(string $path)
    {
        // Check if the request is an API request
        if (str_starts_with($path, '/api/')) {
            Response::send(['error' => '404 - API Not Found'], 404);
        } else {
            // Render a custom 404 template for web users
            echo View::render('errors.404');
        }
    }


    private function runMiddlewares(array $middlewares, callable $next)
    {
        $stack = array_reverse($middlewares);

        $middlewareChain = array_reduce($stack, function ($next, $middleware) {
            return function ($request) use ($middleware, $next) {
                if (!$request instanceof Request) {
                    $request = new Request(); // ✅ Ensure it's always a Request object
                }

                $middlewareInstance = $this->container[$middleware] ?? new $middleware();
                return $middlewareInstance->handle($request, $next);
            };
        }, function ($request) use ($next) {
            return $next($request);
        });

        return $middlewareChain(new Request()); // ✅ Ensure it starts with a Request object
    }
}