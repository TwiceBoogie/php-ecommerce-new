import $ from "jquery";
import Util from "./utils.js";
import Http from "./http.js";

const Login = {
  /**
   * Initialize the login module.
   */
  init: function () {
    this.attachEventListeners();
  },

  /**
   * Attach event listeners for form validation and submission.
   */
  attachEventListeners: function () {
    $("#login-form").validate({
      rules: {
        email: {
          required: true,
          email: true,
        },
        password: {
          required: true,
          minlength: 6, // Ensure password is at least 6 characters
        },
      },
      messages: {
        email: {
          required: "Email is required",
          email: "Please enter a valid email",
        },
        password: {
          required: "Password is required",
          minlength: "Password must be at least 6 characters long",
        },
      },
      submitHandler: function (form) {
        Login.submit(form);
      },
    });
  },

  /**
   * Handle login submission via API.
   * @param {HTMLFormElement} form
   */
  submit: async function (form) {
    Util.removeErrorMessages();
    try {
      const formData = this.getLoginFormData(form);
      const response = await Http.post("/api/v1/auth/login", formData);

      // Redirect on success
      window.location = response.page;
    } catch (error) {
      console.error("Login Error:", error);
      if (error.errors) {
        Util.showFormErrors(form, error.errors);
      }

      // Show error toast
      Util.displayErrorMessage(
        "Login Failed",
        error.message || "Invalid email or password. Please try again."
      );
    }
  },

  /**
   * Extract login form data.
   * @param {HTMLFormElement} form
   * @returns {{email: string, password: string}}
   */
  getLoginFormData: function (form) {
    return {
      email: form["email"].value,
      password: form["password"].value,
    };
  },
};

export default Login;
