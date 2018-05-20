<?php

namespace HospitalBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rol
 *
 * @ORM\Table(name="rol")
 * @ORM\Entity
 */
class Rol extends BaseGroup
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
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    protected $nombre;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="HospitalBundle\Entity\Permiso", inversedBy="rol")
     * @ORM\JoinTable(name="rol_tiene_permiso",
     *   joinColumns={
     *     @ORM\JoinColumn(name="rol_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="permiso_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $permisos;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function getRole()
    {
        return $this->nombre;
    }   

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Rol
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permisos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getRoles()
    {
        return array($this->getNombre());
    }


    /**
     * Add permiso
     *
     * @param \HospitalBundle\Entity\Permiso $permiso
     *
     * @return Rol
     */
    public function addPermiso(\HospitalBundle\Entity\Permiso $permiso)
    {
        $this->permisos[] = $permiso;

        return $this;
    }

    /**
     * Remove permiso
     *
     * @param \HospitalBundle\Entity\Permiso $permiso
     */
    public function removePermiso(\HospitalBundle\Entity\Permiso $permiso)
    {
        $this->permisos->removeElement($permiso);
    }

    /**
     * Get permisos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermisos()
    {
        return $this->permisos;
    }
}
