<?php

namespace ebussola\common\capacity;

/**
 * User: Leonardo Shinagawa
 * Date: 14/08/12
 * Time: 11:16
 */
interface Arrayable {

    /**
     * @abstract
     * @return array
     */
    public function toArray();

    /**
     * @abstract
     * @param array $values
     */
    public function fromArray(array $values);

}
