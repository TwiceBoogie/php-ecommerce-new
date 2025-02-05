<?php

namespace Sebastian\PhpEcommerce\Services;

class Container
{
    protected static $instance = null;

    public static function setContainer(\Pimple\Container $container)
    {
        self::$instance = $container;
    }

    public static function getInstance()
    {
        return self::$instance;
    }
}