<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Services\RegisterService;
use Sebastian\PhpEcommerce\Views\View;
use Sebastian\PhpEcommerce\Services\Response;

class RegisterController
{
    private RegisterService $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    public function index()
    {
        return View::render('register.index');
    }

    public function register()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return Response::send(['error' => 'Invalid JSON input'], 400);
        }
        $response = $this->registerService->register($input);
        return Response::send($response->toArray(), $response->getStatusCode());
    }
}