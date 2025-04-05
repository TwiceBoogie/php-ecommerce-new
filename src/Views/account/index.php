<?php
/**
 * @var \Sebastian\PhpEcommerce\Views\Models\AccountViewModel $account
 */

use function Sebastian\PhpEcommerce\Helpers\include_partial;

$isAdmin = $account->isAdmin();
include_partial('header.php');

?>

<!--Account-->
<section class="my-5 py-5">
    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="https://bootdey.com/img/Content/avatar/avatar6.png" alt="Admin"
                                    class="rounded-circle p-1 bg-primary" width="110">
                                <div class="mt-3">
                                    <h4><?= $account->getName(); ?></h4>
                                    <p class="text-secondary mb-1">Registered:
                                        <span><?= $account->getRegisterDate(); ?></span>
                                    </p>
                                </div>
                                <div class="mt-3">
                                    <p class="text-secondary mb-1">Email:
                                        <span><?= $account->getEmail(); ?></span>
                                    </p>
                                    <!-- <button class="btn btn-primary">Follow</button> -->
                                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal"
                                        data-bs-target="#emailModal">
                                        Change Email
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="emailModal" tabindex="-1"
                                        aria-labelledby="emailModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="emailModalLabel">Change your email
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="emailChange-form">
                                                        <div class="mb-3 form-group">
                                                            <input type="email" class="form-control form-control-lg"
                                                                name="email" placeholder="Enter new email">
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <form class="card-body" id="userSettings-form">
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Full Name</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" name="name"
                                        value="<?= $account->getName(); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Phone</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" name="phone"
                                        value="<?= $account->getPhone(); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Address</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" name="address"
                                        value="<?= $account->getAddress(); ?>">
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-sm-2">
                                    <h6 class="mb-0">City</h6>
                                </div>
                                <div class="col-sm-4 text-secondary">
                                    <input type="text" class="form-control" name="city"
                                        value="<?= $account->getCity(); ?>">
                                </div>
                                <div class="col-sm-2">
                                    <h6 class="mb-0">State</h6>
                                </div>
                                <div class="col-sm-4 text-secondary">
                                    <input type="text" class="form-control" name="state"
                                        value="<?= $account->getState(); ?>">
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-sm-2">
                                    <h6 class="mb-0">Postal Code</h6>
                                </div>
                                <div class="col-sm-4 text-secondary">
                                    <input type="text" class="form-control" name="postal"
                                        value="<?= $account->getPostalCode(); ?>">
                                </div>
                                <div class="col-sm-2">
                                    <h6 class="mb-0">Country</h6>
                                </div>
                                <div class="col-sm-4 text-secondary">
                                    <input type="text" class="form-control" name="country"
                                        value="<?= $account->getCountry(); ?>">
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col text-secondary justify-content-center">
                                    <button type="submit" class="btn btn-primary px-4 form-group">
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <?php include_partial('_tablesOrders.php'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Orders-->
</section>

<?php include_partial('footer.php'); ?>