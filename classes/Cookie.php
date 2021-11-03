<?php

namespace App\Classes;

/**
 * Cookie class
 */
class Cookie
{
    /**
     * Sends a cookie to the HTTP headers
     *
     * @param string $name
     * @param string $value
     * @param integer $expires
     * @param string $path
     * @param string $domain
     * @param boolean $secure
     * @param boolean $httponly
     * @return boolean
     */
    public static function put(string $name, string $value, $expires = 0, $path = '/', $domain = '', $secure = false, $httponly = false)
    {
        return setcookie($name, $value, (time() + $expires), $path, $domain, $secure, $httponly);
    }

    /**
     * Checks if a cookie exists
     *
     * @param string $name
     * @return boolean
     */
    public static function exists(string $name)
    {
        return (isset($_COOKIE[$name])) ? true : false;
    }

    /**
     * Checks if a cookie exists and has a value
     *
     * @param string $name
     * @return boolean
     */
    public static function has(string $name)
    {
        return (!empty($_COOKIE[$name])) ? true : false;
    }

    /**
     * Gets a cookie value
     *
     * @param string $name
     * @return string
     */
    public static function get(string $name)
    {
        return $_COOKIE[$name];
    }

    /**
     * Deletes a cookie
     *
     * @param string $name
     * @return boolean
     */
    public static function delete(string $name)
    {
        return setcookie($name, '', (time() - 1));
    }
}