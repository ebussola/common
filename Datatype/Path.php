<?php

namespace Shina\Common\Datatype;

/**
 * Author: Leonardo Branco Shinagawa
 * Date: 28/12/11
 * Time: 10:16
 */
class Path
{

    /**
     * @var Path\Type
     */
    public $type;
    /**
     * @var String
     */
    public $dirname;
    /**
     * @var String
     */
    public $basename;
    /**
     * @var String
     */
    public $extension;
    /**
     * @var String
     */
    public $filename;
    /**
     * @var boolean
     */
    public $exists;

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
        $full_path = (string) $full_path;
        if (file_exists($full_path))
        {
            $info = pathinfo($full_path);
            if (is_dir($full_path))
            {
                $this->setType(new Path\Type('DIR'));
                $this->setDirname($info['dirname']);
                $this->setBasename($info['basename']);
                $this->setFilename($info['filename']);
            }
            else
            {
                $this->setType(new Path\Type('FILE'));
                $this->setDirname($info['dirname']);
                $this->setBasename($info['basename']);
                if (isset($info['extension']))
                {
                    $this->setExtension($info['extension']);
                }
                $this->setFilename($info['filename']);
            }
            $this->exists = true;
        }
        else
        {
            $this->setFilename($full_path);
            $this->exists = false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getFullpath()
    {
        if ($this->exists)
        {
            $dir_name = $this->getDirname() !== '/' ? $this->getDirname() : '';
            return $dir_name . DIRECTORY_SEPARATOR . $this->getBasename();
        }
        else
        {
            return $this->getFilename();
        }
    }

    /**
     * @param \String $basename
     */
    public function setBasename($basename)
    {
        $this->basename = $basename;
    }

    /**
     * @return \String
     */
    public function getBasename()
    {
        if (!$this->exists)
        {
            $this->setFullpath($this->getFullpath());
        }

        return $this->basename;
    }

    /**
     * @param \String $dirname
     */
    public function setDirname($dirname)
    {
        $this->dirname = $dirname;
    }

    /**
     * @return \String
     */
    public function getDirname()
    {
        if (!$this->exists)
        {
            $this->setFullpath($this->getFullpath());
        }

        return $this->dirname;
    }

    /**
     * @param \String $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * @return \String
     */
    public function getExtension()
    {
        if (!$this->exists)
        {
            $this->setFullpath($this->getFullpath());
        }

        return $this->extension;
    }

    /**
     * @param \String $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return \String
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param Path\Type $type
     */
    public function setType(Path\Type $type)
    {
        $this->type = $type;
    }

    /**
     * @return Path\Type
     */
    public function getType()
    {
        if (!$this->exists)
        {
            $this->setFullpath($this->getFullpath());
        }

        return $this->type;
    }

    /**
     * @return string
     */
    public function getRealpath()
    {
        return realpath($this->getFullpath());
    }

    /**
     * @return bool
     */
    public function isDir()
    {
        return $this->getType() == 'DIR';
    }

    /**
     * @return bool
     */
    public function isFile()
    {
        return $this->getType() == 'FILE';
    }

    /**
     * @return Path
     */
    public function refresh()
    {
        return new self($this);
    }

}
