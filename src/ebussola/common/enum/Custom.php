<?php

namespace ebussola\common\enum;

use ebussola\common\datatype\Enum;


final class Custom extends Enum {

    /**
     * @var array
     */
    private $_default;

    public function __construct(array $values, $value='UNDEFINED') {
        $this->_default = $values;

        parent::__construct($value);
    }

    /**
     * @return array
     * Return a list of available options.
     */
    public function defaults() {
        return $this->_default;
    }

    /**
     * @return String
     * Return the name identifying the Enum class
     */
    public function enumId() {
        return __CLASS__;
    }

}
