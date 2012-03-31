<?php

namespace Shina\Common\Datatype;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 21/12/11
 * Time: 17:32
 */
abstract class Enum
{

    protected $_default = array();
    public $_value;

    public function __construct($value)
    {
        $this->set($value);
    }

    public function set($value)
    {
        if (!in_array($value, $this->_default))
        {
            throw new \Exception('Wrong enumerator value');
        }

        $this->_value = (string)$value;
    }

    public function get()
    {
        return $this->_value;
    }

    public function __toString()
    {
        return $this->get();
    }

}