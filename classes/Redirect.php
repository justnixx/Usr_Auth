<?php

namespace App\Classes;

/**
 * Redirect class
 */
class Redirect
{
    /**
     * Redirects user to a given path or url
     *
     * @param string $path
     * @param integer $delay
     * @return void
     */
    public static function to(string $path, $delay = 0)
    {
        header("Refresh:{$delay};url={$path}");
        exit;
    }

    public static function back()
    {
        $url = isset($_SERVER['HTTP_REFERRER']) ?  $_SERVER['HTTP_REFERRER'] : "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        self::to($url);
    }
}