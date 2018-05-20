<?php

namespace HospitalBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PacienteRepository extends EntityRepository
{
	public function filtrado($nombre , $tipoDoc, $doc)
    {
    	$dql = "p";
    	$and = false;
    	$qb = $this->createQueryBuilder($dql);
    	$parameters = array();
    	$where = '';
    	if ($nombre != '') {
    		$and = true;
    		$where = $where . ' (p.nombre LIKE :nombre OR p.apellido LIKE :nombre)';
    		$parameters[':nombre'] = '%'. $nombre . '%';
    	}
    	if ($tipoDoc != 0) {
    		if ($and) {
    			$where = $where . ' AND';
    		}
    		$where = $where . ' (p.tipoDoc = :tipoDoc)';
    		$parameters[':tipoDoc'] = $tipoDoc ;
    		$and = true;
    	}
    	if ($doc != '') {
    		if ($and) {
    			$where = $where . ' AND';
    		}
    		$where = $where . ' (p.numDoc = :numDoc)';
    		$parameters[':numDoc'] = $doc ;
    	}
    	if ($where != '') {
	    	$qb
	    	->where($where)  
	    	->setParameters($parameters);
    	}
    	return $qb;
    }
}