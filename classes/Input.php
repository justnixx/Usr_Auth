<?php

namespace App\Classes;

/**
 * Input class
 */
class Input
{

    /**
     * Returns true if input exists, otherwise false
     *
     * @param string $type
     * @return boolean
     */
    public static function exists($type = 'POST')
    {
        switch (strtolower($type)) {
            case 'post':
                return (!empty($_POST)) ? true : false;
                break;
            case 'get':
                return (!empty($_GET)) ? true : false;
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * Returns the input value of a given key
     *
     * @param string $key
     * @return string
     */
    public static function get($key)
    {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }

        if (isset($_GET[$key])) {
            return $_GET[$key];
        }

        return '';
    }

    /**
     * Returns an associative array of variables passed to the current scrip via HTTP POST or GET 
     *
     * @return array
     */
    public static function all()
    {
        return !empty($_POST) ? $_POST : (!empty($_GET) ? $_GET : []);
    }
}
