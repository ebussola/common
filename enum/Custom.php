<?php

namespace Shina\Common\Enum;

use Shina\Common\Datatype\Enum;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 24/04/12
 * Time: 10:40
 */
class Custom extends Enum
{

    /**
     * @var array
     */
    private $_default;

    public function __construct(array $values, $value='UNDEFINED')
    {
        array_unshift($values, 'UNDEFINED');
        $this->_default = $values;

        parent::__construct($value);
    }

    /**
     * @return array
     * Return a list of available options.
     */
    public function defaults()
    {
        return $this->_default;
    }

}
