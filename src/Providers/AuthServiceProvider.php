<?php

namespace Sebastian\PhpEcommerce\Providers;

use Pimple\ServiceProviderInterface;
use Sebastian\PhpEcommerce\Controllers\AccountController;
use Sebastian\PhpEcommerce\Mapper\UserMapper;
use Sebastian\PhpEcommerce\Repository\UserRepository;
use Sebastian\PhpEcommerce\Repository\LoginRepository;

use Sebastian\PhpEcommerce\Services\CartService;
use Sebastian\PhpEcommerce\Services\RegisterService;
use Sebastian\PhpEcommerce\Services\LoginService;
use Sebastian\PhpEcommerce\Services\AuthService;
use Sebastian\PhpEcommerce\Services\UserService;

use Sebastian\PhpEcommerce\Services\Impl\AuthServiceImpl;
use Sebastian\PhpEcommerce\Services\Impl\RegisterServiceImpl;
use Sebastian\PhpEcommerce\Services\Impl\LoginServiceImpl;
use Sebastian\PhpEcommerce\Services\Impl\UserServiceImpl;

use Sebastian\PhpEcommerce\Controllers\LoginController;
use Sebastian\PhpEcommerce\Controllers\RegisterController;

class AuthServiceProvider implements ServiceProviderInterface
{
    public function register(\Pimple\Container $c): void
    {
        // Repositories
        $c[UserRepository::class] = fn($c) => new UserRepository($c['db']);
        $c[LoginRepository::class] = fn($c) => new LoginRepository($c['db']);

        // Mapper
        $c[UserMapper::class] = fn($c) => new UserMapper();

        // Services
        $c[RegisterService::class] = fn($c) =>
            new RegisterServiceImpl(
                $c[UserRepository::class],
                $c[CartService::class]
            );
        $c[LoginService::class] = fn($c) =>
            new LoginServiceImpl(
                $c[LoginRepository::class],
                $c[UserRepository::class],
                $c[CartService::class]
            );
        $c[AuthService::class] = fn($c) => new AuthServiceImpl($c[UserRepository::class]);
        $c[UserService::class] = fn($c) => new UserServiceImpl($c[UserRepository::class], $c[UserMapper::class]);

        // Controllers
        $c[RegisterController::class] = fn($c) => new RegisterController($c[RegisterService::class]);
        $c[LoginController::class] = fn($c) => new LoginController($c[LoginService::class]);
        $c[AccountController::class] = fn($c) => new AccountController($c[UserService::class]);
    }
}