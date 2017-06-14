<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Photo
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Photo
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="key", type="integer")
     */
    private $key;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="text")
     */
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="modifications", type="text", nullable=true)
     */
    private $modifications = "{}";

    /**
     * Set key
     *
     * @param int $key
     * @return Photo
     */
    public function setKey($key) {
        $this->key = $key;

        $modifications = $this->getModifications();
        $modifications["key"] = time();
        $this->setModifications($modifications);

        return $this;
    }


    /**
     * Get key
     *
     * @return integer
     */
    public function getKey()
    {
        return $this->key;
    }

    public function setId($id) {
        $this->setKey($id);
    }

    public function getId() {
        return $this->getKey();
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Photo
     */
    public function setValue($value)
    {
        $this->value = $value;

        $modifications = $this->getModifications();
        $modifications["value"] = time();
        $this->setModifications($modifications);

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return Photo
     */
    public function setUser($user)
    {
        $this->user = $user;

        $modifications = $this->getModifications();
        $modifications["user"] = time();
        $this->setModifications($modifications);

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getModifications()
    {
        return json_decode($this->modifications, true);
    }

    /**
     * @param string $modifications
     */
    public function setModifications($modifications)
    {
        $this->modifications = json_encode($modifications);
    }


}

