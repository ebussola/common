<?php

namespace Shina\Common\Datatype\DateTime;

use Shina\Common\Datatype\Date;
use Shina\Common\Datatype\Currency;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 12/04/12
 * Time: 17:32
 */
class Storage implements \Iterator, \Countable, \ArrayAccess
{

    /**
     * @var [Date, Mixed][]
     */
    private $storage = array();

    /**
     * @var int
     */
    private $pointer = 0;

    /**
     * @var bool
     */
    private $isOrdered = false;

    /**
     * @param \Shina\Common\Datatype\Date $date
     * @param mixed $data
     */
    public function add(Date $date, $data)
    {
        $this->offsetSet($date, $data);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        if (!$this->isOrdered)
        {
            usort($this->storage, array($this, 'compare'));
            $this->isOrdered = true;
        }

        list($date, $data) = $this->storage[$this->pointer];

        return $data;
    }

    private function compare($value1, $value2)
    {
        list($date1, $data1) = $value1;
        list($date2, $data2) = $value2;

        if ($date1 == $date2)
        {
            return 0;
        }

        return ($date1 > $date2) ? 1 : -1;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->pointer++;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return scalar scalar on success, integer
     * 0 on failure.
     */
    public function key()
    {
        return $this->storage[$this->pointer][0]->format('Y-m-d');
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->storage[$this->pointer]);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->pointer = 0;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->storage);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean Returns true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        if (!$offset instanceof Date)
        {
            $offset = new Date($offset);
        }

        $item = $this->searchOffset($offset);
        return is_null($item) ? false : true;
    }

    private function searchOffset($offset)
    {
        foreach ($this->storage as $index => $item)
        {
            list($date, $data) = array_values($item);
            if ($date == $offset)
            {
                return $index;
            }
        }

        return null;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        if (!$offset instanceof Date)
        {
            $offset = new Date($offset);
        }

        list($date, $value) = $this->storage[$this->searchOffset($offset)];
        return $value;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (!$offset instanceof Date)
        {
            $offset = new Date($offset);
        }

        $index = $this->searchOffset($offset);
        if (is_null($index))
        {
            $this->storage[] = array($offset, $value);
        }
        else
        {
            $this->storage[$index] = array($offset, $value);
        }

        $this->isOrdered = false;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        if (!$offset instanceof Date)
        {
            $offset = new Date($offset);
        }

        $index = $this->searchOffset($offset);
        unset($this->storage[$index]);
    }

}