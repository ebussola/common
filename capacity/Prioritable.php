<?php

namespace Shina\Common\Capacity;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 23/03/12
 * Time: 14:36
 */
interface Prioritable
{

    /**
     * @abstract
     * @return integer
     */
    public function getPriority();

}
