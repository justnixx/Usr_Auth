<?php

namespace App\Classes;

use App\Interfaces\ErrorInterface;
use App\Traits\ValidateHelper;

class Validate implements ErrorInterface
{
    use ValidateHelper;

    private $_db,
        $_errors;

    /**
     * Initializes the Validate
     */
    public function __construct()
    {
        $this->_db     = DB::getInstance();
        $this->_errors = new AppError();
    }

    /**
     * Validates data against given rules
     *
     * @param array $data
     * @param array $rules
     * @return object
     */
    public function check(array $data, $rules = [])
    {
        if (count($rules)) {
            $psc = 'parse_snake_case';
            $tc  = 'title_case';

            foreach ($rules as $field => $ruleString) {
                $ruleValues = explode('|', $ruleString);
                $value = $data[$field] ?? '';
                $value = trim($value);

                foreach ($ruleValues as $rule) {
                    if (false === strpos($rule, ':', 0)) {
                        if ('required' === $rule && empty($value)) {
                            $this->_errors->add($field, "{$psc($tc($field))} is required.");
                        } else {
                            switch ($rule) {
                                case 'alpha':
                                    if (!$this->isAlpha($value)) {
                                        $this->_errors->add($field, "{$psc($tc($field))} must be alphabets only.");
                                    }
                                    break;
                                case 'email':
                                    if (!$this->isEmail($value)) {
                                        $this->_errors->add($field, "{$psc($tc($field))} must be  a valid email.");
                                    }
                                    break;
                            }
                        }
                    } else {
                        $ruleSet = explode(':', $rule);
                        switch ($ruleSet[0]) {
                            case 'min':
                                if (strlen($value) < $ruleSet[1]) {
                                    $this->_errors->add($field, "{$psc($tc($field))} must not be less than {$ruleSet[1]} characters.");
                                }
                                break;
                            case 'max':
                                if (strlen($value) > $ruleSet[1]) {
                                    $this->_errors->add($field, "{$psc($tc($field))} must not be greater than {$ruleSet[1]} characters.");
                                }
                                break;
                            case 'matches':
                                if (!empty($data[$ruleSet[1]]) && $value !== $data[$ruleSet[1]]) {
                                    $this->_errors->add($field, "{$psc($tc($field))} and {$psc($ruleSet[1])} do not match.");
                                }
                                break;
                            case 'unique':
                                if ($this->_db->exists($ruleSet[1], [$field, '=', $value])) {
                                    $this->_errors->add($field, "{$psc($tc($field))} already exists.");
                                }
                                break;
                        }
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Returns true if the validation passed, otherwise false
     *
     * @return boolean
     */
    public function passed()
    {
        return (empty($this->errors()->all())) ? true : false;
    }


    /**
     * Returns the errors object
     *
     * @return object
     */
    public function errors()
    {
        return $this->_errors;
    }
}