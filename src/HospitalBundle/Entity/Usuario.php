<?php

namespace HospitalBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use HospitalBundle\Entity\Rol;
use Doctrine\ORM\Mapping as ORM;

/**
 * Usuario
 *
 * @ORM\Entity(repositoryClass="HospitalBundle\Repository\UsuarioRepository")
 * @ORM\Table(name="user")
 * 
 *  
 */
class Usuario extends BaseUser
{
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=false)
     */
    private $lastName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="borrado", type="boolean", nullable=true)
     */
    private $borrado = false;

    /**
     * @var string
     *
     * @ORM\Column(name="updated_at", type="datetime", length=255, nullable=true)
     */
    private $updated_at;

    /**
     * @var datetime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $created_at;
    
    public function getId()
    {
        return $this->id;
    }        

    public function getFirstname()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function isBorrado()
    {
        return $this->borrado;
    }        

    public function setFirstname($first)
    {
        $this->firstName = $first;
    }

    public function setLastName($last)
    {
        $this->lastName = $last;
    }

    public function setBorrado($borrado)
    {
        $this->borrado = $borrado;
    }

    public function setCreatedAt($date)
    {
        $this->created_at = $date;
    }

    /*public function addGroup(Group $rol)
    {
        if (!$this->groups->contains($rol)) {
            $this->groups->add($rol);
        }
    }

    public function removeGroup(Group $rol)
    {
        if ($this->groups->contains($rol)) {
            $this->groups->remove($rol);
        }
    }*/

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        //$this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get borrado
     *
     * @return boolean
     */
    public function getBorrado()
    {
        return $this->borrado;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Usuario
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }
}
