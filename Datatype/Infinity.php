<?php

namespace Shina\Common\Datatype;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 03/04/12
 * Time: 19:18
 */
class Infinity extends Number
{

    public function __construct($polarity='+')
    {
        parent::__construct(0);

        if ($polarity == '+')
        {
            $this->isNegative = false;
        }
        else
        {
            $this->isNegative = true;
        }
    }

    /**
     * @return bool
     */
    public function isNegative()
    {
        return $this->isNegative;
    }

    /**
     * @return bool
     */
    public function isPositive()
    {
        return !$this->isNegative;
    }

    protected function bcCalc($name, $args)
    {
        return $this->getValue();
    }

}
