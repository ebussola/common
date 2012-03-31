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

    public function getValue()
    {
        return $this->value;
    }

    public function setDecPoint($dec_point)
    {
        $this->dec_point = $dec_point;

        return $this;
    }

    public function getDecPoint()
    {
        return $this->dec_point;
    }

    public function setDecimals($decimals)
    {
        $this->decimals = $decimals;

        return $this;
    }

    public function getDecimals()
    {
        return $this->decimals;
    }

    public function setThousandPoint($thousand_point)
    {
        $this->thousand_point = $thousand_point;

        return $this;
    }

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
            $result = $name($this->getValue(), $value->getValue(), 14);
        }

        return $result;
    }

    public function __toString()
    {
        return $this->getNumberFormat();
    }

    public function toString()
    {
        return $this->getNumberFormat();
    }

    protected function getNumberFormat()
    {
        return number_format((float)$this->getValue(), $this->getDecimals(), $this->getDecPoint(), $this->getThousandPoint());
    }

}