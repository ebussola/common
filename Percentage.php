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

}
