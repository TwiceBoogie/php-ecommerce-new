let itemId = null,
  editItemMode = false;

$(document).ready(function () {
  $("#products-list").dataTable({
    initComplete: function () {
      $("#loading-products").remove();
      $("#products-list").show();
    },
  });
});

$("#btn-show-product-modal").click(function () {
  HelpModules.Util.removeErrorMessages();
  itemId = null;
  editItemMode = false;

  $("#modal-add-edit-product .modal-title").text("Add Product");

  $("#add-product-form")[0].reset();

  $("#btn-add-product").text("Add");
});

$("#add-product-form").validate({
  rules: {
    product_name: {
      required: true,
    },
    product_desc: {
      required: true,
    },
    product_price: {
      required: true,
    },
    product_color: {
      required: true,
    },
    product_category: {
      required: true,
    },
  },
  submitHandler: function (form) {
    HelpModules.Http.submit(form, getProductFormData(form), function (res) {
      HelpModules.Util.displaySuccessMessage($(form), res.message);
      setTimeout(function () {
        window.location.reload();
      }, 3000);
    });
  },
});

function getProductFormData(form) {
  return {
    action: editItemMode ? "updateProduct" : "addProduct",
    product: {
      product_id: editItemMode ? itemId : null,
      product_name: form["product_name"].value,
      product_desc: form["product_desc"].value,
      product_price: form["product_price"].value,
      product_color: form["product_color"].value,
      product_category: form["product_category"].value,
    },
  };
}

$(".edit-product").click(function () {
  HelpModules.Util.removeErrorMessages();
  itemId = $(this).data("item");
  editItemMode = true;

  var $modalTitle = $("#modal-add-edit-product .modal-title"),
    $modalBody = $("#modal-add-edit-product .modal-body"),
    $modalFooter = $("#modal-add-edit-product .modal-footer"),
    $ajaxLoader = $("#modal-add-edit-product .ajax-loading");

  $modalTitle.text("Loading");
  $modalBody.hide();
  $modalFooter.hide();
  $ajaxLoader.show();

  HelpModules.Http.post(
    {
      action: "getProduct",
      itemId: itemId,
    },
    function (res) {
      var form = $("#add-product-form")[0];

      $(form["product_name"]).val(res.product_name);
      $(form["product_desc"]).val(res.product_description);
      $(form["product_price"]).val(res.product_price);
      $(form["product_color"]).val(res.product_color);
      $(form["product_category"]).val(res.product_category);

      $(form["button"]).text("Update");

      $modalTitle.text(res.product_name);
      $modalBody.show();
      $modalFooter.show();
      $ajaxLoader.hide();
    }
  );
});

$(".delete-product").click(function () {
  if (!confirm("Are You Sure?")) return;

  var $btn = $(this);

  HelpModules.Http.post(
    {
      action: "deleteProduct",
      product_id: $btn.data("item"),
    },
    function () {
      $btn.parents(".product-row").fadeOut(600, function () {
        $(this).remove();
      });
    }
  );
});
