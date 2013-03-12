<?php

namespace ebussola\common\capacity;

/**
 * User: Leonardo Shinagawa
 * Date: 05/03/13
 * Time: 22:21
 */
interface Delegatable {

    /**
     * Must return an array with key value.
     * Where key is the method called
     * And the value is the object to be delegated
     *
     * @return array
     */
    public function _delegateSchema();

}