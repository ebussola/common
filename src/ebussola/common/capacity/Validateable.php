<?php

namespace ebussola\common\capacity;

/**
 * User: Leonardo Shinagawa
 * Date: 14/08/12
 * Time: 11:36
 */
interface Validateable
{

    /**
     * Simple validation check
     * @abstract
     * @return boolean
     * Returns True on success or False on fail
     */
    public function isValid();

    /**
     * Exceptions must be documented with <at>throws
     * @abstract
     * @return boolean
     * Returns True if validation pass or throw an Exception on fail
     */
    public function validate();

}
