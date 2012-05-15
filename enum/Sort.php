<?php

namespace Shina\Common\Enum;

use Shina\Common\Datatype\Enum;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 24/04/12
 * Time: 10:36
 */
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

}
