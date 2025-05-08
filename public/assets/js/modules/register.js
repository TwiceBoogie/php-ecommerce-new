import $ from "jquery";
import "jquery-validation";
import Util from "../utils/utils.js";
import Http from "../api/http.js";

const Register = {
  /**
   * Initialize the register module.
   */
  init: function () {
    this.attachEventListeners();
  },

  /**
   * Attach event listeners for form validation and submission.
   */
  attachEventListeners: function () {
    $("#register-form").validate({
      rules: {
        name: {
          required: true,
        },
        email: {
          required: true,
          email: true,
        },
        password: {
          required: true,
          minlength: 6,
        },
        confirmPassword: {
          required: true,
          equalTo: "#register-password",
        },
      },
      messages: {
        name: {
          required: "Name is required",
        },
        email: {
          required: "Email is required",
          email: "Please enter a valid email",
        },
        password: {
          required: "Password is required",
          minlength: "Password must be at least 6 characters long",
        },
        confirmPassword: {
          required: "Please confirm your password",
          equalTo: "Passwords do not match",
        },
      },
      submitHandler: function (form) {
        Register.submit(form);
      },
    });
  },

  /**
   * Handle registration submission via API.
   * @param {HTMLFormElement} form
   */
  submit: async function (form) {
    try {
      Util.removeErrorMessages();
      const formData = this.getRegisterFormData(form);
      const response = await Http.post("/api/v1/auth/register", formData);

      // Redirect on success
      setTimeout(() => {
        window.location = "/login";
      }, 3000);
    } catch (error) {
      console.error("Registration Error:", error);

      // Show error toast
      Util.displayErrorMessage(
        "Registration Failed",
        error.message || "An error occurred. Please try again."
      );
      Util.showFormErrors(form, error);
    }
  },

  /**
   * Extract register form data.
   * @param {HTMLFormElement} form
   * @returns {{name: string, email: string, password: string, confirmPassword: string}}
   */
  getRegisterFormData: function (form) {
    return {
      name: form["name"].value,
      email: form["email"].value,
      password: HelpModules.Util.hash(form["password"].value),
      confirmPassword: Util.hash(form["confirmPassword"].value),
    };
  },
};

export default Register;
