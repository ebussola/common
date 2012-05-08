<?php

namespace Shina\Common\Datatype;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 10/02/12
 * Time: 11:58
 */
class DateTime extends \DateTime
{

    protected $format = 'd/m/Y h:i:s';

    public function __construct($time = 'now')
    {
        if ($time instanceof \DateTime)
        {
            $time = $time->format('c');
        }

        parent::__construct($time);
    }

    public function __toString()
    {
        return $this->format($this->format);
    }

}