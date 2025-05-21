import $ from "jquery";

/**
 * Utility functions for UI interactions and validation.
 */
export const Util = {
  /**
   * Display a success message in a modal.
   */
  displaySuccessMessage(title, message, redirectUrl = null) {
    const $modal = $("#exampleModal");

    if (!$modal.length) {
      console.error("Modal with ID 'exampleModal' not found.");
      return;
    }

    $("#exampleModalLabel").text(title);
    $(".modal-body").html(`<p>${message}</p>`);

    $(".modal-footer .btn-secondary")
      .text("Close")
      .off("click")
      .on("click", () => {
        $modal.hide();
      });

    const $primaryButton = $(".modal-footer .btn-primary").hide();
    if (redirectUrl) {
      $primaryButton
        .text("Go to Checkout")
        .off("click")
        .on("click", () => {
          window.location.href = redirectUrl;
        })
        .show();
    }

    $modal.show();
  },

  /**
   * Display an error toast.
   */
  displayErrorMessage(title, message = "An error occurred.") {
    const toast = $("#errorToast");

    if (!toast.length) {
      console.error("Error toast not found.");
      return;
    }

    $("#errorToast .toast-header strong").text(title);
    $("#toastMessage").text(message);

    new bootstrap.Toast(toast[0], { delay: 5000 }).show();
  },

  /**
   * Show validation error messages.
   */
  displayFormErrorMessages(element, message) {
    element.addClass("is-invalid").removeClass("is-valid");
    if (message) {
      element.after(`<em class="invalid-feedback">${message}</em>`);
    }
  },

  /**
   * Remove all error messages.
   */
  removeErrorMessages() {
    $("form input").removeClass("is-invalid is-valid");
    $(".invalid-feedback").remove();
  },

  /**
   * Show form validation errors received from the server.
   */
  showFormErrors(form, errors = {}) {
    Object.keys(errors).forEach((key) => {
      this.displayFormErrorMessages(
        $(form).find(`[name="${key}"]`),
        errors[key]
      );
    });
  },

  /**
   * Hash a given password using SHA-256.
   */
  async hash(password) {
    const encoder = new TextEncoder();
    const data = encoder.encode(password);
    const hashBuffer = await crypto.subtle.digest("SHA-256", data);
    return Array.from(new Uint8Array(hashBuffer))
      .map((b) => b.toString(16).padStart(2, "0"))
      .join("");
  },

  showToast(message, type = "success") {
    const $toast = $("#app-toast");
    const $strong = $toast.find(".me-auto");
    const $body = $("#app-toast-body");

    // clear any previous icon and insert a new one
    $strong.empty();
    const $icon = $("<i>").addClass("fa-solid me-2");
    switch (type) {
      case "success":
        $icon.addClass("fa-check");
        $strong.text("Success");
        break;
      case "info":
      default:
        $icon.addClass("fa-info-circle");
        $strong.text("Notice");
        break;
    }

    $strong.prepend($icon);
    $body.text(message);
    $toast
      .removeClass()
      .addClass(
        `toast align-items-center bg-${type}-subtle text-${type} border-0`
      );
    const toast = bootstrap.Toast.getOrCreateInstance($toast[0]);
    toast.show();
  },
};
