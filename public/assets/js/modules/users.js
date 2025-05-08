var activeUser = null,
  editUserMode = false;

$(document).ready(function () {
  $("#users-list").dataTable({
    initComplete: function () {
      $("#loading-users").remove();
      $("#users-list").show();
    },
  });
});

$(".change-role").click(function () {
  activeUser = $(this).data("user");

  // Set active option inside the select box.
  $("#select-user-role").val($(this).data("role"));
});

$("#change-role-button").click(function () {
  HelpModules.Http.post(
    {
      action: "changeRole",
      userId: activeUser,
      role: $("#select-user-role").val(),
    },
    function (response) {
      $(".change-role[data-user=" + activeUser + "] .user-role").text(
        response.role
      );
    }
  );
});

$("#btn-show-user-modal").click(function () {
  HelpModules.Util.removeErrorMessages();
  activeUser = null;
  editUserMode = false;

  $("#modal-add-edit-user .modal-title").text("Add User");

  HelpModules.Util.removeErrorMessages();

  $("#add-user-form")[0].reset();
  $("#add-user-form input[type=password]").removeAttr("placeholder");

  $("#btn-add-user").text("Add");
});

$("#add-user-form").validate({
  rules: {
    email: {
      required: true,
      email: true,
    },
    name: "required",
    password: {
      required: function () {
        return !editUserMode;
      },
      minlength: 6,
    },
    passwordConfirm: {
      required: function () {
        return !editUserMode;
      },
      equalTo: "#password",
    },
  },
  submitHandler: function (form) {
    HelpModules.Http.submit(form, getUserFormData(form), function (res) {
      HelpModules.Util.displaySuccessMessage($(form), res.message);
      setTimeout(function () {
        window.location.reload();
      }, 3000);
    });
  },
});

function getUserFormData(form) {
  return {
    action: editUserMode ? "updateUser" : "addUser",
    user: {
      user_id: editUserMode ? activeUser : null,
      email: form["email"].value,
      name: form["name"].value,
      password: HelpModules.Util.hash(form["password"].value),
      passwordConfirm: HelpModules.Util.hash(form["passwordConfirm"].value),
      city: form["city"].value,
      address: form["address"].value,
      phone: form["phone"].value,
    },
  };
}

$(".edit-user").click(function () {
  HelpModules.Util.removeErrorMessages();
  activeUser = $(this).data("user");
  editUserMode = true;

  var $modalTitle = $("#modal-add-edit-user .modal-title"),
    $modalBody = $("#modal-add-edit-user .modal-body"),
    $modalFooter = $("#modal-add-edit-user .modal-footer"),
    $ajaxLoader = $("#modal-add-edit-user .ajax-loading");

  $modalTitle.text("Loading");
  $modalBody.hide();
  $modalFooter.hide();
  $ajaxLoader.show();

  HelpModules.Http.post(
    {
      action: "getUser",
      userId: activeUser,
    },
    function (res) {
      var form = $("#add-user-form")[0];

      $(form["email"]).val(res.user_email);
      $(form["name"]).val(res.user_name);
      $(form["city"]).val(res.city);
      $(form["address"]).val(res.address);
      $(form["phone"]).val(res.phone);

      $(form["password"]).attr("placeholder", "Leave Blank");
      $(form["passwordConfirm"]).attr("placeholder", "Leave Blank");

      $(form["button"]).text("Update");

      $modalTitle.text(res.user_name);
      $modalBody.show();
      $modalFooter.show();
      $ajaxLoader.hide();
    }
  );
});

$(".user-details").click(function () {
  var $modalTitle = $("#modal-user-details .modal-title"),
    $modalBody = $("#modal-user-details .modal-body"),
    $modalFooter = $("#modal-user-details .modal-footer"),
    $ajaxLoader = $("#modal-user-details .ajax-loading");

  $modalTitle.text("Loading");
  $modalBody.hide();
  $modalFooter.hide();
  $ajaxLoader.show();

  HelpModules.Http.post(
    {
      action: "getUserDetails",
      userId: $(this).data("user"),
    },
    function (res) {
      $("#modal-details--email").text(res.user_email);
      $("#modal-details--full-name").text(res.user_name);
      $("#modal-details--city").text(res.city);
      $("#modal-details--address").text(res.address);
      $("#modal-details--phone").text(res.phone);

      $modalTitle.text(res.user_name);
      $modalBody.show();
      $modalFooter.show();
      $ajaxLoader.hide();
    }
  );
});

$(".delete-user").click(function () {
  if (!confirm("Are You Sure?")) return;

  var $btn = $(this);

  HelpModules.Http.post(
    {
      action: "deleteUser",
      userId: $btn.data("user"),
    },
    function () {
      $btn.parents(".user-row").fadeOut(600, function () {
        $(this).remove();
      });
    }
  );
});
