<?php

namespace ebussola\common\datatype;

use ebussola\common\capacity\Arrayable;
use ebussola\common\exception\InvalidEnum;
use ebussola\common\capacity\Validatable;

/**
 * @version 1.0b
 */
abstract class Enum implements Validatable {

    /**
     * @var String
     */
    private $value;

    /**
     * @var Array
     */
    private $_indexes;

    /**
     * @param String | Integer $value
     */
    public function __construct($value='UNDEFINED') {
        $this->set($value);
        $this->_indexes = array();
    }

    /**
     * @abstract
     * @return array
     * Return a list of available options.
     */
    abstract public function defaults();

    /**
     * Generally return __CLASS__ is the best fit ;)
     *
     * @abstract
     * @return String
     * Return the name identifying the Enum class
     */
    abstract public function enumId();

    /**
     * @param bool $throwException
     * @return boolean
     * Returns True on success or False on fail
     * If the flag $throwException is true, an \InvalidArgumentException will be throwed on fail
     */
    public function isValid($throwException = false) {
        if (!in_array($this->value, $this->defaults())) {
            if ($throwException) {
                throw new InvalidEnum("Wrong enumerator value ({$this->enumId()})");
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * Alias to setValue
     * @param Integer | String $value
     */
    public function set($value) {
        $this->setValue($value);
    }

    /**
     * @param Integer | String $value
     */
    public function setValue($value) {
        if (!is_string($value)) {
            $defaults = $this->defaults();
            $value = (isset($defaults[$value])) ? $defaults[$value] : null;
        }
        $this->value = $value;
    }

    /**
     * Alias to getValue
     * @return String
     */
    public function get() {
        return $this->getValue();
    }

    /**
     * @return String
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @return Integer
     */
    public function getIndex() {
        if (count($this->_indexes) == 0) {
            foreach ($this->defaults() as $i => $v) {
                $this->_indexes[$v] = $i;
            }
        }
        return $this->_indexes[$this->get()];
    }

    /**
     * @return String
     */
    public function __toString() {
        return $this->get();
    }

}