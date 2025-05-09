<?php

use Dotenv\Dotenv;

$envPath = __DIR__ . '/../.env';
if (!file_exists($envPath)) {
    error_log(".env not found at: $envPath");
    die('Missing .env file');
}

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Base config
$config = [
    'App' => [
        'Name' => getenv('APP_NAME') ?? 'DefaultApp',
        'Environment' => getenv('APP_ENV') ?? 'production',
        'Debug' => filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN),
        'Url' => rtrim(getenv('APP_URL'), '/'),
    ],
    'Database' => [
        'Type' => getenv('DB_TYPE') ?? 'mysql',
        'Host' => getenv('DB_HOST') ?? 'localhost',
        'Name' => getenv('DB_NAME') ?? 'default_db',
        'User' => getenv('DB_USER') ?? 'root',
        'Password' => getenv('DB_PASSWORD') ?? '',
    ],
    'Session' => [
        'Secure' => filter_var(getenv('SESSION_SECURE'), FILTER_VALIDATE_BOOLEAN),
        'HttpOnly' => filter_var(getenv('SESSION_HTTP_ONLY'), FILTER_VALIDATE_BOOLEAN),
        'UseOnlyCookies' => filter_var(getenv('SESSION_USE_ONLY_COOKIES'), FILTER_VALIDATE_BOOLEAN),
        'Lifetime' => getenv('SESSION_LIFETIME') ?? 60,
    ],
    'Hasher' => [
        'Cost' => '13',
        'Salt' => getenv('PASSWORD_SALT') ?? 'randomSalt',
    ]
];

// Load environment-specific config
$environment = getenv('APP_ENV') ?? 'production';
$envConfigFile = __DIR__ . "/environments/{$environment}.php";

if (file_exists($envConfigFile)) {
    $envConfig = include $envConfigFile;
    $config = array_merge($config, $envConfig); // Merge configs
}

define('BASE_URL', $config['App']['Url']); // Define BASE_URL as a constant globally
define('ENVIRONMENT', $config['App']['Environment']);

return $config;
