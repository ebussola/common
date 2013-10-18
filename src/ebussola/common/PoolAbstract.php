<?php
/**
 * Created by JetBrains PhpStorm.
 * User: usuario
 * Date: 22/08/13
 * Time: 10:26
 */

namespace ebussola\common;


abstract class PoolAbstract {

    /**
     * @var array
     */
    private $objects;

    static private $instances = [];

    public function __construct() {
        $this->objects = [];
    }

    /**
     * @return PoolAbstract
     */
    static public function getInstance() {
        $class_name = get_class(new static());
        if (!isset(self::$instances[$class_name])) {
            self::$instances[$class_name] = new static();
        }

        return self::$instances[$class_name];
    }

    /**
     * @param $objects
     */
    public function addAll($objects) {
        foreach ($objects as $obj) {
            $this->add($obj);
        }
    }

    /**
     * @param $object
     */
    public function add($object) {
        $id = $this->makeId($object);
        if (!$this->has($id)) {
            $this->objects[$id] = $object;
        }
    }

    /**
     * @param int $object_id
     *
     * @return bool
     */
    public function has($object_id) {
        return isset($this->objects[$object_id]);
    }

    /**
     * @param array $object_ids
     * Ids to check the existence on the pool
     *
     * @return array
     * return the Ids that don't has on the pool
     */
    public function getNotHasIds(array $object_ids) {
        $not_exists = [];
        foreach ($object_ids as $id) {
            if (!$this->has($id)) {
                $not_exists[] = $id;
            }
        }

        return $not_exists;
    }

    /**
     * @param array $object_ids
     *
     * @return array
     * return only the existents objects
     */
    public function getAllExistents(array $object_ids) {
        $objects = [];
        foreach ($object_ids as $id) {
            if ($this->has($id)) {
                $objects[] = $this->get($id);
            }
        }

        return $objects;
    }

    /**
     * @param int $object_id
     */
    public function get($object_id) {
        return $this->objects[$object_id];
    }

    /**
     * @param $object_id
     */
    public function remove($object_id) {
        if ($this->has($object_id)) {
            unset($this->objects[$object_id]);
        }
    }

    /**
     * @param $object
     *
     * @return int | string
     */
    abstract protected function makeId($object);

}