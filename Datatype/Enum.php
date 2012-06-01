<?php

namespace Shina\Common\Datatype;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 21/12/11
 * Time: 17:32
 */
abstract class Enum
{

    /**
     * @var string
     */
    public $_value;

    /**
     * @var array
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
     * @param int | string $value
     * @throws \Exception
     */
    public function set($value)
    {
        if (is_string($value)) {
            if (!in_array($value, $this->defaults())) {
                throw new \Exception('Wrong enumerator value');
            }
        } else {
            $defaults = $this->defaults();
            if (!isset($defaults[$value])) {
                throw new \Exception('Wrong enumerator value');
            }
            $value = $defaults[$value];
        }

        $this->_value = $value;
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->_value;
    }

    /**
     * @return integer
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