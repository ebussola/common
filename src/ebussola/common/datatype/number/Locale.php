<?php

namespace ebussola\common\datatype\number;

interface Locale
{

    /**
     * @abstract
     * @return String
     */
    public function getDecPoint();

    /**
     * @abstract
     * @return String
     */
    public function getThousandPoint();

}
