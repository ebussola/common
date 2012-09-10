<?php

namespace ebussola\common\datatype\number;

use ebussola\common\datatype\Number;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 15/03/12
 * Time: 16:18
 */
class NumberVariation extends Number {
    /**
     * NumberVariation is a number that can vary in a limit.
     * If the number 10, varies 1 to up and down:
     * * 9, 10 and 11 is equals
     * * 12 is greater
     * * 8 is less
     */

    /**
     * @var Number
     */
    private $limitUp;

    /**
     * @var Number
     */
    private $limitDown;

    /**
     * @param Number|String $value
     * Base value
     *
     * @param Number|String $limitUp
     * How much this number vary to up
     *
     * @param Number|String $limitDown
     * How much this number vary to down
     */
    public function __construct($value = 0, $limitUp = 0, $limitDown = 0) {
        parent::__construct($value);
        $this->setLimitUp($limitUp);
        $this->setLimitDown($limitDown);
    }

    /**
     * @param Number $number
     * @return boolean
     */
    public function isEqual(Number $number) {
        $l1 = clone $this;
        $l2 = clone $this;
        $l1->bcadd($this->getLimitUp());
        $l2->bcadd($this->getLimitDown());

        return (($number->isLess($l1) || $number->isEqual($l1)) && ($number->isGreater($l2) || $number->isEqual($l2)));
    }

    /**
     * @param Number $number
     * @return bool
     */
    public function isGreater(Number $number) {
        $l1 = clone $this;
        $l1->bcadd($this->getLimitUp());

        return $number->isGreater($l1);
    }

    /**
     * @param Number $number
     * @return bool
     */
    public function isLess(Number $number) {
        $l2 = clone $this;
        $l2->bcadd($this->getLimitDown());

        return $number->isLess($l2);
    }

    /**
     * @param Number|Percentage|String $limitDown
     */
    public function setLimitDown($limitDown) {
        if ($limitDown instanceof Percentage) {
            $limitDown = $limitDown->of($this->getValue());
        } elseif (!$limitDown instanceof Number) {
            $limitDown = new Number($limitDown);
        }

        if ($limitDown->isPositive()) {
            $limitDown->bcmul('-1');
        }

        $this->limitDown = $limitDown;
    }

    /**
     * @return Number
     */
    public function getLimitDown() {
        return $this->limitDown;
    }

    /**
     * @param Number|Percentage|String $limitUp
     */
    public function setLimitUp($limitUp) {
        if ($limitUp instanceof Percentage) {
            $limitUp = $limitUp->of($this->getValue());
        } elseif (!$limitUp instanceof Number) {
            $limitUp = new Number($limitUp);
        }

        $this->limitUp = $limitUp;
    }

    /**
     * @return Number
     */
    public function getLimitUp() {
        return $this->limitUp;
    }

}
