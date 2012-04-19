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
     * @param Number $number
     * @return Number|Currency
     */
    public function of($number)
    {
        return $this->bcmul($number)->bcdiv(100);
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
