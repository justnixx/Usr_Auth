<?php

namespace App\Classes;

class AppError
{
    private $_errors = [];

    /**
     * Initializes the AppError
     *
     * @param string|int $code
     * @param string $message
     */
    public function __construct($code = '', $message = '')
    {
        if (empty($code)) {
            return;
        }

        $this->add($code, $message);
    }

    /**
     * Returns true if error code exists and has a value, otherwise false
     *
     * @param string|int $code
     * @return boolean
     */
    public function has($code)
    {
        return (!empty($this->_errors[$code])) ? true : false;
    }

    /**
     * Adds new error to the errors array
     *
     * @param string|int $code
     * @param string $message
     * @return void
     */
    public function add($code, $message)
    {
        if (!isset($this->_errors[$code])) {
            $this->_errors[$code] = $message;
        }
    }

    /**
     * Returns the value of a given error code
     *
     * @param string|int $code
     * @return string
     */
    public function getMessage($code)
    {
        return $this->_errors[$code];
    }

    /**
     * Returns the errors array
     *
     * @return array
     */
    public function all()
    {
        return $this->_errors;
    }
}