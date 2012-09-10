<?php

namespace ebussola\common\datatype\number;

use ebussola\common\datatype\Number;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 03/04/12
 * Time: 19:18
 */
class Infinity extends Number {
    /**
     * An Infinity number represents the infinity value, it can be positive or negative.
     * Internally, Infinity is always Zero, but with polarity (+ or -) defining the direction.
     */

    /**
     * @param string $polarity
     */
    public function __construct($polarity = '+') {
        parent::__construct(0);

        if ($polarity == '+') {
            $this->setIsNegative(false);
        } else {
            $this->setIsNegative(true);
        }
    }

    /**
     * @return bool
     */
    public function isNegative() {
        return $this->isNegative();
    }

    /**
     * @return bool
     */
    public function isPositive() {
        return !$this->isNegative();
    }

    /**
     * Just to certifies that infinity will never be other number than 0
     */
    protected function bcCalc($name, $args) {
        return $this->getValue();
    }

}
