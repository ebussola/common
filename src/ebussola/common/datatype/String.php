<?php

namespace ebussola\common\datatype;

/**
 * User: Leonardo Shinagawa
 * Date: 12/03/13
 * Time: 00:12
 */
class String {

    /**
     * @var string
     */
    private $value;

    public function __construct($value) {
        $this->value = $value;
    }

    /**
     * Magic method to catch any methods and delegate to string functions of php
     * It also adds chainability
     *
     * @param       $name
     * @param array $args
     * @return String
     */
    public function __call($name, array $args=array()) {
        $param_arr = array_merge(array($this->value), $args);
        return new self(call_user_func_array($name, $param_arr));
    }

    public function __toString() {
        return (string)$this->value;
    }

}
