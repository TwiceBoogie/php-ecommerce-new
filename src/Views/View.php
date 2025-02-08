<?php

namespace Sebastian\PhpEcommerce\Views;

class View
{
    public static function render($view, $data = []): bool|string
    {
        $viewPath = __DIR__ . "/../Views/" . str_replace('.', '/', $view) . ".php";

        if (!file_exists($viewPath)) {
            throw new \Exception("View file not found: $viewPath");
        }

        // Extract data to variables
        extract($data);

        // Start output buffering
        ob_start();
        require $viewPath;

        // Return the rendered content
        return ob_get_clean();
    }
}
