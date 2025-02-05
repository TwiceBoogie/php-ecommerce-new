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
    try {
      const formData = this.getLoginFormData(form);
      const response = await HelpModules.Http.post(
        "/api/v1/auth/login",
        formData
      );

      // Redirect on success
      window.location = response.page;
    } catch (error) {
      console.error("Login Error:", error);

      // Show error toast
      HelpModules.Util.displayErrorMessage(
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
      password: HelpModules.Util.hash(form["password"].value),
    };
  },
};

// Initialize login module when the page loads
$(function () {
  Login.init();
});
