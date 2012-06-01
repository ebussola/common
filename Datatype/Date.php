<?php

namespace Shina\Common\Datatype;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 12/12/11
 * Time: 18:27
 */
class Date extends DateTime
{

    protected $format = 'd/m/Y';

    public function __construct ($time='now', \DateTimeZone $timezone=null)
    {
        parent::__construct($time, $timezone);
        $this->setTime(0, 0, 0);
    }

    public function __toString()
    {
        return $this->format($this->format);
    }

}
