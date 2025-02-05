<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Services\LoginService;
use Sebastian\PhpEcommerce\Services\SecureSession;
use Sebastian\PhpEcommerce\Views\View;
use Sebastian\PhpEcommerce\Services\Response;

class LoginController
{
    private LoginService $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function index()
    {
        return View::render('login.index');
    }

    public function login()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return Response::send(['error' => 'Invalid JSON input'], 400);
        }

        $response = $this->loginService->login($input);
        return Response::send($response->toArray(), $response->getStatusCode());
    }

    public function logout()
    {
        $response = $this->loginService->logout();
        return Response::send($response->toArray(), $response->getStatusCode());
    }
}