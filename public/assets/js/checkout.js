$("#checkout-form").validate({
  rules: {
    name: {
      required: true,
    },
    email: {
      required: true,
    },
    phone: {
      required: true,
    },
    city: {
      required: true,
    },
    address: {
      required: true,
    },
  },
  submitHandler: function (form) {
    HelpModules.Http.submit(form, getCheckoutFormData(form), function (res) {
      HelpModules.Util.displaySuccessMessage($(form), res.message);
      var data = { place_order: "Place Order" };
      setTimeout(function () {
        $.ajax({
          url: "place_order.php",
          type: "POST",
          dataType: "json",
          data: data,
          success: function (res) {
            console.log(res);
            window.location = res.page;
          },
        });
      }, 3000);
    });
  },
});

/**
 * Builds the checkout form data.
 * @param form
 */
function getCheckoutFormData(form) {
  return {
    action: "updateDetails",
    details: {
      phone: form["phone"].value,
      city: form["city"].value,
      address: form["address"].value,
    },
  };
}
