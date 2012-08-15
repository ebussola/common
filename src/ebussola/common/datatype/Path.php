<?php

namespace ebussola\common\datatype;

use ebussola\common\capacity\Arrayable;
use ebussola\common\exception\InvalidPath;
use ebussola\common\capacity\Validateable;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 28/12/11
 * Time: 10:16
 */
class Path implements Arrayable, Validateable
{

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
    private $full_path;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->setFullpath($path);
    }

    public function __toString()
    {
        return $this->getFullpath();
    }

    /**
     * @param string $full_path
     * @return null
     */
    public function setFullpath($full_path)
    {
        $this->full_path = strval($full_path);
        if (file_exists($this->full_path)) {
            $info = pathinfo($this->full_path);
            if (is_dir($this->full_path)) {
                $this->type = new path\Type('DIR');
                $this->dirname = $info['dirname'];
                $this->basename = $info['basename'];
                $this->filename = $info['filename'];
            } else {
                $this->type = new path\Type('FILE');
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
    public function getFullpath()
    {
        return $this->full_path;
    }

    /**
     * @return string
     */
    public function getRealpath()
    {
        return $this->exists ? realpath($this->full_path) : '';
    }

    /**
     * @return \String
     */
    public function getBasename()
    {
        return $this->basename;
    }

    /**
     * @return \String
     */
    public function getDirname()
    {
        return $this->dirname;
    }

    /**
     * @return \String
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @return \String
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return path\Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isDir()
    {
        return $this->getType() == new path\Type('DIR');
    }

    /**
     * @return bool
     */
    public function isFile()
    {
        return $this->getType() == new path\Type('FILE');
    }

    /**
     * @param \ebussola\common\datatype\path\Type $type
     */
    public function setType(path\Type $type)
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'type' => $this->type->get(),
            'dirname' => $this->dirname,
            'basename' => $this->basename,
            'extension' => $this->extension,
            'filename' => $this->filename,
            'exists' => $this->exists,
            'full_path' => $this->full_path
        );
    }

    /**
     * @param array $values
     */
    public function fromArray(array $values)
    {
        $defaults = array(
            'type' => new path\Type(null),
            'dirname' => null,
            'basename' => null,
            'extension' => null,
            'filename' => null,
            'exists' => false,
            'full_path' => null
        );
        $values = array_merge($defaults, $values);

        $this->type = $values['type'];
        $this->dirname = $values['dirname'];
        $this->basename = $values['basename'];
        $this->extension = $values['extension'];
        $this->filename = $values['filename'];
        $this->exists = $values['exists'];
        $this->full_path = $values['full_path'];
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
        } catch (InvalidPath $e) {
            return false;
        }
        return true;
    }

    /**
     * Exceptions must be documented with <at>throws
     * @return boolean
     * Returns True if validation pass or throw an Exception on fail
     */
    public function validate()
    {
        $e = new InvalidPath();
        $e->setClassName(__CLASS__);

        if (!$this->type->isValid()) {
            $e->addAttributeName('type');
        }
        if (empty($this->full_path)) {
            $e->addAttributeName('full_path');
        }
        if (!is_bool($this->exists)) {
            $e->addAttributeName('exists');
        }

        if (count($e->getAttributeNames())) {
            throw $e;
        }

        return true;
    }

}
