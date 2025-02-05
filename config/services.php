<?php

use Pimple\Container;
use Sebastian\PhpEcommerce\Controllers\RegisterController;
use Sebastian\PhpEcommerce\Models\Database;
use Sebastian\PhpEcommerce\Repository\BaseRepository;
use Sebastian\PhpEcommerce\Repository\CartRepository;
use Sebastian\PhpEcommerce\Repository\ProductRepository;
use Sebastian\PhpEcommerce\Repository\UserRepository;
use Sebastian\PhpEcommerce\Repository\LoginRepository;
use Sebastian\PhpEcommerce\Services\HomeService;
use Sebastian\PhpEcommerce\Services\ShopService;
use Sebastian\PhpEcommerce\Services\CartService;
use Sebastian\PhpEcommerce\Services\LoginService;
use Sebastian\PhpEcommerce\Services\RegisterService;
use Sebastian\PhpEcommerce\Services\Impl\HomeServiceImpl;
use Sebastian\PhpEcommerce\Services\Impl\ShopServiceImpl;
use Sebastian\PhpEcommerce\Services\Impl\CartServiceImpl;
use Sebastian\PhpEcommerce\Services\Impl\LoginServiceImpl;
use Sebastian\PhpEcommerce\Services\Impl\RegisterServiceImpl;
use Sebastian\PhpEcommerce\Controllers\ContactController;
use Sebastian\PhpEcommerce\Controllers\LoginController;
use Sebastian\PhpEcommerce\Controllers\CartController;
use Sebastian\PhpEcommerce\Controllers\HomeController;
use Sebastian\PhpEcommerce\Controllers\ShopController;
use Sebastian\PhpEcommerce\Mapper\ProductMapper;
use Sebastian\PhpEcommerce\Mapper\CartMapper;


$container = new Container();

$container['config'] = function () {
    $config = include __DIR__ . '/Config.php';
    if (!$config) {
        die('Config.php could not be loaded.');
    }
    return $config;
};

// Add database service
$container['db'] = function ($c) {
    $config = $c['config'];
    try {
        return new Database(
            $config['Database']['Type'],
            $config['Database']['Host'],
            $config['Database']['Name'],
            $config['Database']['User'],
            $config['Database']['Password']
        );
    } catch (PDOException $e) {
        die('Connection failed: ' . $e->getMessage());
    }
};

// $container[BaseRepository::class] = $container->factory(function ($c) {
//     return new BaseRepository($c['db'], ''); // No table specified for the base class
// });

$container[ProductRepository::class] = function ($c) {
    $db = $c['db'];
    return new ProductRepository($db);
};

$container[ProductMapper::class] = function ($c) {
    return new ProductMapper();
};

$container[CartMapper::class] = function ($c) {
    return new CartMapper();
};

$container[HomeService::class] = function ($c) {
    $productRepo = $c[ProductRepository::class];
    $productMapper = $c[ProductMapper::class];
    return new HomeServiceImpl($productRepo, $productMapper);
};

$container[HomeController::class] = function ($c) {
    $homeService = $c[HomeService::class];
    return new HomeController($homeService);
};

$container[ShopService::class] = function ($c) {
    $productRepo = $c[ProductRepository::class];
    $productMapper = $c[ProductMapper::class];
    return new ShopServiceImpl($productRepo, $productMapper);
};

$container[ShopController::class] = function ($c) {
    $shopService = $c[ShopService::class];
    return new ShopController($shopService);
};

$container[CartRepository::class] = function ($c) {
    $db = $c['db'];
    return new CartRepository($db);
};

$container[CartService::class] = function ($c) {
    $cartRepo = $c[CartRepository::class];
    $productRepo = $c[ProductRepository::class];
    $cartMapper = $c[CartMapper::class];
    return new CartServiceImpl($cartRepo, $productRepo, $cartMapper);
};

$container[CartController::class] = function ($c) {
    $cartService = $c[CartService::class];
    return new CartController($cartService);
};

$container[ContactController::class] = function ($c) {
    return new ContactController();
};

$container[UserRepository::class] = function ($c) {
    $db = $c['db'];
    return new UserRepository($db);
};

$container[LoginRepository::class] = function ($c) {
    $db = $c['db'];
    return new LoginRepository($db);
};

$container[LoginService::class] = function ($c) {
    $loginRepo = $c[LoginRepository::class];
    $userRepo = $c[UserRepository::class];
    $cartRepo = $c[CartRepository::class];
    return new LoginServiceImpl($loginRepo, $userRepo, $cartRepo);
};

$container[LoginController::class] = function ($c) {
    $loginService = $c[LoginService::class];
    return new LoginController($loginService);
};

$container[RegisterService::class] = function ($c) {
    $userRepo = $c[UserRepository::class];
    $cartService = $c[CartService::class];
    return new RegisterServiceImpl($userRepo, $cartService);
};

$container[RegisterController::class] = function ($c) {
    $registerService = $c[RegisterService::class];
    return new RegisterController($registerService);
};

return $container;