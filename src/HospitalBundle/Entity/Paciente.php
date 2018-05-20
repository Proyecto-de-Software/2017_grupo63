<?php

namespace HospitalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HospitalBundle\Entity\DatosDemograficos;
use HospitalBundle\Entity\Historia;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Paciente
 *
 * @ORM\Table(name="paciente", indexes={@ORM\Index(name="id", columns={"id"}), @ORM\Index(name="datos_demograficos_id", columns={"datos_demograficos_id"})})
 * 
 * @ORM\Entity(repositoryClass="HospitalBundle\Repository\PacienteRepository")
 */
class Paciente
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
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=256, nullable=false)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=256, nullable=false)
     */
    private $nombre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="nacimiento", type="date", nullable=false)
     */
    private $nacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="genero", type="string", length=30, nullable=false)
     */
    private $genero;

    /**
     * @var string
     *
     * @ORM\Column(name="tipoDoc", type="string", length=256, nullable=false)
     */
    private $tipoDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="numDoc", type="string", length=20, nullable=false)
     */
    private $numDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="domicilio", type="string", length=256, nullable=false)
     */
    private $domicilio;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=256, nullable=false)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="obraSocial", type="string", length=256, nullable=false)
     */
    private $obraSocial;

    /**
     * @var boolean
     *
     * @ORM\Column(name="borrado", type="boolean", nullable=false)
     */
    private $borrado = true;

    /**
     * @ORM\OneToOne(targetEntity="DatosDemograficos", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="datos_demograficos_id", referencedColumnName="id", nullable=true, onDelete="cascade")
     */
    private $datosDemograficos;

     /**
     * @ORM\OneToMany(targetEntity="Historia", mappedBy="paciente")
     */
    private $historias;

    public function __construct()
    {
        $this->historias = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function getNacimiento()
    {
        return $this->nacimiento;
    }

    public function getGenero()
    {
        return $this->genero;
    }

    public function getTipoDoc()
    {
        return $this->tipoDoc;
    }

    public function getNumDoc()
    {
        return $this->numDoc;
    }

    public function getDomicilio()
    {
        return $this->domicilio;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function getObraSocial()
    {
        return $this->obraSocial;
    }

    public function isBorrado()
    {
        return $this->borrado;
    }

    public function getDatosDemograficos()
    {
        return $this->datosDemograficos;
    }

    public function getHistorias()
    {
        return $this->historias;
    }
    public function addHistoria(Historia $historia)
    {
        if (!$this->historias->contains($historia)) {
            $this->historia->add($historia);
        }
    }

    public function removeHistoria(Rol $historia)
    {
        if ($this->historias->contains($historia)) {
            $this->historias->remove($historia);
        }
    }

    public function SetNombre($nombre)
    {
           $this->nombre = $nombre;
    }

    public function SetApellido($apellido)
    {
           $this->apellido = $apellido;
    }

    public function SetNacimiento($nacimiento)
    {
           $this->nacimiento = $nacimiento;
    }

    public function SetGenero($genero)
    {
           $this->genero = $genero;
    }

    public function SetTipoDoc($tipoDoc)
    {
           $this->tipoDoc = $tipoDoc;
    }

    public function SetNumDoc($numDoc)
    {
           $this->numDoc = $numDoc;
    }

    public function SetDomicilio($domicilio)
    {
           $this->domicilio = $domicilio;
    }

    public function SetTelefono($telefono)
    {
           $this->telefono = $telefono;
    }

    public function SetObraSocial($obraSocial)
    {
           $this->obraSocial = $obraSocial;
    }

    public function SetDatosDemograficos(DatosDemograficos $dm)
    {
           $this->datosDemograficos = $dm;
    }   

    /**
     * Set borrado
     *
     * @param boolean $borrado
     *
     * @return Paciente
     */
    public function setBorrado($borrado)
    {
        $this->borrado = $borrado;

        return $this;
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
}
