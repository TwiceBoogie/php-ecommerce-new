import $ from "jquery";
import { Util } from "../utils/index.js";
import { Http } from "../api/http.js";

const Cart = {
  /**
   * Initializes the cart by attaching event listeners.
   */
  init: function () {
    this.attachEventListeners();
  },

  /**
   * Attach event listener to all "Add to Cart" buttons.
   */
  attachEventListeners: function () {
    const self = this;
    $("#add-product-form").validate({
      rules: {
        productId: {
          required: true,
          number: true,
          digits: true,
          min: 1,
        },
        productQuantity: {
          required: true,
          number: true,
          digits: true,
          min: 1,
        },
      },
      messages: {
        productQuantity: {
          required: "quantity must be 1 or greater",
          min: "Quantity must be 1 or greater",
        },
      },
      submitHandler: function (form) {
        self.submit(form);
      },
    });
  },

  submit: async function (form) {
    Util.removeErrorMessages();
    try {
      const formData = this.getAddProductFormData(form);

      const response = Http.post("/api/v1/cart/add", formData);
      Util.displaySuccessMessage("Product added to cart", response.message);
    } catch (error) {
      console.error("Add Product Error: ", error);
    }
  },

  getAddProductFormData: function (form) {
    return {
      productId: form["productId"].value,
      productQuantity: form["productQuantity"].value,
    };
  },

  /**
   * Update the cart count in the UI (e.g., the header cart icon).
   * @param {number} cartCount - The updated cart count to display.
   */
  updateCartUI: function (cartCount) {
    $("#cart-count").text(cartCount);
  },
};

export default Cart;
