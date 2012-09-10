<?php

namespace ebussola\common\datatype\number;

use ebussola\common\datatype\Number;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 05/12/11
 * Time: 11:20
 */
class Currency extends Number {

    public function __toString() {
        $numberFormatter = new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::CURRENCY);
        $result = $numberFormatter->format($this->getValue());
        if ($this->isNegative()) {
            $result = '(' . str_replace('-', '', $result) . ')';
        }

        return $result;
    }

    /**
     * @param Number|string $value
     * @return Currency
     */
    public function setValue($value) {
        parent::setValue($value);

        if (preg_match('/^\(.*\)$/is', trim($value))) {
            $this->setIsNegative(true);
            $this->setRawValue('-' . $this->getValue());
        }

        return $this;
    }

}