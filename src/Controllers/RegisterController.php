<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Http\Request;
use Sebastian\PhpEcommerce\Http\Request\RegisterRequest;
use Sebastian\PhpEcommerce\Services\RegisterService;
use Sebastian\PhpEcommerce\Views\Models\GenericViewModel;
use Sebastian\PhpEcommerce\Views\View;
use Sebastian\PhpEcommerce\Services\Response;

class RegisterController
{
    private RegisterService $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    public function index(Request $request)
    {
        return View::render('register.index', [
            "viewModel" => new GenericViewModel(
                $request->isAdmin(),
                $request->isAuthenticated()
            )
        ]);
    }

    public function register(Request $request)
    {
        $registerRequest = new RegisterRequest($request->getBody());
        $response = $this->registerService->register($registerRequest);
        return Response::send($response->toArray(), $response->getStatusCode());
    }
}