<?php

namespace Shina\Common\Datatype;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 17/01/12
 * Time: 13:55
 */
class Number
{

    /**
     * @var string
     */
    public $value;
    /**
     * @var int
     */
    protected $decimals = 2;
    /**
     * @var string
     */
    protected $dec_point = ',';
    /**
     * @var string
     */
    protected $thousand_point = '.';

    /**
     * @var bool
     */
    protected $isNegative = false;

    /**
     * @var integer
     */
    static public $precision = 14;

    public function __construct($value, $config=Array())
    {
        $this->setValue($value);
        if (count($config))
            $this->setConfig($config);
    }

    public function setConfig(Array $config)
    {
        if (isset($config['decimals']))
            $this->setDecimals($config['decimals']);

        if (isset($config['dec_point']))
            $this->setDecPoint($config['dec_point']);

        if (isset($config['thousand_point']))
            $this->setThousandPoint($config['thousand_point']);

        return $this;
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
     * @param string $dec_point
     * @return Number
     */
    public function setDecPoint($dec_point)
    {
        $this->dec_point = $dec_point;

        return $this;
    }

    /**
     * @return string
     */
    public function getDecPoint()
    {
        return $this->dec_point;
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
     * @param string $thousand_point
     * @return Number
     */
    public function setThousandPoint($thousand_point)
    {
        $this->thousand_point = $thousand_point;

        return $this;
    }

    /**
     * @return string
     */
    public function getThousandPoint()
    {
        return $this->thousand_point;
    }

    public function __call($name, $args)
    {
        if (substr($name, 0, 2) == 'bc')
        {
            return new self($this->bcCalc($name, $args));
        }

        return null;
    }

    /**
     * @param $name
     * @param $args
     * @return int
     */
    protected function bcCalc($name, $args)
    {
        /* @var Number $value */
        $value = current($args);
        if ($value instanceof Percentage)
        {
            $value = $value->of($this);
        }
        elseif (!$value instanceof Number)
        {
            if (is_numeric($value))
            {
                $value = new Number($value);
            }
            else
            {
                trigger_error('BC Functions must be compared with another Number or a valid number');
            }
        }

        if ($name == 'bcdiv' && $value->getValue() == 0)
        {
            $result = 0;
        }
        else
        {
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
        return $this->getValue() == 0;
    }

    /**
     * @param Number $number
     * @return boolean
     */
    public function isGreater($number)
    {
        if ($number instanceof Number)
        {
            $number = $number->getValue();
        }

        return $this->getValue() > $number;
    }

    /**
     * @param Number $number
     * @return boolean
     */
    public function isLess($number)
    {
        if ($number instanceof Number)
        {
            $number = $number->getValue();
        }

        return $this->getValue() < $number;
    }

    /**
     * @param Number $number
     * @return bool
     */
    public function isEqual($number)
    {
        if ($number instanceof Number)
        {
            $number = $number->getValue();
        }

        return $this->getValue() == $number;
    }

    protected function getNumberFormat()
    {
        return number_format((float)$this->getValue(), $this->getDecimals(), $this->getDecPoint(), $this->getThousandPoint());
    }

    /**
     * @param int $precision
     */
    public function setPrecision($precision)
    {
        self::$precision = $precision;
    }

    /**
     * @return int
     */
    public function getPrecision()
    {
        return self::$precision;
    }

}