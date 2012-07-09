<?php

namespace Shina\Common\Datatype;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 10/02/12
 * Time: 11:58
 */
class DateTime extends \DateTime
{

    protected $format = 'd/m/Y h:i:s';

    public function __construct($time = 'now')
    {
        if ($time instanceof \DateTime)
        {
            $time = $time->format('c');
        }

        parent::__construct($time);
    }

    public function __toString()
    {
        return $this->format($this->format);
    }
    
    public function format($format)
    {
        $str = parent::format($format);
        return str_replace(array(
    		'January', 'Jan',
			'February', 'Feb',
			'March', 'Mar',
			'April', 'Apr',
			'May', 'May',
			'June', 'Jun',
			'July', 'Jul',
			'August', 'Aug',
			'September', 'Sep',
			'October', 'Oct',
			'November', 'Nov',
			'December', 'Dec',

            'Saturday', 'Sat',
            'Sunday', 'Sun',
            'Monday', 'Mon',
            'Tuesday', 'Tue',
            'Wednesday', 'Wed',
            'Thursday', 'Thu',
            'Friday', 'Fri'
		), array(
			'Janeiro', 'Jan',
			'Fevereiro', 'Fev',
			'Março', 'Mar',
			'Abril', 'Abr',
			'Maio', 'Mai',
			'Junho', 'Jun',
			'Julho', 'Jul',
			'Agosto', 'Ago',
			'Setembro', 'Set',
			'Outubro', 'Out',
			'Novembro', 'Nov',
			'Dezembro', 'Dez',

            'Sábado', 'Sáb',
            'Domingo', 'Dom',
            'Segunda-feira', 'Seg',
            'Terça-feira', 'Ter',
            'Quarta-feira', 'Qua',
            'Quinta-feira', 'Qui',
            'Sexta-feira', 'Sex'
		), $str);
    }

}