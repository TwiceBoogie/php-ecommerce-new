<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Services\SecureSession;
use function Sebastian\PhpEcommerce\Helpers\app;
use Sebastian\PhpEcommerce\Views\View;

class ContactController
{
    public function index()
    {
        $isAdmin = SecureSession::get('user_id') !== null && app('user')->isAdmin();

        return View::render('contact.index', [
            'isAdmin' => $isAdmin,
        ]);
    }
}