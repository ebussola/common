<?php

namespace ebussola\common\datatype;

use ebussola\common\exception\InvalidNumber;
use ebussola\common\capacity\Validatable;


/**
 * @method \ebussola\common\datatype\Number bcadd($number) make the calc and return itself
 * @method \ebussola\common\datatype\Number bcsub($number) make the calc and return itself
 * @method \ebussola\common\datatype\Number bcmul($number) make the calc and return itself
 * @method \ebussola\common\datatype\Number bcdiv($number) make the calc and return itself
 *
 * @version 1.0b
 */
class Number implements Validatable {

    /**
     * @var string
     */
    private $value;

    /**
     * @var int
     */
    private $decimals = 2;

    /**
     * @var bool
     */
    private $isNegative = false;

    /**
     * @var integer
     */
    private $precision = 14;

    /**
     * @param string $value
     */
    public function __construct($value = 0) {
        if (!extension_loaded('bcmath')) {
            trigger_error('bcmath not loaded, please install the extension: http://php.net/manual/en/book.bc.php');
        }

        $this->setValue($value);
    }

    /**
     * @param string|\ebussola\common\datatype\Number $value
     * @return Number
     */
    public function setValue($value) {
        if ($value instanceof Number) {
            $value = $value->getValue();
        } elseif (is_null($value) || $value == '') {
            $value = 0;
        }

        $value = $this->normalize($value);

        $value = preg_replace('/([^-0-9]*)([-]?)([0-9.]*).*/is', '$2$3', $value); //Clean all invalid chars
        if ($value < 0) {
            $this->isNegative = true;
        } else {
            $this->isNegative = false;
        }
        $this->value = (string)$value;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param integer $decimals
     * @return Number
     */
    public function setDecimals($decimals) {
        $this->decimals = $decimals;

        return $this;
    }

    /**
     * @return int
     */
    public function getDecimals() {
        return $this->decimals;
    }

    public function __call($name, $args) {
        if (substr($name, 0, 2) === 'bc') {
            $this->value = $this->bcCalc($name, $args);
        } else {
            throw new \Exception('Method do not exists. ' . $name);
        }

        return $this;
    }

    /**
     * @param $name
     * @param $args
     * @return String
     */
    protected function bcCalc($name, $args) {
        $value = current($args);
        if ($value instanceof number\Percentage) {
            switch ($name) {
                case 'bcmul' :
                case 'bcdiv' :
                    $value->bcdiv(100);
                    break;

                default :
                    $value->of($this);
                    break;
            }
        } elseif (!$value instanceof Number) {
            if (is_numeric($value)) {
                $value = new Number($value);
            } else {
                trigger_error('BC Functions must be compared with another Number or a valid number');
            }
        }

        if ($name == 'bcdiv' && $value->getValue() == 0) {
            $result = 0;
        } else {
            $result = $name($this->getValue(), $value->getValue(), $this->getPrecision());
        }

        return $result;
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->getNumberFormat();
    }

    /**
     * @return bool
     */
    public function isNegative() {
        return (!$this->isZero() && $this->isNegative);
    }

    /**
     * @return bool
     */
    public function isPositive() {
        return (!$this->isZero() && !$this->isNegative);
    }

    /**
     * @return bool
     */
    public function isZero() {
        return bccomp($this->getValue(), 0) === 0;
    }

    /**
     * @param \ebussola\common\datatype\Number|String $number
     * @return boolean
     */
    public function isGreater($number) {
        if ($number instanceof Number) {
            $number = $number->getValue();
        }

        return bccomp($this->getValue(), $number) === 1;
    }

    /**
     * @param \ebussola\common\datatype\Number|String $number
     * @return boolean
     */
    public function isLess($number) {
        if ($number instanceof Number) {
            $number = $number->getValue();
        }

        return bccomp($this->getValue(), $number) === -1;
    }

    /**
     * @param \ebussola\common\datatype\Number|String $number
     * @return bool
     */
    public function isEqual($number) {
        if ($number instanceof Number) {
            $number = $number->getValue();
        }

        return bccomp($this->getValue(), $number) === 0;
    }

    /**
     * Number OF $value is what percentage?
     *
     * @param \ebussola\common\datatype\Number|String $value
     * @return number\Percentage
     */
    public function of($value) {
        $number = clone $this;
        return new number\Percentage($number->bcdiv($value)->bcmul(100)->getValue());
    }

    /**
     * Number IS $percentage of what Number?
     *
     * @param Number|float $percentage
     * @return Number
     */
    public function is($percentage) {
        $number = clone $this;
        return $number->bcdiv($percentage);
    }

    /**
     * @param int $precision
     */
    public function setPrecision($precision) {
        $this->precision = $precision;
    }

    /**
     * @return int
     */
    public function getPrecision() {
        return $this->precision;
    }

    /**
     * @param bool $throwException
     * @return boolean
     * Returns True on success or False on fail
     * If the flag $throwException is true, an \InvalidArgumentException will be throwed on fail
     */
    public function isValid($throwException = false) {
        if (!is_numeric($this->value)) {
            if ($throwException) {
                throw new InvalidNumber("Invalid value, must be an numeric value.");
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * Used to make calculations without change the value
     * Instead of change the value of itself, preserve it and return the calculated value
     *
     * @return Number
     */
    public function preserve() {
        return clone $this;
    }

    /**
     * @return string
     */
    private function getNumberFormat() {
        $numberFormatter = new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::DECIMAL);
        $decPoint = $numberFormatter->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
        $thousandPoint = $numberFormatter->getSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL);

        return number_format((float)$this->getValue(), $this->getDecimals(), $decPoint, $thousandPoint);
    }

    /**
     * @param String $number
     * @return String
     */
    private function normalize($number) {
        $numberFormatter = new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::DECIMAL);
        $decPoint = $numberFormatter->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
        $thousandPoint = $numberFormatter->getSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL);
        if (strstr($number, $decPoint)) {
            $number = str_replace($thousandPoint, '', $number);
            $number = str_replace($decPoint, '.', $number);
        }
        return $number;
    }

    /**
     * @param boolean $bool
     */
    protected function setIsNegative($bool) {
        $this->isNegative = (bool)$bool;
    }

    /**
     * @param String $value
     */
    protected function setRawValue($value) {
        $this->value = $value;
    }

}