<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Http\Request;
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
        $isAdmin = $request->isAdmin;
        $userDetails = $this->userService->getUserDetails();
        $accountViewModel = new AccountViewModel($userDetails, $isAdmin);
        return View::render('account.index', [
            'account' => $accountViewModel
        ]);
    }
}