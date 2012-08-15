<?php

namespace ebussola\common\datatype;

use ebussola\common\capacity\Arrayable;
use ebussola\common\capacity\Validateable;


/**
 * @method Number bcadd($number)
 * @method Number bcsub($number)
 * @method Number bcmul($number)
 * @method Number bcdiv($number)
 *
 * @version 1.0b
 */
class Number implements Arrayable, Validateable
{

    /**
     * @var string
     */
    private $value;

    /**
     * @var number\Locale
     */
    private $locale;

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

    public function __construct($value, number\Locale $locale=null)
    {
        $this->locale = new number\pt_BR();
        $this->setValue($value);
    }

    /**
     * @param string $value
     * @return Number
     */
    public function setValue($value)
    {
        if ($value instanceof Number)
        {
            $value = $value->getValue();
        }
        elseif (is_null($value) || $value == '')
        {
            $value = 0;
        }

        if (strstr($value, ','))
        {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }

        $value = preg_replace('/([^-0-9]*)([-]?)([0-9.]*).*/is', '$2$3', $value);
        if ($value < 0)
        {
            $this->isNegative = true;
        }
        else
        {
            $this->isNegative = false;
        }
        $this->value = (string) $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getDecPoint()
    {
        return $this->locale->getDecPoint();
    }

    /**
     * @param integer $decimals
     * @return Number
     */
    public function setDecimals($decimals)
    {
        $this->decimals = $decimals;

        return $this;
    }

    /**
     * @return int
     */
    public function getDecimals()
    {
        return $this->decimals;
    }

    /**
     * @return string
     */
    public function getThousandPoint()
    {
        return $this->locale->getThousandPoint();
    }

    public function __call($name, $args)
    {
        if (substr($name, 0, 2) == 'bc') {
            $this->value = $this->bcCalc($name, $args);
        } else {
            throw new \Exception('Method do not exists. '.$name);
        }

        return $this;
    }

    /**
     * @param $name
     * @param $args
     * @return int
     */
    protected function bcCalc($name, $args)
    {
        if (!extension_loaded('bcmath')) {
            trigger_error('bcmath not loaded, please install the extension: http://php.net/manual/en/book.bc.php');
        }

        /* @var Number $value */
        $value = current($args);
        if ($value instanceof Percentage) {
            switch ($name) {
                case 'bcmul' :
                case 'bcdiv' :
                    $value = $value->bcdiv(100);
                    break;

                default :
                    $value = $value->of($this);
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

    public function __toString()
    {
        return $this->getNumberFormat();
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->getNumberFormat();
    }

    /**
     * @return bool
     */
    public function isNegative()
    {
        return !$this->isZero() && $this->isNegative;
    }

    /**
     * @return bool
     */
    public function isPositive()
    {
        return !$this->isZero() && !$this->isNegative;
    }

    /**
     * @return bool
     */
    public function isZero()
    {
        return bccomp($this->getValue(), 0) === 0;
    }

    /**
     * @param Number|Float $number
     * @return boolean
     */
    public function isGreater($number)
    {
        if ($number instanceof Number) {
            $number = $number->getValue();
        }

        return bccomp($this->getValue(), $number) === 1;
    }

    /**
     * @param Number|Float $number
     * @return boolean
     */
    public function isLess($number)
    {
        if ($number instanceof Number) {
            $number = $number->getValue();
        }

        return bccomp($this->getValue(), $number) === -1;
    }

    /**
     * @param Number|Float $number
     * @return bool
     */
    public function isEqual($number)
    {
        if ($number instanceof Number) {
            $number = $number->getValue();
        }

        return bccomp($this->getValue(), $number) === 0;
    }

    /**
     * Number OF $value is what percentage?
     *
     * @param Number|Float $value
     * @return Percentage
     */
    public function of($value)
    {
        $number = clone $this;
        return new Percentage($number->bcdiv($value)->bcmul(100)->getValue());
    }

    /**
     * Number IS $percentage of what Number?
     *
     * @param Number|float $percentage
     * @return Number
     */
    public function is($percentage)
    {
        $number = clone $this;
        return $number->bcdiv($percentage);
    }

    /**
     * @param int $precision
     */
    public function setPrecision($precision)
    {
        $this->precision = $precision;
    }

    /**
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @param \ebussola\common\datatype\number\Locale $locale
     */
    public function setLocale(number\Locale $locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    private function getNumberFormat()
    {
        return number_format((float)$this->getValue(), $this->getDecimals(), $this->getDecPoint(), $this->getThousandPoint());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'value' => $this->getValue()
        );
    }

    /**
     * @param array $values
     */
    public function fromArray(array $values)
    {
        $defaults = array(
            'value' => 0
        );
        $values = array_merge($defaults, $values);
        $this->setValue($values['value']);
    }

    /**
     * Simple validation check
     * @return boolean
     * Returns True on success or False on fail
     */
    public function isValid()
    {
        try {
            $this->validate();
        } catch (\ebussola\common\exception\InvalidNumber $e) {
            return false;
        }
        return true;
    }

    /**
     * Exceptions must be documented with <at>throws
     * @return boolean
     * Returns True if validation pass or throw an Exception on fail
     */
    public function validate()
    {
        $invalid_number = new \ebussola\common\exception\InvalidNumber();
        $invalid_number->setClassName(__DIR__);

        if ($this->value === null) {
            $invalid_number->addAttributeName('value');
        }
        if (!$this->locale instanceof number\Locale) {
            $invalid_number->addAttributeName('locale');
        }

        if (count($invalid_number->getAttributeNames()) > 0) {
            throw $invalid_number;
        }

        return true;
    }

}