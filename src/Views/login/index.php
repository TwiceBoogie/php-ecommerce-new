<?php

/**
 * @var \Sebastian\PhpEcommerce\Views\Models\HomeViewModel $viewModel;
 */

use function Sebastian\PhpEcommerce\Helpers\include_partial;

include_partial('header.php', ['viewModel' => $viewModel]);
?>
<!--Login-->
<div id="main-wrapper" class="container mt-5">
    <!-- Toast Container (Positioned at the Top Right) -->
    <div class="toast-container position-fixed top-2 end-0 p-3">
        <div id="errorToast" class="toast bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true"
            data-bs-delay="5000">
            <div class="toast-header">
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                An error occurred. Please try again.
            </div>
        </div>
    </div>


    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card border-0 shadow-lg">
                <div class="row g-0">
                    <!-- Left Side (Login Form) -->
                    <div class=" p-5">
                        <div class="mb-4">
                            <h3 class="fw-bold text-primary">Login</h3>
                            <p class="text-muted">Enter your credentials to access your account.</p>
                        </div>

                        <form id="login-form">
                            <div class="mb-3 form-group">
                                <label for="login-email" class="form-label">Email Address</label>
                                <input type="email" class="form-control form-control-lg" id="login-email" name="email"
                                    placeholder="Enter email">
                            </div>
                            <div class="mb-4 form-group">
                                <label for="login-password" class="form-label">Password</label>
                                <input type="password" class="form-control form-control-lg" id="login-password"
                                    name="password" placeholder="Enter password">
                            </div>
                            <button type="submit" id="login-btn" name="login_btn"
                                class="btn btn-primary btn-lg w-100 form-group">
                                Login
                            </button>
                            <a href="#" class="d-block text-end mt-3 text-decoration-none text-primary">
                                Forgot password?
                            </a>
                        </form>

                    </div>


                </div>
            </div>

            <p class="text-center mt-4">Don't have an account? <a href="/register"
                    class="text-primary fw-bold">Register</a></p>
        </div>
    </div>
</div>
<!--Footer-->
<?php include_partial('footer.php') ?>