<?php

namespace ebussola\common\capacity;

/**
 * User: Leonardo Shinagawa
 * Date: 14/08/12
 * Time: 11:36
 */
interface Validatable {

    /**
     * @abstract
     * @param bool $throwException
     * @return boolean
     * Returns True on success or False on fail
     * If the flag $throwException is true, an \InvalidArgumentException will be throwed on fail
     */
    public function isValid($throwException = false);

}
