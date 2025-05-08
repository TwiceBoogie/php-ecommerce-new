<?php

namespace Sebastian\PhpEcommerce\Http;

class Request
{
    private array $body;
    private array $query;
    private string $method;
    private string $path;
    private bool $isAuthenticated = false;
    private ?object $user = null;
    private bool $isAdmin = false;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $this->query = $_GET ?? [];
        $this->body = $this->parseRequestBody();
    }

    /**
     * Parses the request body based on the Content-Type header.
     *
     * If the request has a Content-Type of "application/json", it decodes the JSON data.
     * Otherwise, it falls back to $_POST.
     *
     * @return array The parsed request body data.
     */
    private function parseRequestBody(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        // If the request content type is JSON, decode it
        if (stripos($contentType, 'application/json') !== false) {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                return $data;
            }
            return [];
        }

        // For form data or other content types, fallback to $_POST
        return $_POST;
    }

    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    public function setAuthenticated(bool $value): void
    {
        $this->isAuthenticated = $value;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function setAdmin(bool $value): void
    {
        $this->isAdmin = $value;
    }

    /**
     * Get all request body data.
     *
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * Get a specific parameter from the request body or query parameters.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    /**
     * Get query parameters ($_GET).
     *
     * @return array
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * Get HTTP method (GET, POST, etc.).
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get request path (e.g., /users, /login, etc.).
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
