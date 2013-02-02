<?php

namespace ebussola\common\datatype;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 10/02/12
 * Time: 11:58
 */
class DateTime extends \DateTime implements \Serializable
{

    /**
     * @var Array
     */
    static private $language;

    /**
     * @var string
     */
    protected $format;

    public function __construct($time = 'now', \DateTimeZone $timezone=null) {
        $this->setupDefaultFormat();

        if ($time instanceof \DateTime) {
            $time = $time->format('c');
        }

        parent::__construct($time, $timezone);
    }

    public function __toString() {
        return $this->format($this->format);
    }
    
    public function format($format) {
        $str = parent::format($format);

        return str_replace(array(
                'January', 'Jan',
                'February', 'Feb',
                'March', 'Mar',
                'April', 'Apr',
                'May', 'May',
                'June', 'Jun',
                'July', 'Jul',
                'August', 'Aug',
                'September', 'Sep',
                'October', 'Oct',
                'November', 'Nov',
                'December', 'Dec',

                'Saturday', 'Sat',
                'Sunday', 'Sun',
                'Monday', 'Mon',
                'Tuesday', 'Tue',
                'Wednesday', 'Wed',
                'Thursday', 'Thu',
                'Friday', 'Fri'
		    ),
            $this->getLanguage()['language'],
            $str
        );
    }

    protected function getLanguage() {
        if (self::$language === null) {
            $path = __DIR__ . '/datetime/languages/' . \Locale::getDefault() . '.php';
            self::$language = include($path);
        }

        return self::$language;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize() {
        return $this->format('c');
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     *                           The string representation of the object.
     * </p>
     * @return mixed the original value unserialized.
     */
    public function unserialize($serialized) {
        parent::__construct($serialized);
        $this->setupDefaultFormat();
        return $this;
    }

    protected function setupDefaultFormat() {
        $this->format = $this->getLanguage()['date_format'] . ' ' . $this->getLanguage()['time_format'];
    }

}