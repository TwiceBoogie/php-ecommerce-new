<?php

namespace Sebastian\PhpEcommerce\Services;

use Sebastian\PhpEcommerce\DTO\ResponseDTO;

class Response
{
    protected $statuses = array(
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        204 => 'No Content',

        301 => 'Moved Permanently',
        302 => 'Found',
        304 => 'Not Modified',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        415 => 'Unsupported Media Type',
        422 => 'Unprocessable Entity',
        429 => 'Too Many Requests',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
    );

    public static function send(array $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json');
        self::setStatusHeader($statusCode);
        echo json_encode($data);
        exit;
    }

    private static function setStatusHeader(int $code): void
    {
        $statuses = (new self())->statuses;
        $text = $statuses[$code] ?? 'Unknown';
        $protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1';

        header("$protocol $code $text", true, $code);
    }
}

