<?php

namespace App\Classes;

/**
 * Session class
 */
class Session
{

    /**
     * Adds an item to session
     *
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public static function put(string $name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    /**
     * Checks if an item exists in the session
     *
     * @param string $name
     * @return boolean
     */
    public static function exists($name)
    {
        return (isset($_SESSION[$name])) ? true : false;
    }

    /**
     * Checks if an item exists in the session, and has a value
     *
     * @param string $name
     * @return boolean|null
     */
    public static function has($name)
    {
        return (!empty($_SESSION[$name])) ? true : null;
    }

    /**
     * Gets the value of an item in the session
     *
     * @param string $name
     * @return string
     */
    public static function get($name)
    {
        return $_SESSION[$name];
    }

    /**
     * Returns all the variables stored in the session
     *
     * @return void
     */
    public static function all()
    {
        return $_SESSION;
    }

    /**
     * Fashes data to the session
     *
     * @param string $name
     * @param string $message
     * @return string|void
     */
    public static function flash(string $name, $message = '')
    {
        if (self::has($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        }

        self::put($name, $message);
    }

    /**
     * Deletes an item from the session
     *
     * @param sting $name
     * @return void
     */
    public static function delete($name)
    {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }
}