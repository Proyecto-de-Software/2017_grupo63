<?php

namespace HospitalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DatosDemograficos
 *
 * @ORM\Table(name="datos_demograficos")
 * @ORM\Entity(repositoryClass="HospitalBundle\Repository\DatosDemograficosRepository")
 */
class DatosDemograficos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="heladera", type="boolean", nullable=false)
     */
    private $heladera;

    /**
     * @var integer
     *
     * @ORM\Column(name="electricidad", type="boolean", nullable=false)
     */
    private $electricidad;

    /**
     * @var integer
     *
     * @ORM\Column(name="mascota", type="boolean", nullable=false)
     */
    private $mascota;

    /**
     * @var integer
     *
     * @ORM\Column(name="tipo_vivienda_id", type="integer", nullable=false)
     */
    private $tipoViviendaId;

    /**
     * @var integer
     *
     * @ORM\Column(name="tipo_calefaccion_id", type="integer", nullable=false)
     */
    private $tipoCalefaccionId;

    /**
     * @var integer
     *
     * @ORM\Column(name="tipo_agua_id", type="integer", nullable=false)
     */
    private $tipoAguaId;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get heladera
     *
     * @return integer 
     */
    public function getHeladera()
    {
        return $this->heladera;
    }

    public function SetHeladera($heladera)
    {
           $this->heladera = $heladera;
    }

    /**
     * Get electricidad
     *
     * @return integer 
     */
    public function getElectricidad()
    {
        return $this->electricidad;
    }

    public function SetElectricidad($electricidad)
    {
           $this->electricidad = $electricidad;
    }

    /**
     * Get mascota
     *
     * @return integer 
     */
    public function getMascota()
    {
        return $this->mascota;
    }    

    public function SetMascota($mascota)
    {
           $this->mascota = $mascota;
    }

    /**
     * Get tipoViviendaId
     *
     * @return integer 
     */
    public function getTipoViviendaId()
    {
        return $this->tipoViviendaId;
    }

    public function SetTipoViviendaId($tipoViviendaId)
    {
           $this->tipoViviendaId = $tipoViviendaId;
    }

    /**
     * Get tipoCalefaccionId
     *
     * @return integer 
     */
    public function getTipoCalefaccionId()
    {
        return $this->tipoCalefaccionId;
    }

    public function SetTipoCalefaccionId($tipoCalefaccionId)
    {
           $this->tipoCalefaccionId = $tipoCalefaccionId;
    }

    /**
     * Get tipoAguaId
     *
     * @return integer 
     */
    public function getTipoAguaId()
    {
        return $this->tipoAguaId;
    }

    public function SetTipoAguaId($tipoAguaId)
    {
           $this->tipoAguaId = $tipoAguaId;
    }
}
