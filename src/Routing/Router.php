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
    private array $globalMiddlewares = [];
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
        $this->register('/', HomeController::class, 'index', ['GET']);
        $this->register('/products', ShopController::class, 'index', ['GET']);
        $this->register('/product/{id}', ShopController::class, 'show', ['GET']);
        $this->register('/contact', ContactController::class, 'index', ['GET']);
        $this->register('/login', LoginController::class, 'index', ['GET'], [RedirectIfAuthMiddleware::class]);
        $this->register('/register', RegisterController::class, 'index', ['GET'], [RedirectIfAuthMiddleware::class]);
        $this->register('/account', AccountController::class, 'index', ['GET'], [AuthMiddleware::class]);
        $this->register('/cart', CartController::class, 'index', ['GET']);

        $this->register('/api/v1/cart', CartController::class, 'index', ['GET']);
        $this->register('/api/v1/cart/add', CartController::class, 'add', ['POST']);
        $this->register('/api/v1/cart/clear', CartController::class, 'clear', ['DELETE']);
        $this->register('/api/v1/auth/logout', LoginController::class, 'logout', ['GET']);
        $this->register('/api/v1/auth/login', LoginController::class, 'login', ['POST'], [RedirectIfAuthMiddleware::class]);
        $this->register('/api/v1/auth/register', RegisterController::class, 'register', ['POST'], [RedirectIfAuthMiddleware::class]);
        $this->register('/api/v1/orders', OrderController::class, 'index', ['GET'], [AuthMiddleware::class]);
        $this->register('/api/v1/user/settings/update', AccountController::class, 'updateUserDetails', ['PUT'], [AuthMiddleware::class]);
        $this->register('/api/v1/user/settings/update/email', AccountController::class, 'updateEmail', ['PUT'], [AuthMiddleware::class]);

        file_put_contents(
            __DIR__ . '/../../storage/cache/routes.php',
            '<?php return ' . var_export($this->routes, true) . ';'
        );
    }

    public function handleRequest($path, $method)
    {
        foreach ($this->routes as $route) {
            // converts '/products/{id}' into '/product/([^/]+)' that way you can use preg_match() and extract dynamic values
            $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches) && in_array($method, $route['http_methods'])) {
                array_shift($matches);

                $controller = $this->container[$route['controller']] ?? null;
                $action = $route['method']; // class method name

                if (!$controller) {
                    // Response::send(['error' => "Controller not found: {$route['controller']}"], 500);
                    return $this->handleNotFound($path);
                }

                if (!method_exists($controller, $action)) {
                    // Response::send(['error' => "Method '{$action}' not found in controller '{$route['controller']}'"], 500);
                    return $this->handleNotFound($path);
                }
                $allMiddlewares = array_merge($this->globalMiddlewares, $route['middlewares']);
                $request = new Request();
                // Execute middleware before calling the controller
                $this->runMiddlewares($allMiddlewares, function (Request $request) use ($controller, $action, $matches) {

                    // Pass $request to the controller
                    $response = $controller->$action($request, ...array_values($matches));

                    if (is_string($response)) {
                        echo $response;
                    } elseif (is_array($response) || is_object($response)) {
                        Response::send((array) $response);
                    } else {
                        Response::send(['error' => 'Invalid response type'], 500);
                    }
                }, $request);

                return;
            }
        }

        // Response::send(['error' => '404 - Page Not Found'], 404);
        return $this->handleNotFound($path);
    }

    public function addGlobalMiddlewares(string ...$middleware)
    {
        $this->globalMiddlewares = array_merge($this->globalMiddlewares, $middleware);
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

    // recurseive/stack-based execuationn.
    private function runMiddlewares(array $middlewares, callable $next, Request $request)
    {
        // reverse array bcs array_reduce() builds chain from last to first
        $stack = array_reverse($middlewares);

        $middlewareChain = array_reduce($stack, function ($next, $middleware) {
            return function (Request $request) use ($middleware, $next) {
                $middlewareInstance = $this->container[$middleware];
                return $middlewareInstance->handle($request, $next);
            };
        }, function (Request $request) use ($next) { // fallback or 'final' callable if $stack is empty which skipps the callback
            return $next($request);
        });

        return $middlewareChain($request);
    }
}