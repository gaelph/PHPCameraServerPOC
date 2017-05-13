<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User
{
    /**
     * @var {string}
     *
     * @ORM\Column(name="username", type="text")
     * @ORM\Id
     */
    private $username;

    /**
     * @var {string}
     *
     * @ORM\Column(name="password", type="text")
     */
    private $password;

    /**
     * @var {integer}
     *
     * @ORM\Column(name="admin", type="integer")
     */
    private $admin;

    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getAdmin() {
        return $this->admin;
    }

    /**
     * @param mixed $admin
     */
    public function setAdmin($admin) {
        $this->admin = $admin;
    }


}