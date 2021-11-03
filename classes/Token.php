<?php

namespace App\Classes;

/**
 * Token class
 */
class Token
{

    /**
     * Generates a token and stores it in the session
     *
     * @return string
     */
    public static function generate()
    {
        return Session::put(Config::get('SESSION_TOKEN'), md5(uniqid()));
    }

    /**
     * Checks if a session is valid
     *
     * @param string $token
     * @return boolean
     */
    public static function check(string $token)
    {
        $tokenName = Config::get('SESSION_TOKEN');

        if (Session::has($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }

        return false;
    }
}