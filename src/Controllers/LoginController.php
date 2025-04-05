<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Http\Request;
use Sebastian\PhpEcommerce\Http\Request\LoginRequest;
use Sebastian\PhpEcommerce\Services\LoginService;
use Sebastian\PhpEcommerce\Views\View;
use Sebastian\PhpEcommerce\Services\Response;

class LoginController
{
    private LoginService $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function index(Request $request): bool|string
    {
        return View::render('login.index');
    }

    public function login(Request $request)
    {
        $loginRequest = new LoginRequest($request->getBody());

        $response = $this->loginService->login($loginRequest);
        return Response::send($response->toArray(), $response->getStatusCode());
    }

    public function logout(Request $request)
    {
        $response = $this->loginService->logout();
        return Response::send($response->toArray(), $response->getStatusCode());
    }
}