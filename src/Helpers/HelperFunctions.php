<?php

namespace Sebastian\PhpEcommerce\Helpers;

use Sebastian\PhpEcommerce\Services\Response;
use Sebastian\PhpEcommerce\Services\Container;

function redirect($url)
{
    $isExternal = stripos($url, "http://") !== false || stripos($url, "https://") !== false;

    if (!$isExternal) {
        $url = rtrim(BASE_URL, '/') . '/' . ltrim($url, '/');
    }

    if (!headers_sent()) {
        header('Location: ' . $url, true, 302);
    } else {
        echo '<script type="text/javascript">';
        echo 'window.location.href="' . $url . '";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
        echo '</noscript>';
    }
    exit;
}

function respond(array $data, $statusCode = 200)
{
    $response = new Response();

    $response->send($data, $statusCode);
}

function app($service = null)
{
    $c = Container::getInstance();

    if (is_null($service)) {
        return $c;
    }

    return $c[$service];
}

function wait_for($hostname, $port, $timeout = 30)
{
    $start = time();
    $connection = false;

    while (time() < $start + $timeout && !$connection) {
        $connection = fsockopen($hostname, intval($port));
    }
}

function include_partial($file, array $data = [])
{
    extract($data); // in order to bring it into local scope since functions have their own local scope
    $basePath = dirname(__DIR__, 2) . '/src/Views/partials/';
    include $basePath . ltrim($file, '/');
}
