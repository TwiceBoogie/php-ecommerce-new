<?php

// Check if user is logged in and is admin
$isAdmin = isset($isAdmin) && $isAdmin;
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

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />

    <link rel="stylesheet" href="/assets/css/style.css" />

</head>

<body>
    <!--NavBar-->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 fixed-top">
        <div class="container-fluid">
            <!-- <a class="navbar-brand" href="/">
                <img class="logo" src="/assets/imgs/logo.jpg" alt="Logo" style="height: 50px;">
            </a> -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/products">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contact Us</a>
                    </li>
                    <?php if ($isAdmin): ?>
                        <li class="nav-item">
                            <a class="nav-link text-danger fw-bold" href="/admin/users">Admin Panel</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item d-flex">
                        <a class="nav-link me-3" href="/cart"><i class="fas fa-shopping-bag"></i></a>
                        <a class="nav-link" href="/account"><i class="fas fa-user"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>