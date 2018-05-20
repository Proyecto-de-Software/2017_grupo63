<?php

namespace HospitalBundle\Service;

use Doctrine\ORM\EntityManager;
use HospitalBundle\Entity\Configuracion;

/**
* 
*/
class DatosTwig
{
	private $em;

	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	public function getDatos()
	{
		$repository = $this->em->getRepository(Configuracion::class);
        $config = $repository->find(1);
		return $config;
	}

	public function getTitulo()
	{
		return $this->getDatos()->getTitulo();
	}

	public function getDescripcion()
	{
		return $this->getDatos()->getDescripcion();
	}

	public function getMail()
	{
		return $this->getDatos()->getMail();
	}

	public function getPaginado()
	{
		return $this->getDatos()->getPaginado();
	}

	public function getHabilitado()
	{
		return $this->getDatos()->isHabilitado();
	}

}