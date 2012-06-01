<?php

namespace Shina\Common\Datatype\Path;

use Shina\Common\Datatype\Enum;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 28/12/11
 * Time: 10:15
 */
class Type extends Enum
{

    /**
     * @return array
     * Return a list of available options.
     */
    public function defaults()
    {
        return array('DIR', 'FILE');
    }


}
