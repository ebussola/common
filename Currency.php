<?php

namespace Shina\Common\Datatype;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 05/12/11
 * Time: 11:20
 */
class Currency extends Number
{

    /**
     * @var string
     */
    protected $currency_symbol = 'R$';

    /**
     * @var string
     */
    protected $default_format = '% $';

    public function setConfig(array $config)
    {
        if (isset($config['currency_symbol']))
            $this->setCurrencySymbol($config['currency_symbol']);

        parent::setConfig($config);
    }

    public function setCurrencySymbol($currency_symbol)
    {
        $this->currency_symbol = $currency_symbol;

        return $this;
    }

    public function getCurrencySymbol()
    {
        return $this->currency_symbol;
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function toString()
    {
        return $this->format($this->getDefaultFormat());
    }

    public function format($format)
    {
        $value = str_replace('$', $this->getNumberFormat(), $format);
        $result = str_replace('%', $this->getCurrencySymbol(), $value);
        if ($this->isNegative)
        {
            $result = '('.str_replace('-', '', $result).')';
        }

        return $result;
    }

    public function setDefaultFormat($format)
    {
        $this->default_format = $format;

        return $this;
    }

    public function getDefaultFormat()
    {
        return $this->default_format;
    }

    public function __call($name, $args)
    {
        if (substr($name, 0, 2) == 'bc')
        {
            return new self($this->bcCalc($name, $args));
        }

        return null;
    }

    public function setValue($value)
    {
        parent::setValue($value);

        if (preg_match('/^\(.*\)$/is', trim($value)))
        {
            $this->isNegative = true;
            $this->value = '-'.$this->value;
        }

        return $this;
    }

}