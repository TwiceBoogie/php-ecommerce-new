import $ from "jquery";
import "jquery-validation";
import "jquery-validation/dist/additional-methods.js";
import DataTable from "datatables.net-bs5";
// import "datatables.net-bs5/css/datatables.bootstrap5.css";
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
  const fakeData = Array.from({ length: 30 }, (_, i) => ({
    id: i + 1,
    name: `Product ${i + 1}`,
    price: (Math.random() * 100).toFixed(2),
    quantity: Math.floor(Math.random() * 5 + 1),
  }));

  $(".toast").each((_, toastEl) => new bootstrap.Toast(toastEl));
  $("#cartTable").DataTable({
    columns: [
      { data: "id", visible: false, searchable: false },
      { data: "name", searchable: true },
      { data: "price", searchable: false },
      { data: "quantity", searchable: false },
      { data: "actions", orderable: false },
    ],
    responsive: true,
    language: {
      emptyTable: "Your cart is currently empty. Start shopping!",
    },
  });
});
