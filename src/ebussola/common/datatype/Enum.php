<?php

namespace ebussola\common\datatype;

use ebussola\common\capacity\Arrayable;
use ebussola\common\exception\InvalidEnum;
use ebussola\common\capacity\Validateable;

/**
 * @version 1.0b
 */
abstract class Enum implements Arrayable, Validateable
{

    /**
     * @var String
     */
    private $_value;

    /**
     * @var Array
     */
    private $_indexes;

    public function __construct($value)
    {
        $this->set($value);
    }

    /**
     * @abstract
     * @return array
     * Return a list of available options.
     */
    abstract public function defaults();

    /**
     * @abstract
     * @return String
     * Return the name identifying the Enum class
     */
    abstract public function enumId();

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'value' => $this->get()
        );
    }

    /**
     * @param array $values
     */
    public function fromArray(array $values)
    {
        $defaults = array('value' => null);
        $values = array_merge($defaults, $values);
        $this->set($values['value']);
    }

    /**
     * Simple validation check
     * @return boolean
     * Returns True on success or False on fail
     */
    public function isValid()
    {
        try {
            $this->validate();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @return boolean
     * Returns True if validation pass or throw an Exception on fail
     * @throws InvalidEnum
     */
    public function validate()
    {
        if (!in_array($this->_value, $this->defaults())) {
            $invalid_enum = new InvalidEnum('Wrong enumerator value');
            $invalid_enum->setClassName($this->enumId());
            throw $invalid_enum;
        }
        return true;
    }

    /**
     * @param Integer | String $value
     */
    public function set($value)
    {
        if (!is_string($value)) {
            $defaults = $this->defaults();
            $value = (isset($defaults[$value])) ? $defaults[$value] : null;
        }
        $this->_value = $value;
    }

    /**
     * @return String
     */
    public function get()
    {
        return $this->_value;
    }

    /**
     * @return Integer
     */
    public function index()
    {
        if (is_null($this->_indexes)) {
            $this->_indexes = array();
            foreach ($this->defaults() as $i => $v) {
                $this->_indexes[$v] = $i;
            }
        }
        return $this->_indexes[$this->get()];
    }

    public function __toString()
    {
        return $this->get();
    }

}