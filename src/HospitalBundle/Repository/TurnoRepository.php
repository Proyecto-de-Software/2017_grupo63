<?php

namespace HospitalBundle\Repository;

use Doctrine\ORM\EntityRepository;


class TurnoRepository extends EntityRepository
{
	public function getTurnos($fechaTurno)
	{
		$turnos = array();
		$fecha = new \DateTime('20-01-2001');
		$fecha->add(new \DateInterval('PT7H30M'));
		for ($i=1; $i <= 24; $i++) { 
			$turno = new \DateTime($fecha->format('d-m-Y H:i:s'));
			$turno->add(new \DateInterval("PT" . 30 * $i ."M"));
			$turnos[] = $turno->format('H:i:s') ;
		}
		$ocupados = $this->getOcupados($fechaTurno);
		foreach ($ocupados as $ocupado) {
				//unset($turnos[array_search($ocupado['hora'], $turnos)]);
				array_splice($turnos, array_search($ocupado['hora'], $turnos), 1);
			}
		return $turnos;
	}

	private function getOcupados($fecha)
	{
		
		$fechaSQL = strtotime($fecha);
		$inicio = new \DateTime($fecha);  
    	$to   = new \DateTime($inicio->format("Y-m-d")." 23:59:59");
		$from = new \DateTime($inicio->format("Y-m-d")." 00:00:00");
		$inicio->add(new \DateInterval('PT8H'));
		$inicio = $inicio->format('Y-m-d H:i:s');
		$fin = new \DateTime($fecha);
		$fin->add(new \DateInterval('PT20H'));
		$fin = $fin->format('Y-m-d H:i:s');
		$dql = 't';
		$qb = $this->createQueryBuilder($dql);
    	$qb ->select("DATE_FORMAT(t.fecha, '%H:%i:%s') as hora")
    		->andWhere("t.fecha BETWEEN :inicio AND :fin")
    		->setParameter('inicio', $from)
    		->setParameter('fin', $to);
    	return $qb->getQuery()->getArrayResult();
	}

	public function estaOcupado($fecha, $hora)
	{
		$turno = new \DateTime($fecha . " " . $hora);
		$qb = $this->createQueryBuilder('t');
		$qb
			->andWhere("t.fecha = :fecha")
			->setParameter('fecha', $turno);
		$ocupado = $qb->getQuery()->getResult();
		return !empty($ocupado);
	}

}