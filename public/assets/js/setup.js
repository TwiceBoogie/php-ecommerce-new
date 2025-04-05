import $ from "jquery";
import "jquery-validation";
import "jquery-validation/dist/additional-methods.js";

// Default validation settings
$.validator.setDefaults({
  errorElement: "em",
  errorPlacement: (error, element) => {
    error.addClass("invalid-feedback");
    element.prop("type") === "checkbox"
      ? error.insertAfter(element.next("label"))
      : error.insertAfter(element);
  },
  highlight: (element) => $(element).addClass("is-invalid"),
  unhighlight: (element) => $(element).removeClass("is-invalid"),
});

// Initialize Bootstrap Toasts on page load
$(function () {
  $(".toast").each((_, toastEl) => new bootstrap.Toast(toastEl));
});
