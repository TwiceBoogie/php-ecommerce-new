<?php

namespace Sebastian\PhpEcommerce\Providers;

use Pimple\ServiceProviderInterface;
use Sebastian\PhpEcommerce\Controllers\ContactController;

class ContactServiceProvider implements ServiceProviderInterface
{
    public function register(\Pimple\Container $c): void
    {
        $c[ContactController::class] = fn($c) => new ContactController();
    }
}