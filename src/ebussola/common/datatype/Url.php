<?php

namespace ebussola\common\datatype;

use ebussola\common\capacity\Arrayable;
use ebussola\common\exception\InvalidUrl;
use ebussola\common\capacity\Validateable;

/**
 * User: Leonardo Shinagawa
 * Date: 15/08/12
 * Time: 20:05
 */
class Url implements Arrayable, Validateable
{

    /**
     * @var String
     */
    private $full_address, $scheme, $host, $port, $user, $pass, $path, $fragment;

    /**
     * @var Array
     */
    private $query;

    public function __construct($address)
    {
        $this->setAddress($address);
    }

    /**
     * @param String $address
     */
    public function setAddress($address)
    {
        $this->full_address = filter_var($address, FILTER_SANITIZE_URL);
        $info = parse_url($this->full_address);
        $this->fromArray($info);
    }

    /**
     * @return String
     */
    public function getAddress()
    {
        return $this->full_address;
    }

    /**
     * @return Array
     */
    public function getArrayQuery()
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'full_address' => $this->full_address,
            'scheme' => $this->scheme,
            'host' => $this->host,
            'port' => $this->port,
            'user' => $this->user,
            'pass' => $this->pass,
            'path' => $this->path,
            'query' => $this->getQuery(),
            'fragment' => $this->fragment
        );
    }

    /**
     * @param array $values
     */
    public function fromArray(array $values)
    {
        $defaults = array(
            'full_address' => null,
            'scheme' => null,
            'host' => null,
            'port' => null,
            'user' => null,
            'pass' => null,
            'path' => null,
            'query' => null,
            'fragment' => null
        );
        $values = array_merge($defaults, $values);

        $this->full_address = $values['full_address'];
        $this->scheme = $values['scheme'];
        $this->host = $values['host'];
        $this->port = $values['port'];
        $this->user = $values['user'];
        $this->pass = $values['pass'];
        $this->path = $values['path'];
        $queries = array();
        parse_str($values['query'], $queries);
        $this->query = $queries;
        $this->fragment = $values['fragment'];

        $this->assembleUrl();
    }

    private function assembleUrl()
    {
        $address = '';
        if (!empty($this->scheme)) {
            $address .= $this->scheme.'://';
        }
        if (!empty($this->user)) {
            $address .= $this->user;
        }
        if (!empty($this->pass)) {
            $address .= ':'.$this->pass.'@';
        }
        if (!empty($this->host)) {
            $address .= $this->host;
        }
        if (!empty($this->port)) {
            $address .= ':'.$this->port;
        }
        if (!empty($this->path)) {
            $address .= $this->path;
        }
        if (count($this->query) > 0) {
            $address .= '?'.$this->getQuery();
        }
        if (!empty($this->fragment)) {
            $address .= '#'.$this->fragment;
        }

        $this->full_address = $address;
    }

    /**
     * Simple validation check
     * @return boolean
     * Returns True on success or False on fail
     */
    public function isValid()
    {
        try {
            $this->validate();
        } catch (InvalidUrl $e) {
            return false;
        }
        return true;
    }

    /**
     * Exceptions must be documented with <at>throws
     * @return boolean
     * Returns True if validation pass or throw an Exception on fail
     * @throws InvalidUrl
     */
    public function validate()
    {
        $e = new InvalidUrl();
        $e->setClassName(__CLASS__);

        if (empty($this->full_address)) {
            $e->addAttributeName('full_address');
        }
        if (!is_array($this->query)) {
            $e->addAttributeName('query');
        }

        if (count($e->getAttributeNames())) {
            throw $e;
        }

        return true;
    }

    /**
     * @param $scheme
     * @return Url
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
        $this->assembleUrl();
        return $this;
    }

    /**
     * @param String $host
     * @return Url
     */
    public function setHost($host)
    {
        $this->host = $host;
        $this->assembleUrl();
        return $this;
    }

    /**
     * @param String $port
     * @return Url
     */
    public function setPort($port)
    {
        $this->port = $port;
        $this->assembleUrl();
        return $this;
    }

    /**
     * @param String$user
     * @return Url
     */
    public function setUser($user)
    {
        $this->user = $user;
        $this->assembleUrl();
        return $this;
    }

    /**
     * @param String $pass
     * @return Url
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
        $this->assembleUrl();
        return $this;
    }

    /**
     * @param String $path
     * @return Url
     */
    public function setPath($path)
    {
        $this->path = $path;
        $this->assembleUrl();
        return $this;
    }

    /**
     * @param String $query
     * @return Url
     */
    public function setQuery($query)
    {
        $this->query = $query;
        $this->assembleUrl();
        return $this;
    }

    /**
     * @param String $fragment
     * @return Url
     */
    public function setFragment($fragment)
    {
        $this->fragment = $fragment;
        $this->assembleUrl();
        return $this;
    }

    /**
     * @return Array
     */
    public function getQuery()
    {
        return http_build_query($this->query);
    }

    /**
     * @return String
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @return String
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return String
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return String
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return String
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return String
     */
    public function getFragment()
    {
        return $this->fragment;
    }

}
