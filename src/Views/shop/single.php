<?php
/**
 * @var \Sebastian\PhpEcommerce\Views\Models\ShopViewModel $viewModel
 */
use function Sebastian\PhpEcommerce\Helpers\include_partial;

include_partial('header.php', ['viewModel' => $viewModel]);

$product = $viewModel->getProduct();
?>



<!--Single Product-->
<section class="container single-product my-5 pt-5">
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-lg-5 col-md-6 col-sm-12">
            <img class="img-fluid w-100 pb-1" src="/assets/imgs/<?= htmlspecialchars($product->getPrimaryImage()); ?>"
                id="mainImg" />
            <div class="small-image-group">
                <?php foreach ($product->getImages() as $image): ?>
                    <div class="small-image-col">
                        <img src="/assets/imgs/<?= htmlspecialchars($image); ?>" width="100%" class="small-image" />
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <h6>Keyboards/Mice</h6>
            <h3 class="py-4"><?= $product->getName(); ?></h3>
            <h2>$<?= $product->getPrice(); ?></h2>

            <div class="quantity-container">
                <input type="number" class="product-quantity" min="1" max=<?= $product->getQuantity(); ?> value="1"
                    data-product-id="<?= $product->getId(); ?>" />
                <button class="buy-btn add-to-cart-btn" data-product-id="<?= $product->getId(); ?>">
                    Add to Cart
                </button>
            </div>


            <h4 class="mt-5 mb-5">Product Details</h4>
            <span><?= $product->getDescription(); ?></span>
        </div>
    </div>
</section>

<script>
    // $(function () {
    //     var mainImg = $("#mainImg");
    //     $(".small-image").on("click", function () {
    //         mainImg.attr("src", $(this).attr("src"));
    //     })
    // });
    var mainImg = document.getElementById("mainImg");
    var smallImg = document.getElementsByClassName("small-image");

    for (let i = 0; i < 4; i++) {
        smallImg[i].onclick = function () {
            mainImg.src = smallImg[i].src
        }
    }
</script>

<?php include_partial('footer.php'); ?>