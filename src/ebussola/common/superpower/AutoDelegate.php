<?php

namespace ebussola\common\superpower;

/**
 * User: Leonardo Shinagawa
 * Date: 12/03/13
 * Time: 00:00
 */
trait AutoDelegate {
    /*
     * Using together with the interface Delegatable, the AutoDelegate can be very helpful to delegate
     * large number of methods to another object.
     */

    public function __call($name, array $args=array()) {
        if (!$this instanceof \ebussola\common\capacity\Delegatable) {
            throw new \BadMethodCallException('The class using AutoDelegate must implements Delegatable');
        }

        $deletageSchema = $this->_delegateSchema();
        if (isset($deletageSchema[$name])) {
            return call_user_func_array(array($deletageSchema[$name], $name), $args);
        } else {
            if (!isset($deletageSchema['::'])) {
                throw new \BadMethodCallException('At least the item \"::\" must be defined on delegateSchema');
            }
            return call_user_func(array($deletageSchema['::'], $name), $args);
        }
    }

}
