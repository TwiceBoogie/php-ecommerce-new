import $ from "jquery";

const Orders = {
  /**
   * Initialize the Orders module.
   */
  init: function () {
    this.loadOrders();
  },

  /**
   * Fetch orders from API and initialize DataTables.
   */
  loadOrders: async function () {
    try {
      $("#orders-list").hide(); // Hide table until data loads
      $("#loading-orders").show(); // Show loading spinner

      const response = await fetch("/api/v1/orders"); // API endpoint
      if (!response.ok) {
        throw new Error("Failed to load orders.");
      }

      const data = await response.json();

      // Check if there are no orders
      if (!data.orders || data.orders.length === 0) {
        $("#loading-orders").remove(); // Remove loading spinner
        $("#orders-list").after(
          `<div class="alert alert-warning text-center">No orders found.</div>`
        );
        return;
      }

      // Initialize DataTable
      $("#orders-list").DataTable({
        data: data.orders, // Use API response
        columns: [
          { data: "id" },
          { data: "status" },
          { data: "cost", render: Orders.formatCurrency }, // Format currency
          { data: "date", render: Orders.formatDate }, // Format date
        ],
        paging: true,
        searching: true,
        ordering: true,
        responsive: true,
        destroy: true, // Allow re-initialization
        initComplete: function () {
          $("#loading-orders").remove(); // Remove loading spinner
          $("#orders-list").show(); // Show table
        },
      });
    } catch (error) {
      console.error("Error loading orders:", error);
      $("#loading-orders").remove(); // Remove loading spinner
      $("#orders-list").after(
        `<div class="alert alert-danger text-center">Failed to load orders.</div>`
      );
    }
  },

  /**
   * Format cost as currency.
   * @param {number} cost
   * @returns {string}
   */
  formatCurrency: function (data, type, row) {
    return `$${parseFloat(data).toFixed(2)}`;
  },

  /**
   * Format date to a readable format.
   * @param {string} date
   * @returns {string}
   */
  formatDate: function (data, type, row) {
    return new Date(data).toLocaleDateString();
  },
};

export default Orders;
