import { ApiError } from "@utils";

export const Http = {
  /**
   * Make an HTTP POST request using fetch.
   *
   * @param {string} url
   * @param {Object} data
   * @returns {Promise<Object>} Response data from the server.
   */
  async post(url, formData = {}) {
    try {
      const response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        credentials: "include",
        body: JSON.stringify(formData),
      });

      const data = await response.json();
      if (!response.ok) {
        throw new ApiError(data, response.status);
      }

      return data;
    } catch (error) {
      if (!(error instanceof ApiError)) {
        throw new ApiError(
          { message: error.message } || "Unexpected error has occured"
        );
      }
      throw error;
    }
  },
  /**
   * Make an HTTP PUT request using fetch.
   *
   * @param {string} url
   * @param {Object} data
   * @returns {Promise<Object>} Response data from the server.
   */
  async put(url, formData = {}) {
    try {
      const response = await fetch(url, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
        },
        credentials: "include",
        body: JSON.stringify(formData),
      });

      const data = await response.json();
      console.log(data);
      if (!response.ok) {
        throw new ApiError(data, response.status);
      }

      return data;
    } catch (error) {
      if (!(error instanceof ApiError)) {
        throw new ApiError(
          { message: error.message } || "Unexpected error has occured"
        );
      }
      throw error;
    }
  },

  /**
   * Make an HTTP GET request using fetch.
   *
   * @param {string} url
   * @param {Object} data
   * @returns {Promise<Object>} Response data from the server.
   */
  async get(url) {
    try {
      const response = await fetch(url, {
        method: "GET",
        headers: {
          "Content-type": "application/json",
        },
        credentials: "include",
      });
      const data = await response.json();
      if (!response.ok) {
        throw new ApiError(data, response.status);
      }
      return data;
    } catch (error) {
      if (!(error instanceof ApiError)) {
        throw new ApiError(
          { message: error.message } || "Unexpected error has occured"
        );
      }
      throw error;
    }
  },
};
