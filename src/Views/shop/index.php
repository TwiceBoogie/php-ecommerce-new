<?php

/**
 * @var \Sebastian\PhpEcommerce\Views\Models\ShopViewModel $shop
 */

use function Sebastian\PhpEcommerce\Helpers\include_partial;
$isAdmin = $shop->isAdmin();
include_partial('header.php');
?>

<!--Products-->
<section id="shop" class="my-5 py-5">
    <div class="container mt-5 py-5">
        <h3>Our Products</h3>
        <hr>
        <p>Here you can check out our new featured products</p>
    </div>
    <div class="row mx-auto container">


        <?php foreach ($shop->getProducts() as $product): ?>

            <div onclick="window.location.href='/product/<?= $product->getId(); ?>';"
                class="product text-center col-lg-3 col-md-4 col-sm-12">
                <a href="/product/<?= $product->getId(); ?>"><img class="img-fluid mb-3"
                        src="/assets/imgs/<?= $product->getPrimaryImage(); ?>" /></a>
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <h5 class="p-name"><?= $product->getName(); ?></h5>
                    <h4 class="p-price">$<?= $product->getPrice(); ?></h4>
                </div>
                <a class="btn shop-buy-btn" href="/product/<?= $product->getId(); ?>">Buy Now</a>
            </div>

        <?php endforeach; ?>

    </div>

</section>


<?php include_partial('footer.php'); ?>