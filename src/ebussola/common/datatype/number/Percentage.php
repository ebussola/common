<?php

namespace ebussola\common\datatype\number;

use ebussola\common\datatype\Number;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 17/01/12
 * Time: 14:26
 */
class Percentage extends Number {

    /**
     * @var boolean
     */
    private $showSymbol = true;

    /**
     * Percentage OF $number is how much Number?
     *
     * @param Number|String $number
     * @return Number
     * Depending on the input, but always an object extended of Number
     *
     * @throws \InvalidArgumentException
     */
    public function of($number) {
        $value = clone $this;
        $value->bcmul($number)->bcdiv(100);
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
    public function setShowSymbol($value) {
        $this->showSymbol = (bool)$value;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString() {
        if ($this->showSymbol) {
            return parent::__toString() . '%';
        } else {
            return parent::__toString();
        }
    }

}
