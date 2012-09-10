<?php

namespace ebussola\common\datatype;

use ebussola\common\exception\InvalidPath;
use ebussola\common\capacity\Validatable;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 28/12/11
 * Time: 10:16
 */
class Path implements Validatable {

    /**
     * @var path\Type
     */
    private $type;

    /**
     * @var String
     */
    private $dirname;

    /**
     * @var String
     */
    private $basename;

    /**
     * @var String
     */
    private $extension;

    /**
     * @var String
     */
    private $filename;

    /**
     * @var boolean
     */
    private $exists;

    /**
     * @var String
     */
    private $fullpath;

    /**
     * @param string $path
     */
    public function __construct($path = null) {
        $this->type = new path\Type();
        $this->exists = false;
        if ($path != null) {
            $this->setFullpath($path);
        }
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->getFullpath();
    }

    /**
     * @param string $full_path
     * @return null
     */
    public function setFullpath($full_path) {
        $this->fullpath = strval($full_path);
        if (file_exists($this->fullpath)) {
            $info = pathinfo($this->fullpath);
            if (is_dir($this->fullpath)) {
                $this->type->set('DIR');
                $this->dirname = $info['dirname'];
                $this->basename = $info['basename'];
                $this->filename = $info['filename'];
            } else {
                $this->type->set('FILE');
                $this->dirname = $info['dirname'];
                $this->basename = $info['basename'];
                if (isset($info['extension'])) {
                    $this->extension = $info['extension'];
                }
                $this->filename = $info['filename'];
            }
            $this->exists = true;
        } else {
            $this->exists = false;
        }
    }

    /**
     * @return string
     */
    public function getFullpath() {
        return $this->fullpath;
    }

    /**
     * @return string
     */
    public function getRealpath() {
        return $this->exists ? realpath($this->fullpath) : '';
    }

    /**
     * @return \String
     */
    public function getBasename() {
        return $this->basename;
    }

    /**
     * @return \String
     */
    public function getDirname() {
        return $this->dirname;
    }

    /**
     * @return \String
     */
    public function getExtension() {
        return $this->extension;
    }

    /**
     * @return \String
     */
    public function getFilename() {
        return $this->filename;
    }

    /**
     * @return path\Type
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isDir() {
        return $this->getType() == 'DIR';
    }

    /**
     * @return bool
     */
    public function isFile() {
        return $this->getType() == 'FILE';
    }

    /**
     * @param bool $throwException
     * @return boolean
     * Returns True on success or False on fail
     * If the flag $throwException is true, an \InvalidArgumentException will be throwed on fail
     */
    public function isValid($throwException = false) {
        if ((!$this->type->isValid()) || (empty($this->fullpath))) {
            if ($throwException) {
                throw new InvalidPath('Invalid path, no path was defined.');
            } else {
                return false;
            }
        }
        return true;
    }

}
