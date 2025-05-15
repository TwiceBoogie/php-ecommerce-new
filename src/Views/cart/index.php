<?php

/**
 * @var \Sebastian\PhpEcommerce\Views\Models\CartViewModel $viewModel;
 */

use function Sebastian\PhpEcommerce\Helpers\include_partial;

include_partial('header.php', ['viewModel' => $viewModel]);
?>
<div id="main-wrapper" class="container mt-5">
    hello
</div>
<?php include_partial('footer.php');