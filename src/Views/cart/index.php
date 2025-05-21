<?php

/**
 * @var \Sebastian\PhpEcommerce\Views\Models\CartViewModel $viewModel;
 */

use function Sebastian\PhpEcommerce\Helpers\include_partial;

$cart = $viewModel->getCart();
$cartItems = $cart->getItems();

include_partial('header.php', ['viewModel' => $viewModel]);
?>
<div id="main-wrapper" class="container mt-5">
    <div class="table-responsive">
        <table class="table table-striped data-table" id="cartTable">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item):
                    $product = $item->getProduct();
                    ?>
                    <tr class="item-row">
                        <td><?= $item->getId(); ?></td>
                        <td><?= $product->getName(); ?></td>
                        <td>$<?= number_format($product->getPrice() * $item->getQuantity(), 2); ?></td>
                        <td><?= $item->getQuantity(); ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu ">
                                    <li>
                                        <button type="button" class="dropdown-item btn-delete-soft">
                                            Remove
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item">
                                            Update Quantity
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <thead>
                <tr class="table-light fw-bold">
                    <td>Total: </td>
                    <td id="cart-total-amount">
                        $<?= $cart->getTotalCost(); ?>
                    </td>
                    <td colspan="2">
                        <button type="button" class="btn btn-primary">
                            Purchase
                        </button>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>
<?php include_partial('footer.php');