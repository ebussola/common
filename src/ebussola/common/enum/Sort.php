<?php

namespace Shina\Common\Enum;

use ebussola\common\datatype\Enum;


class Sort extends Enum
{

    /**
     * @return array
     * Return a list of options available.
     */
    public function defaults()
    {
        return array('ASC', 'DESC');
    }

    /**
     * @return String
     * Return the name identifying the Enum class
     */
    public function enumId()
    {
        return __CLASS__;
    }

}
