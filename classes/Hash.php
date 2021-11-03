<?php

namespace App\Classes;

class Hash
{
    /**
     * Creates a password hash and returns it
     *
     * @param string $password
     * @param string|int|null $algo
     * @param array $options
     * @return string
     */
    public static function make(string $password, $algo = PASSWORD_DEFAULT, $options = [])
    {
        return password_hash($password, $algo, $options);
    }


    /**
     * Checks if the given hash matches the given options
     *
     * @param string $password
     * @param string $hash
     * @return boolean
     */
    public static function check(string $password, string $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Generates a random hash value
     *
     * @return string
     */
    public static function rand()
    {
        return hash('sha256', uniqid());
    }
}