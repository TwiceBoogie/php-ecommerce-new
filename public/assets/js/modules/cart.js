import $ from "jquery";
import Util from "@utils/utils.js";
import Http from "@api/http.js";

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
    $(".add-to-cart-btn").on("click", async function (event) {
      event.preventDefault();

      const button = $(this);

      // Get product ID from button data attribute
      const productId = button.data("product-id");

      // Find the corresponding quantity input field
      const quantityInput = $(
        `.product-quantity[data-product-id="${productId}"]`
      );

      // Get the quantity or default to 1
      const quantity =
        quantityInput.length && quantityInput.val()
          ? parseInt(quantityInput.val(), 10)
          : 1;

      // Call Cart.addToCart with product ID and quantity
      await Cart.addToCart(productId, quantity);
    });
  },
  /**
   * Add an item to the cart.
   * @param {number} productId - The ID of the product to add to the cart.
   * @param {number} quantity - The quantity of the product to add.
   */
  addToCart: async function (productId, quantity) {
    try {
      const response = await Http.post("/api/v1/cart/add", {
        product_id: productId,
        quantity: quantity,
      });

      // TODO: update the max quantity value

      // Update the cart count in the UI
      // this.updateCartUI(response.cart_count);

      // // Show success message in a modal
      // HelpModules.Util.displaySuccessMessage(
      //   "Item Added to Cart",
      //   "The product has been successfully added to your cart!",
      //   "/checkout"
      // );
    } catch (error) {
      console.error("Error adding to cart:", error);

      // Show error message in a modal
      Util.displayErrorMessage(
        "Error Adding to Cart",
        error.message || "Failed to add the item to the cart. Please try again."
      );
    }
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
