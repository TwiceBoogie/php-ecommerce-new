<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Http\Request;
use Sebastian\PhpEcommerce\Http\Request\UpdateUserDetailsRequest;
use Sebastian\PhpEcommerce\Services\Response;
use Sebastian\PhpEcommerce\Services\UserService;
use Sebastian\PhpEcommerce\Views\Models\AccountViewModel;
use Sebastian\PhpEcommerce\Views\View;

class AccountController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $isAdmin = $request->isAdmin();
        $isAuthenticated = $request->isAuthenticated();
        $userDetails = $this->userService->getUserDetails();
        $accountViewModel = new AccountViewModel($isAdmin, $isAuthenticated, $userDetails);
        return View::render('account.index', [
            'viewModel' => $accountViewModel
        ]);
    }

    public function updateUserDetails(Request $request)
    {
        $userDetailsRequest = new UpdateUserDetailsRequest($request->getBody());
        $response = $this->userService->updateUserDetails($userDetailsRequest);
        return Response::send($response->toArray(), $response->getStatusCode());
    }
}