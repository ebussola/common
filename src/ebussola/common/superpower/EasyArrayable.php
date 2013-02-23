<?php

namespace ebussola\common\superpower;

/**
 * User: Leonardo Shinagawa
 * Date: 23/02/13
 * Time: 16:29
 */
trait EasyArrayable {
    /**
     * TODO explicar como funciona
     */

    /**
     * @return array
     */
    public function toArray() {
        $inflector = new \ebussola\akelos\Inflector();
        $result = array();
        foreach (get_class_methods($this) as $methodName) {
            if (substr($methodName, 0, 3) === 'get') {
                $value = $this->$methodName();
                if ($value instanceof \ebussola\common\datatype\Enum) {
                    $value = $value->getIndex();
                }
                $indexName = $inflector->underscore(substr($methodName, 3));
                $result[$indexName] = $value;
            }
        }

        return $result;
    }

    /**
     * @param array $data
     */
    public function fromArray(Array $values) {
        $inflector = new \ebussola\akelos\Inflector();
        foreach ($values as $key => $value) {
            if (is_array($value)) {
                $methodName = 'add' . $inflector->camelize($key);
                foreach ($value as $v) {
                    $this->$methodName($v);
                }
            } else {
                $methodName = 'set' . $inflector->camelize($key);
                if (method_exists($this, $methodName)) {
                    $this->$methodName($value);
                }
            }
        }
    }

}