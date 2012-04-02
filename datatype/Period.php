<?php

namespace Shina\Common\Datatype;

use Shina\Common\Datatype\Date;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 02/04/12
 * Time: 16:00
 */
class Period extends \DatePeriod
{

    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @var \DateInterval
     */
    private $interval;

    public function __construct(\DateTime $start, $interval, $end)
    {
        if (!$interval instanceof \DateInterval)
        {
            $interval = new \DateInterval("P{$interval}D");
        }

        if (!$end instanceof \DateTime)
        {
            $recurrences = $end;
            $end = clone $start;
            for ($i=0 ; $i<=$recurrences ; $i++)
            {
                $end = $end->add($interval);
            }
        }

        $this->start = $start;
        $this->end = $end;
        $this->interval = $interval;

        parent::__construct($start, $interval, $end);
    }

    /**
     * @return \DateTime
     */
    public function start()
    {
        return $this->start;
    }

    /**
     * @return \DateTime
     */
    public function end()
    {
        return $this->end;
    }

    /**
     * @return \DateInterval
     */
    public function interval()
    {
        return $this->interval;
    }

    /**
     * @return integer
     */
    public function count()
    {
        $i = 0;
        foreach ($this as $date)
        {
            $i++;
        }
        return $i;
    }

}