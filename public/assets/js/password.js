/**
 * Validate and submit change password form.
 */
$(".change-pass-form").validate({
  rules: {
    oldPass: "required",
    newPass: {
      required: true,
      minlength: 6,
    },
    newPassConfirm: {
      required: true,
      equalTo: ".newPass",
    },
  },
  submitHandler: function (form) {
    HelpModules.Http.submit(
      form,
      getChangePasswordFormData(form),
      function (res) {
        HelpModules.Util.displaySuccessMessage($(form), res.message);
      }
    );
  },
});

/**
 * Builds a change password form data.
 * @param form
 * @returns *
 */
function getChangePasswordFormData(form) {
  return {
    action: "updatePassword",
    oldPass: HelpModules.Util.hash(form["oldPass"].value),
    newPass: HelpModules.Util.hash(form["newPass"].value),
    newPassConfirm: HelpModules.Util.hash(form["newPassConfirm"].value),
  };
}
