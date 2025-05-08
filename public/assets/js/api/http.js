import { ApiError } from "@utils";

const Http = {
  /**
   * Make an HTTP POST request using fetch.
   *
   * @param {string} url
   * @param {Object} data
   * @returns {Promise<Object>} Response data from the server.
   */
  async post(url, data = {}) {
    try {
      const response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
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
   * Make an HTTP POST request using fetch.
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

  // /**
  //  * Submit a form via POST using fetch.
  //  *
  //  * @param {HTMLFormElement} form The form to submit.
  //  * @param {Object} data The form data to send.
  //  * @param {Function} onSuccess Callback to run on success.
  //  * @param {Function} onError Callback to run on error.
  //  */
  // async submit(form, data, onSuccess, onError) {
  //   Util.removeErrorMessages();

  //   try {
  //     const response = await HelpModules.Http.post(form.action, data);
  //     form.reset();

  //     if (typeof onSuccess === "function") {
  //       onSuccess(response);
  //     }
  //   } catch (error) {
  //     if (typeof onError === "function") {
  //       onError(error);
  //     } else {
  //       HelpModules.Util.showFormErrors(form, error);
  //     }
  //   }
  // },
};

export default Http;
