HelpModules.Util.displaySuccessMessage = function (
  title,
  message,
  redirectUrl = null
) {
  // Ensure the modal exists in the DOM
  if (!$("#exampleModal").length) {
    console.error("Modal with ID 'exampleModal' not found in the DOM.");
    return;
  }

  // Set the modal title and message dynamically
  $("#exampleModalLabel").text(title);
  $(".modal-body").html(`<p>${message}</p>`);

  // Update button actions
  const $closeButton = $(".modal-footer .btn-secondary");
  const $primaryButton = $(".modal-footer .btn-primary");

  $closeButton.off("click"); // Clear any previous handlers
  $primaryButton.off("click"); // Clear any previous handlers

  $closeButton.text("Close").on("click", function () {
    $("#exampleModal").modal("hide");
  });

  if (redirectUrl) {
    $primaryButton
      .text("Go to Checkout")
      .on("click", function () {
        window.location.href = redirectUrl;
      })
      .show(); // Ensure the button is visible
  } else {
    $primaryButton.hide(); // Hide the primary button if no redirect URL is provided
  }

  // Show the modal
  $("#exampleModal").modal("show");
};

HelpModules.Util.displayErrorMessage = function (
  title,
  message = "An error occurred."
) {
  // Ensure the toast exists
  if (!$("#errorToast").length) {
    console.error("Error toast not found in the DOM.");
    return;
  }

  // Set title & message
  $("#errorToast .toast-header strong").text(title);
  $("#toastMessage").text(message);

  // Show the toast
  let toast = new bootstrap.Toast($("#errorToast")[0], { delay: 5000 });
  toast.show();
};

HelpModules.Util.displayFormErrorMessages = function (element, message) {
  element.addClass("is-invalid").removeClass("is-valid");

  if (typeof message !== "undefined") {
    element.after($("<em class='invalid-feedback'>" + message + "</em>"));
  }
};

/**
 * Removes all error messages from all input fields.
 */
HelpModules.Util.removeErrorMessages = function () {
  $("form input").removeClass("is-invalid").removeClass("is-valid");
  $(".invalid-feedback").remove();
};

/**
 * Show errors received from the server.
 * @param {HTMLFormElement} form
 * @param {Object} error
 */
HelpModules.Util.showFormErrors = function (form, error) {
  const errors = error.errors || {};
  Object.keys(errors).forEach((key) => {
    HelpModules.Util.displayFormErrorMessages(
      $(form).find(`input[name="${key}"]`),
      errors[key]
    );
  });
};

/**
 * Hash a given value using SHA512 hashing algorithm.
 * @param {string} value
 * @returns {string}
 */
HelpModules.Util.hash = function (value) {
  return value.length ? CryptoJS.SHA512(value).toString() : "";
};
