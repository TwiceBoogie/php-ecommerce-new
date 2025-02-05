jQuery.validator.setDefaults({
  errorElement: "em",
  errorPlacement: function (error, element) {
    error.addClass("invalid-feedback");

    element.prop("type") === "checkbox"
      ? error.insertAfter(element.next("label"))
      : error.insertAfter(element);
  },
  highlight: function (element, errorClass, validClass) {
    $(element).addClass("is-invalid");
  },
  unhighlight: function (element, errorClass, validClass) {
    $(element).removeClass("is-invalid");
  },
});

$(document).ready(function () {
  // Initialize all toast elements on the page
  const toastElList = $(".toast").toArray();
  const toastList = toastElList.map((toastEl) => new bootstrap.Toast(toastEl));
});

const HelpModules = {
  Util: {},
  Http: {},
};
