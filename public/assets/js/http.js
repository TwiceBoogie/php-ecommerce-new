import Util from "./utils.js";

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

      if (!response.ok) {
        const errorData = await response.json();
        throw errorData;
      }

      return await response.json();
    } catch (error) {
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
