<?php

namespace Sebastian\PhpEcommerce\Controllers;

use Sebastian\PhpEcommerce\Http\Request;
use Sebastian\PhpEcommerce\Views\Models\GenericViewModel;
use Sebastian\PhpEcommerce\Views\View;

class ContactController
{
    public function index(Request $request)
    {
        return View::render('contact.index', [
            "viewModel" => new GenericViewModel(
                $request->isAdmin(),
                $request->isAuthenticated()
            )
        ]);
    }
}