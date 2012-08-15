<?php

namespace ebussola\common\exception;

/**
 * User: Leonardo Shinagawa
 * Date: 15/08/12
 * Time: 00:05
 */
class InvalidObject extends \Exception
{

    /**
     * @var String
     */
    private $class_name;

    /**
     * @var Array
     */
    private $attribute_names = array();

    /**
     * @return String
     */
    public function getClassName()
    {
        return $this->class_name;
    }

    /**
     * @param String $class_name
     */
    public function setClassName($class_name)
    {
        $this->class_name = $class_name;
    }

    /**
     * @return Array
     */
    public function getAttributeNames()
    {
        return $this->attribute_names;
    }

    /**
     * @param String $attribute_names
     */
    public function addAttributeName($attribute_names)
    {
        $this->attribute_names[] = $attribute_names;
    }

}
