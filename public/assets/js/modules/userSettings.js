import $ from "jquery";
import { ApiError, Util } from "../utils/index.js";
import { Http } from "../api/http.js";

const UserSettings = {
  init: function () {
    this.attachEventListeners();
  },
  attachEventListeners: function () {
    let self = this; // Store context
    $("#userSettings-form").validate({
      rules: {
        name: {
          required: true,
        },
        phone: {
          phoneUS: true,
        },
        address: {
          minlength: 5,
        },
        city: {
          minlength: 2,
        },
        state: {},
        postal: {
          digits: true,
          minlength: 5,
        },
        country: {},
      },
      messages: {
        name: {
          required: "Name is required",
          minlength: "Name must be at least 3 characters long",
        },
        phone: {
          phoneUS: "Please enter a valid US phone number",
        },
        address: {
          minlength: "Address must be at least 5 characters long",
        },
        city: {
          minlength: "City name must be at least 2 characters",
        },
        state: {
          required: "Please select a state",
        },
        postal: {
          required: "Postal code is required",
          digits: "Only numbers are allowed",
          minlength: "Postal code must be at least 5 digits long",
        },
        country: {
          required: "Please select a country",
        },
      },
      submitHandler: function (form) {
        self.submit(
          form,
          "/api/v1/user/settings/update",
          self.getUserSettingsFormData
        );
      },
    });

    $("#emailChange-form").validate({
      rules: {
        email: {
          email: true,
          required: true,
        },
      },
      messages: {
        email: {
          required: "Email is required",
          email: "Please enter a valid email",
        },
      },
      submitHandler: function (form) {
        self.submit(
          form,
          "/api/v1/user/settings/update/email",
          self.getEmailFormData
        );
      },
    });

    $("#emailForm-submitButton").on("click", function () {
      if ($("#emailChange-form").valid()) {
        $("#emailChange-form").trigger("submit");
      }
    });
  },
  submit: async function (form, url, fn) {
    try {
      Util.removeErrorMessages();
      const formData = fn(form);
      const res = await Http.put(url, formData);
      Util.showToast(res.message);

      setTimeout(() => {
        window.location.reload();
      });
    } catch (error) {
      console.error("User Settings update failed", error);
      if (error instanceof ApiError) {
        Util.displayErrorMessage("validation Failed", error.message);
        Util.showFormErrors(form, error.errors);
      }
    }
  },

  /**
   * Extract register form data.
   * @param {HTMLFormElement} form
   * @returns {{name: string, phone: string, address: string, city: string, state: string, postal: digits, country: string}}
   */
  getUserSettingsFormData: function (form) {
    return {
      name: form["name"].value,
      phone: form["phone"].value,
      address: form["address"].value,
      city: form["city"].value,
      state: form["state"].value,
      postal: form["postal"].value,
      country: form["country"].value,
    };
  },

  getEmailFormData: function (form) {
    return {
      email: form["email"].value,
    };
  },
};

export default UserSettings;
