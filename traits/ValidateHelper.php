<?php

namespace App\Traits;

/**
 * ValidateHelper trait
 */
trait ValidateHelper
{
    /**
     * Returns true if the value passed is a valid email address, otherwise false
     *
     * @param string $email
     * @return boolean
     */
    public function isEmail(string $email)
    {
        $sanitize = filter_var($email, FILTER_SANITIZE_EMAIL);

        if (filter_var($sanitize, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        return false;
    }

    /**
     * Returns true if the value passed are alphabets, otherwise false
     *
     * @param string $str
     * @return boolean
     */
    public function isAlpha(string $str)
    {
        if (preg_match('/^[a-zA-Z]*$/', $str)) {
            return true;
        }

        return false;
    }
}