<?php
/**
 * Created by PhpStorm.
 * User: gaelph
 * Date: 15/05/2017
 * Time: 09:41
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Monolog\Logger;

/**
 * Class TestCase
 * @package AppBundle\Entity
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class TestCase implements \jsonSerializable
{
    /**
     * @var
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue
     */
	private $id;

    /**
     * @var
     *
     * @ORM\Column(name="nom", type="text")
     */
	private $nom;

    /**
     * @var
     *
     * @ORM\Column(name="description", type="text")
     */
	private $description;

    /**
     * @var
     *
     * @ORM\Column(name="numero", type="integer")
     */
	private $numero;

    /**
     * @var
     *
     * @ORM\Column(name="date", type="integer")
     */
	private $date;

    /**
     * @var
     *
     * @ORM\Column(name="modifications", type="text", nullable=true)
     */
	private $modifications;

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

//        $modifications = $this->getModifications();
//        $modifications["id"] = time();
//        $this->setModifications($modifications);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom) {
        $this->nom = $nom;

//        $modifications = $this->getModifications();
//        $modifications["nom"] = time();
//        $this->setModifications($modifications);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description) {
        $this->description = $description;

//        $modifications = $this->getModifications();
//        $modifications["description"] = time();
//        $this->setModifications($modifications);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumero() {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero) {
        $this->numero = $numero;

//        $modifications = $this->getModifications();
//        $modifications["numero"] = time();
//        $this->setModifications($modifications);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date) {
//        if (!preg_match('/^[0-9]*$/', $date)) {
//            $date = strtotime($date);
//        }
        $this->date = strtotime($date);

//        $modifications = $this->getModifications();
//        $modifications["date"] = time();
//        $this->setModifications($modifications);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModifications()
    {
        $ret = json_decode($this->modifications, true);
        if (!is_array($ret)) {
            $ret = [];
        }
        return $ret;
    }

    /**
     * @param mixed $modifications
     */
    public function setModifications($modifications)
    {
        $this->modifications = json_encode($modifications);
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}