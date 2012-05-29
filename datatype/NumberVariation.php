<?php

namespace Shina\Common\Datatype;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 15/03/12
 * Time: 16:18
 */
class NumberVariation extends Number
{

    /**
     * @var Number
     */
    private $limit_up, $limit_down;

    public function __construct($value, $limit_up, $limit_down, $config=Array())
    {
        parent::__construct($value, $config);
        $this->setLimitUp($limit_up);
        $this->setLimitDown($limit_down);
    }

    /**
     * @param Number $number
     * @return boolean
     */
    public function isIn(Number $number)
    {
        $l1 = $this->bcadd($this->getLimitUp());
        $l2 = $this->bcadd($this->getLimitDown());

        return ($number->getValue() <= $l1->getValue()) && ($number->getValue() >= $l2->getValue());
    }

    /**
     * @param Number $number
     * @return bool
     */
    public function isOver(Number $number)
    {
        $l1 = $this->bcadd($this->getLimitUp());

        return $number->getValue() > $l1->getValue();
    }

    /**
     * @param Number $number
     * @return bool
     */
    public function isUnder(Number $number)
    {
        $l2 = $this->bcadd($this->getLimitDown());

        return $number->getValue() < $l2->getValue();
    }

    /**
     * @param Number|Percentage $limit_down
     */
    public function setLimitDown($limit_down)
    {
        if ($limit_down instanceof Percentage)
        {
            $limit_down = $limit_down->of($this->getValue());
        }
        elseif (!$limit_down instanceof Number)
        {
            $limit_down = new Number($limit_down);
        }

        if (!$limit_down->isNegative)
        {
            $limit_down = $limit_down->bcmul('-1');
        }

        $this->limit_down = $limit_down;
    }

    /**
     * @return Number
     */
    public function getLimitDown()
    {
        return $this->limit_down;
    }

    /**
     * @param Number|Percentage $limit_up
     */
    public function setLimitUp($limit_up)
    {
        if ($limit_up instanceof Percentage)
        {
            $limit_up = $limit_up->of($this->getValue());
        }
        elseif (!$limit_up instanceof Number)
        {
            $limit_up = new Number($limit_up);
        }

        $this->limit_up = $limit_up;
    }

    /**
     * @return Number
     */
    public function getLimitUp()
    {
        return $this->limit_up;
    }

}
