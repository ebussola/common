<?php

namespace ebussola\common\capacity;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 29/03/12
 * Time: 15:14
 */
interface Hideable {

    /**
     * @abstract
     * @return boolean
     */
    public function isVisible();

    /**
     * @abstract
     * @return self
     */
    public function hide();

    /**
     * @abstract
     * @return self
     */
    public function show();

}
