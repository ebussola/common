<?php

namespace Shina\Common\Datatype;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 10/02/12
 * Time: 11:52
 */
class Time extends DateTime
{

    protected $format = 'h:i:s';

    public function __construct($time = 'now', DateTimeZone $timezone = null)
    {
        parent::__construct($time, $timezone);
        $this->setDate(0, 0, 0);
    }

    public function __toString()
    {
        return $this->format($this->format);
    }

}
