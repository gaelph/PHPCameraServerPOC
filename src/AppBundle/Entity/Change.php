<?php
/**
 * Created by PhpStorm.
 * User: gaelph
 * Date: 13/05/2017
 * Time: 17:14
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Change
 * @package AppBundle\Entity
 *
 * @ORM\Table()
 */
class Change
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="timestamp", type="integer")
     */
    private $timestamp;

    /**
     * @ORM\Column(name="table", type="text")
     */
    private $table;

    /**
     * @ORM\Column(name="field", type="text")
     */
    private $field;

    /**
     * @ORM\Column(name="rowid", type="integer")
     */
    private $rowid;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTimestamp() {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * @param mixed $table
     */
    public function setTable($table) {
        $this->table = $table;
    }

    /**
     * @return mixed
     */
    public function getField() {
        return $this->field;
    }

    /**
     * @param mixed $field
     */
    public function setField($field) {
        $this->field = $field;
    }

    /**
     * @return mixed
     */
    public function getRowid() {
        return $this->rowid;
    }

    /**
     * @param mixed $rowid
     */
    public function setRowid($rowid) {
        $this->rowid = $rowid;
    }


}

