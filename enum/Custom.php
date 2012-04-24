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

    public function __construct(array $values, $value='UNDEFINED')
    {
        $values[] = 'UNDEFINED';
        $this->_default = $values;

        parent::__construct($value);
    }

}
