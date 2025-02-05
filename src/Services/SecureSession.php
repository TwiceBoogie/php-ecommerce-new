<?php

namespace Sebastian\PhpEcommerce\Services;

class SecureSession
{
    public static function startSession($config)
    {
        ini_set('session.use_only_cookies', $config['Session']['UseOnlyCookies']);

        $cookieParams = session_get_cookie_params();
        session_set_cookie_params(
            $cookieParams["lifetime"],
            $cookieParams["path"],
            $cookieParams["domain"],
            $config['Session']['Secure'],
            $config['Session']['HttpOnly']
        );

        session_start();
    }

    public static function destroySession()
    {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );

        session_destroy();
    }

    public static function regenerate($deleteOldSession = true)
    {
        return session_regenerate_id($deleteOldSession);
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function destroy($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function get($key, $default = null)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return $default;
    }

    public static function getSessionId()
    {
        return session_id();
    }
}
