<?php

namespace ebussola\common\datatype;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 10/02/12
 * Time: 11:52
 */
class Time extends DateTime
{

    /**
     * @var String
     */
    protected $format;

    public function __construct($time = 'now', \DateTimeZone $timezone = null) {
        parent::__construct($time, $timezone);
        $this->setDate(1, 1, 1);
    }

    public function __toString() {
        return $this->format($this->format);
    }

    protected function setupDefaultFormat() {
        $this->format = $this->getLanguage()['time_format'];
    }

}
