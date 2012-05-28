<?php

namespace Shina\Common\Datatype;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 17/01/12
 * Time: 14:26
 */
class Percentage extends Number
{

    /**
     * @var boolean
     */
    protected $show_symbol = true;

    /**
     * Percentage OF $number is how much Number?
     *
     * @param Number | (numeric) $number
     * @return Number
     * Depending on the input, but always an object extended of Number
     *
     * @throws \InvalidArgumentException
     */
    public function of($number)
    {
        $value = $this->bcmul($number)->bcdiv(100);
        if ($number instanceof Number) {
            $class = get_class($number);
            $value = new $class($value);
        } else {
            if (is_numeric($number)) {
                $value = new Number($value);
            } else {
                throw new \InvalidArgumentException();
            }
        }
        return $value;
    }

    /**
     * @param boolean $value
     * @return Percentage
     */
    public function setShowSymbol($value)
    {
        $this->show_symbol = $value;

        return $this;
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function toString()
    {
        if ($this->show_symbol)
        {
            return parent::toString() . '%';
        }
        else
        {
            return parent::toString();
        }
    }

    public function __call($name, $args)
    {
        if (substr($name, 0, 2) == 'bc')
        {
            return new self($this->bcCalc($name, $args));
        }
        else
        {
            trigger_error('Method do not exists. '.$name);
        }

        return null;
    }

    protected function bcCalc($name, $args)
    {
        /* @var Number $value */
        $value = current($args);
        if ($value instanceof Percentage)
        {
            $value = new Number($value);
        }

        return parent::bcCalc($name, array($value));
    }

}
