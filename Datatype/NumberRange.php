<?php

namespace ebussola\common\datatype\number;

use ebussola\common\datatype\Number;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 03/04/12
 * Time: 19:13
 */
class NumberRange extends Number {

    /**
     * @var Number
     */
    private $limit_down;

    /**
     * @var Number
     */
    private $limit_up;

    /**
     * @var Number
     */
    private $diff;

    public function __construct($initial_value, $limit_down = null, $limit_up = null) {
        $this->setLimitDown($limit_down);
        $this->setLimitUp($limit_up);
        $this->setDiff(new Number(0));
        parent::__construct($initial_value);
    }

    /**
     * @param $value
     * @return void
     */
    public function setValue($value) {
        parent::setValue($value);

        if ((!$this->limitUp() instanceof Infinity) && ($this->getRealValue() > $this->limitUp()->getValue())) {
            $this->setDiff(bcsub($this->getRealValue(), $this->limitUp()->getValue(), $this->getPrecision()));
        } elseif ((!$this->limitDown() instanceof Infinity) && ($this->getRealValue() < $this->limitDown()->getValue())
        ) {
            $this->setDiff(bcsub($this->limitDown()->getValue(), $this->getRealValue(), $this->getPrecision()) * -1);
        }
        else {
            $this->setDiff(0);
        }
    }

    public function getValue() {
        return bcsub(parent::getValue(), $this->diff()->getValue(), $this->getPrecision());
    }

    public function getRealValue() {
        return parent::getValue();
    }

    /**
     * @param Number|integer $number
     * @return self
     */
    public function add($number) {
        if (!$number instanceof Number) {
            $number = new Number($number);
        }
        $real_value = new Number($this->getRealValue());
        $this->setValue($real_value->bcadd($number)->getValue());

        return $this;
    }

    /**
     * @param Number|integer $number
     * @return self
     */
    public function sub($number) {
        if (!$number instanceof Number) {
            $number = new Number($number);
        }
        $real_value = new Number($this->getRealValue());
        $this->setValue($real_value->bcsub($number)->getValue());

        return $this;
    }

    /**
     * @param Number $limit_down
     */
    public function setLimitDown($limit_down = null) {
        if (is_null($limit_down)) {
            $limit_down = new Infinity('-');
        } elseif (!$limit_down instanceof Number) {
            $limit_down = new Number($limit_down);
        }

        $this->limit_down = $limit_down;
    }

    /**
     * @return Number
     */
    public function limitDown() {
        return $this->limit_down;
    }

    /**
     * @param Number $limit_up
     */
    public function setLimitUp($limit_up = null) {
        if (is_null($limit_up)) {
            $limit_up = new Infinity('+');
        } elseif (!$limit_up instanceof Number) {
            $limit_up = new Number($limit_up);
        }

        $this->limit_up = $limit_up;
    }

    /**
     * @return Number
     */
    public function limitUp() {
        return $this->limit_up;
    }

    /**
     * @param Number $diff
     */
    private function setDiff($diff) {
        if (!$diff instanceof Number) {
            $diff = new Number($diff);
        }

        $this->diff = $diff;
    }

    /**
     * @return Number
     */
    public function diff() {
        return $this->diff;
    }

    public function cutDiff() {
        $this->setValue($this->getValue());
    }

}