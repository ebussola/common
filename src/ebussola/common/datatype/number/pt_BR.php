<?php

namespace ebussola\common\datatype\number;

/**
 * User: Leonardo Shinagawa
 * Date: 14/08/12
 * Time: 22:57
 */
class pt_BR implements Locale
{

    /**
     * @return String
     */
    public function getDecPoint()
    {
        return ',';
    }

    /**
     * @return String
     */
    public function getThousandPoint()
    {
        return '.';
    }

}
