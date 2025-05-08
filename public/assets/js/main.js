import $ from "jquery";

import "./setup.js";
import { Register, UserSettings, Cart, Login } from "@modules";

$(function () {
  UserSettings.init();
  Register.init();
  Cart.init();
  Login.init();
  if ($("orders-list").length) {
    import("@modules/orders.js")
      .then(({ default: Orders }) => {
        Orders.init();
      })
      .catch((err) => console.error("Failed to load Orders module: ", err));
  }
});
