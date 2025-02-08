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
        email: {
          required: true,
          email: true,
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
        email: {
          required: "Email is required",
          email: "Please enter a valid email address",
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
        self.submit(form);
      },
    });
  },
  submit: async function (form) {
    try {
      console.log(form);
    } catch (error) {}
  },

  /**
   * Extract register form data.
   * @param {HTMLFormElement} form
   * @returns {{name: string, email: string, phone: string, address: string, city: string, state: string, postal: digits, country: string}}
   */
  getUserSettingsFormData: function (form) {
    return {
      name: form["name"].value,
      email: form["email"].value,
      phone: form["phone"].value,
      address: form["address"].value,
      city: form["city"].value,
      state: form["state"].value,
      postal: form["postal"].value,
      country: form["country"].value,
    };
  },
};

$(function () {
  UserSettings.init();
});
