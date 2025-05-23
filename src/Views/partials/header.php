<?php

/**
 * @var \Sebastian\PhpEcommerce\Views\Models\BaseViewModel $viewModel;
 */

$isAdmin = $viewModel->isAdmin();
$isAuthenticated = $viewModel->isAuthenticated();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="/assets/css/style.css" />

</head>

<body class="pt-5">
    <!--Toast-->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
        <div id="app-toast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Warning</strong>
                <button type="button" class="btn-close btn-close-black me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
            <div class="d-flex">
                <div class="toast-body" id="app-toast-body">
                    <!-- Dynamic message goes here -->
                </div>
            </div>
        </div>
    </div>

    <!--NavBar-->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img class="logo" src="/assets/imgs/logo.jpg" alt="Logo" style="height: 50px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="/products">Shop</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="/contact">Contact Us</a>
                    </li> -->
                    <?php if ($isAdmin): ?>
                        <li class="nav-item">
                            <a class="nav-link text-danger fw-bold" href="/admin/users">Admin Panel</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item d-flex">
                        <a class="nav-link me-3" href="/cart">Cart</a>
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle fas fa-user" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">

                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if ($isAuthenticated): ?>
                                    <li><a class="dropdown-item" href="/account">Account</a></li>
                                    <li><a class="dropdown-item" href="/orders">Orders</a></li>
                                    <li><button class="dropdown-item" id="logout-button">logout</button></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="/login">Login</a></li>
                                    <li><a class="dropdown-item" href="/register">Register</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>