<?php

namespace ebussola\common\datatype\path;

use ebussola\common\datatype\Enum;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 28/12/11
 * Time: 10:15
 */
class Type extends Enum {

    /**
     * @return array
     * Return a list of available options.
     */
    public function defaults() {
        return array(
            1 => 'DIR',
            2 => 'FILE'
        );
    }

    /**
     * @return String
     * Return the name identifying the Enum class
     */
    public function enumId() {
        return __CLASS__;
    }

}
