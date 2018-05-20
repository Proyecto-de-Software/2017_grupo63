<?php

namespace HospitalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HospitalBundle\Entity\Paciente;

/**
 * Historia
 *
 * @ORM\Table(name="historia")
 * @ORM\Entity
 */
class Historia
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
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
    private $fecha;

    /**
     * @var integer
     *
     * @ORM\Column(name="peso", type="integer")
     */
    protected $peso;

    /**
     * @var boolean
     *
     * @ORM\Column(name="vacunas_completas", type="boolean")
     */
    protected $vacunas;

    /**
     * @var string
     *
     * @ORM\Column(name="vacunas_observacion", type="string", length=255, nullable=false)
     */
    private $vacunasOb;

    /**
     * @var boolean
     *
     * @ORM\Column(name="maduracion_acorde", type="boolean")
     */
    protected $maduracion;

    /**
     * @var string
     *
     * @ORM\Column(name="maduracion_observacion", type="string", length=255, nullable=false)
     */
    private $maduracionOb;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ex_fisico_normal", type="boolean", nullable=false)
     */
    protected $fisico;

    /**
     * @var string
     *
     * @ORM\Column(name="fisico_observacion", type="string", length=255, nullable=false)
     */
    private $fisicoOb;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="string", length=255, nullable=true)
     */
    private $observacion;

    /**
     * @var string
     *
     * @ORM\Column(name="alimentacion", type="string", length=255, nullable=true)
     */
    private $alimentacion;

    /**
     * @var integer
     *
     * @ORM\Column(name="pc", type="integer", nullable=true)
     */
    protected $pc;

    /**
     * @var integer
     *
     * @ORM\Column(name="ppc", type="integer", nullable=true)
     */
    protected $ppc;

    /**
     * @var integer
     *
     * @ORM\Column(name="talla", type="integer", nullable=true)
     */
    protected $talla;

    /**
     * @ORM\ManyToOne(targetEntity="Paciente", inversedBy="historias")
     * @ORM\JoinColumn(name="paciente_id", referencedColumnName="id")
     */
    private $paciente;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $usuario;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function getFecha()
    {
        return $this->fecha;
    }    

    public function SetFecha($fecha)
    {
           $this->fecha = $fecha;
    }

    public function getPeso()
    {
        return $this->peso;
    }    

    public function SetPeso($peso)
    {
           $this->peso = $peso;
    }

    public function getVacunas()
    {
        return $this->vacunas;
    }    

    public function SetVacunas($vacunas)
    {
           $this->vacunas = $vacunas;
    }

    public function getVacunasOb()
    {
        return $this->vacunasOb;
    }    

    public function SetVacunasOb($vacunasOb)
    {
           $this->vacunasOb = $vacunasOb;
    }

    public function getMaduracion()
    {
        return $this->maduracion;
    }    

    public function SetMaduracion($maduracion)
    {
           $this->maduracion = $maduracion;
    }

    public function getMaduracionOb()
    {
        return $this->maduracionOb;
    }    

    public function SetMaduracionOb($maduracionOb)
    {
           $this->maduracionOb = $maduracionOb;
    }

    public function getFisico()
    {
        return $this->fisico;
    }    

    public function SetFisico($fisico)
    {
           $this->fisico = $fisico;
    }

    public function getFisicoOb()
    {
        return $this->fisicoOb;
    }    

    public function SetFisicoOb($fisicoOb)
    {
           $this->fisicoOb = $fisicoOb;
    }

    public function getObservacion()
    {
        return $this->observacion;
    }    

    public function SetObservacion($observacion)
    {
           $this->observacion = $observacion;
    }

    public function getAlimentacion()
    {
        return $this->alimentacion;
    }    

    public function SetAlimentacion($alimentacion)
    {
           $this->alimentacion = $alimentacion;
    }

    public function getPc()
    {
        return $this->pc;
    }    

    public function SetPc($pc)
    {
           $this->pc = $pc;
    }

    public function getPpc()
    {
        return $this->ppc;
    }    

    public function SetPpc($ppc)
    {
           $this->ppc = $ppc;
    }

    public function getTalla()
    {
        return $this->talla;
    }    

    public function SetTalla($talla)
    {
           $this->talla = $talla;
    }

    public function getPaciente()
    {
        return $this->paciente;
    }    

    public function SetPaciente($paciente)
    {
           $this->paciente = $paciente;
    }

    public function getusuario()
    {
        return $this->usuario;
    }    

    public function SetUsuario($usuario)
    {
           $this->usuario = $usuario;
    }
}
