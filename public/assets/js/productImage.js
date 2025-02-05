$(".product-details").click(function (e) {
  var productID = $(this).data("item");
  $(".add-image")
    .unbind()
    .submit(function (e) {
      console.log("This is also firing");
      e.preventDefault();
      sendImage(productID, new FormData($(".add-image")[0]));
    });
  var $modalTitle = $("#modal-product-details .modal-title"),
    $modalBody = $("#modal-product-details .modal-body"),
    $modalFooter = $("#modal-product-details .modal-footer"),
    $ajaxLoader = $("#modal-product-details .ajax-loading");

  $modalTitle.text("Loading");
  $modalBody.hide();
  $modalFooter.hide();
  $ajaxLoader.show();

  HelpModules.Http.post(
    {
      action: "getProductDetails",
      productId: $(this).data("item"),
    },
    function (res) {
      $("#product-img").attr(
        "src",
        "../assets/imgs/" + encodeURIComponent(res.product_image)
      );
      $("#modal-details--name").text(res.product_name);
      $("#modal-details--description").text(res.product_description);
      $("#modal-details--price").text("$" + res.product_price);
      $("#modal-details--color").text(res.product_color);
      $("#modal-details--category").text(res.product_category);

      $modalTitle.text(res.product_name);
      $modalBody.show();
      $modalFooter.show();
      $ajaxLoader.hide();
    }
  );
});

$(".eraseInput").click(function () {
  $("#productImg").val(null);
});

function sendImage(id, data) {
  data.append("action", "addProductImg");
  data.append("productID", id);
  $.ajax({
    url: "Backend/Ajax.php",
    type: "POST",
    dataType: "json",
    data: data,
    processData: false,
    contentType: false,
    success: function (res) {
      HelpModules.Util.displaySuccessMessage($(".add-image"), res.message);
      setTimeout(() => {
        location.reload();
      }, 3000);
    },
    error: function (res) {
      HelpModules.Util.displayErrorMessage(
        $(".add-image"),
        res.responseJSON.error
      );
    },
    complete: function () {},
  });
}
