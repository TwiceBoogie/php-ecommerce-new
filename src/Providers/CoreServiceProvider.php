<?php

namespace Sebastian\PhpEcommerce\Providers;

use Pimple\ServiceProviderInterface;
use Sebastian\PhpEcommerce\Models\Database;
use Sebastian\PhpEcommerce\Routing\Router;

class CoreServiceProvider implements ServiceProviderInterface
{
    public function register(\Pimple\Container $c): void
    {
        $c['config'] = function () {
            $configPath = dirname(__DIR__, 2) . '/config/Config.php';
            $config = include $configPath;

            if (!$config) {
                die("Config.php could not be loaded.");
            }
            return $config;
        };

        $c['db'] = function ($c) {
            $config = $c['config'];
            try {
                return new Database(
                    $config['Database']['Type'],
                    $config['Database']['Host'],
                    $config['Database']['Name'],
                    $config['Database']['User'],
                    $config['Database']['Password']
                );
            } catch (\PDOException $e) {
                die('Connection failed: ' . $e->getMessage());
            }
        };
        // TODO: refactor the Router parameter. taking in everything
        $c[Router::class] = fn($c) => new Router($c);
    }
}