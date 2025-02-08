<?php

namespace Sebastian\PhpEcommerce\Http;

class Request
{
    private array $body;
    private array $query;
    private string $method;
    private string $path;
    public ?object $user = null;
    public bool $isAdmin = false;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->query = $_GET;
        $this->body = $this->parseRequestBody();
    }

    /**
     * Parses request body depending on content type.
     */
    private function parseRequestBody(): array
    {
        if ($this->method === 'POST') {
            return $_POST;
        }

        if (in_array($this->method, ['PUT', 'PATCH', 'DELETE', 'POST'])) {
            $input = file_get_contents('php://input');
            $decoded = json_decode($input, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        }

        return [];
    }

    /**
     * Get all request body data.
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * Get a specific parameter from the request body.
     */
    public function get(string $key, $default = null)
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    /**
     * Get query parameters ($_GET)
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * Get HTTP method (GET, POST, etc.)
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get request path (/users, /login, etc.)
     */
    public function getPath(): string
    {
        return $this->path;
    }

}
