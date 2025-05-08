export default class ApiError extends Error {
  constructor(responseData, statusCode) {
    super(responseData.message || "An unknown error occurred");
    this.name = "ApiError";
    this.status = responseData.status || "error";
    this.data = responseData.data || {};
    this.errors = responseData.errors || null;
    this.statusCode = statusCode || 500;
  }
}
