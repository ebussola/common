<?php

namespace ebussola\common\datatype;

use ebussola\common\exception\InvalidUrl;
use ebussola\common\capacity\Validatable;

/**
 * User: Leonardo Shinagawa
 * Date: 15/08/12
 * Time: 20:05
 */
class Url implements Validatable {

    /**
     * @var String
     */
    private $fullAddress;

    /**
     * @var String
     */
    private $scheme;

    /**
     * @var String
     */
    private $host;

    /**
     * @var String
     */
    private $port;

    /**
     * @var String
     */
    private $user;

    /**
     * @var String
     */
    private $pass;

    /**
     * @var String
     */
    private $path;

    /**
     * @var String
     */
    private $fragment;

    /**
     * @var Array
     */
    private $query;

    /**
     * @param String $address
     */
    public function __construct($address=null) {
        if ($address != null) {
            $this->setAddress($address);
        }
    }

    /**
     * @param String $address
     */
    public function setAddress($address) {
        $this->fullAddress = filter_var($address, FILTER_SANITIZE_URL);
        $info = parse_url($this->fullAddress);

        $this->scheme = $info['scheme'];
        $this->host = $info['host'];
        $this->port = $info['port'];
        $this->user = $info['user'];
        $this->pass = $info['pass'];
        $this->path = $info['path'];
        $queries = array();
        parse_str($info['query'], $queries);
        $this->query = $queries;
        $this->fragment = $info['fragment'];
    }

    /**
     * @return String
     */
    public function getAddress() {
        return $this->fullAddress;
    }

    /**
     * @return Array
     */
    public function getArrayQuery() {
        return $this->query;
    }

    /**
     * Assemble the fullAddress from the other properties
     */
    private function assembleUrl() {
        $address = '';
        if (!empty($this->scheme)) {
            $address .= $this->scheme . '://';
        }
        if (!empty($this->user)) {
            $address .= $this->user;
        }
        if (!empty($this->pass)) {
            $address .= ':' . $this->pass . '@';
        }
        if (!empty($this->host)) {
            $address .= $this->host;
        }
        if (!empty($this->port)) {
            $address .= ':' . $this->port;
        }
        if (!empty($this->path)) {
            $address .= $this->path;
        }
        if (count($this->query) > 0) {
            $address .= '?' . $this->getQuery();
        }
        if (!empty($this->fragment)) {
            $address .= '#' . $this->fragment;
        }

        $this->fullAddress = $address;
    }

    /**
     * @param bool $throwException
     * @return boolean
     * Returns True on success or False on fail
     * If the flag $throwException is true, an \InvalidArgumentException will be throwed on fail
     */
    public function isValid($throwException = false) {
        if (empty($this->fullAddress)) {
            if ($throwException) {
                throw new InvalidUrl('Url wasn\'t defined');
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $scheme
     * @return Url
     */
    public function setScheme($scheme) {
        $this->scheme = $scheme;
        $this->assembleUrl();
        return $this;
    }

    /**
     * @param String $host
     * @return Url
     */
    public function setHost($host) {
        $this->host = $host;
        $this->assembleUrl();
        return $this;
    }

    /**
     * @param String $port
     * @return Url
     */
    public function setPort($port) {
        $this->port = $port;
        $this->assembleUrl();
        return $this;
    }

    /**
     * @param String$user
     * @return Url
     */
    public function setUser($user) {
        $this->user = $user;
        $this->assembleUrl();
        return $this;
    }

    /**
     * @param String $pass
     * @return Url
     */
    public function setPass($pass) {
        $this->pass = $pass;
        $this->assembleUrl();
        return $this;
    }

    /**
     * @param String $path
     * @return Url
     */
    public function setPath($path) {
        $this->path = $path;
        $this->assembleUrl();
        return $this;
    }

    /**
     * @param Array $query
     * @return Url
     */
    public function setQuery(Array $query) {
        $this->query = $query;
        $this->assembleUrl();
        return $this;
    }

    /**
     * @param String $fragment
     * @return Url
     */
    public function setFragment($fragment) {
        $this->fragment = $fragment;
        $this->assembleUrl();
        return $this;
    }

    /**
     * @return Array
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getQueryString() {
        return http_build_query($this->query);
    }

    /**
     * @return String
     */
    public function getScheme() {
        return $this->scheme;
    }

    /**
     * @return String
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @return String
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @return String
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @return String
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @return String
     */
    public function getFragment() {
        return $this->fragment;
    }

}
