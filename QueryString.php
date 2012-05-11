<?php

namespace Shina\Common;

/**
 * Query String Management Class
 *
 * This class turns your HTTP query string into an object with dynamic properties
 * allowing you to get, set, and unset key value pairs in your query string for printing
 * within link tags or server redirects.
 *
 * To retrieve an HTML version of the generated query string, simply print your instantiated variable.
 * To retrieve a non-HTML entity version of the query string, use the url() method.
 *
 * NOTE: This class does NOT modify the $_GET array in any way, shape, or form
 *
 * Please see the example.php file for more information
 *
 * @author Kenaniah Cerny
 * @version 1.0
 * @license http://creativecommons.org/licenses/BSD/ BSD License
 * @copyright Kenaniah Cerny, 2008
 *
 * modified by Leonardo Branco Shinagawa, 2011
 * <leonado at ebussola dot com>
 */
class QueryString
{

    private $_vars = array();

    public function __construct($initial_array = NULL)
    {
        //Populate using the initial array, or import from $_GET by default
        if (isset($initial_array)) {
            $this->_vars = (array)$initial_array;
        } else {
            $this->_vars = $_GET;
        }

        //PhpGear uses $_GET['q']
        unset($this->q);
    }

    /**
     * Loads data into the object using an array
     */
    public function set_array($array)
    {
        $this->__construct($array);
    }

    /**
     * Retrieves data from the object in array format
     */
    public function get_array()
    {
        return $this->_vars;
    }

    /**
     * Prints a version of the query string that can be used for HTTP redirects
     */
    public function get($url_encoded = true)
    {
        if (!count($this->_vars)) return "";

        $output = '';
        foreach ($this->_vars as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $k => $v) {
                    if (is_array($v)) {
                        foreach ($val as $k2 => $v2) {
                            if (is_array($v2)) {
                                foreach ($v2 as $k3 => $v3) {
                                    $output = $this->parseValue($output, "{$key}[{$k}][{$k2}][{$v3}]", $v3);
                                }
                            }
                            else
                                $output = $this->parseValue($output, "{$key}[{$k}][{$k2}]", $v2);
                        }
                    }
                    else
                        $output = $this->parseValue($output, "{$key}[{$k}]", $v);
                }
            }
            else
                $output = $this->parseValue($output, $key, $val);
        }

        return $output;
    }

    protected function parseValue($output, $key, $val)
    {
        if (is_bool($val))
            $val = $val ? "true" : "false";

        if (empty($output))
            $output = "?";
        else {
            $output .= "&";
        }

        $output .= urlencode($key) . "=" . urlencode($val);

        return $output;
    }

    public function url($vars = array(), $value = '')
    {
        if (is_array($vars)) {
            foreach ($vars as $var => $value)
                $this->{$var} = $value;
        }
        else
            $this->{$vars} = $value;

        $op = $this->get();

        if (is_array($vars)) {
            foreach ($vars as $var => $value)
                unset($this->{$var});
        }
        else
            unset($this->{$vars});

        return $op;
    }

    public function __get($key)
    {
        return $this->_vars[$key];
    }


    public function __set($key, $val)
    {
        $this->_vars[$key] = $val;
    }


    public function __isset($key)
    {
        return isset($this->_vars[$key]);
    }


    public function __unset($key)
    {
        unset($this->_vars[$key]);
    }

    /**
     * Converts the object into a query string based off the object's properties
     */
    public function __toString()
    {
        return $this->get();
    }

}