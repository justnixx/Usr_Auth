<?php

namespace App\Classes;

/**
 * Config class
 */
class Config
{
    /**
     * Returns a config value or default
     *
     * @param string $key
     * @param string $default
     * @return string
     */
    public static function get(string $key, $default = '')
    {
        $config = $GLOBALS['config'] ?? [];

        if (isset($config[$key])) {
            return $config[$key];
        }

        return $default;
    }
}