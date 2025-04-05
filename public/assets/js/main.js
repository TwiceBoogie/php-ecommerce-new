import $ from "jquery";

import "./setup.js";
import Register from "./register.js";
import UserSettings from "./userSettings.js";
import Cart from "./cart.js";
import Login from "./login.js";

$(function () {
  UserSettings.init();
  Register.init();
  Cart.init();
  Login.init();
  if ($("orders-list").length) {
    import("./orders.js")
      .then(({ default: Orders }) => {
        Orders.init();
      })
      .catch((err) => console.error("Failed to load Orders module: ", err));
  }
});
