<?php

namespace HospitalBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UsuarioRepository extends EntityRepository
{
	public function findAll()
    {
        return $this->createQueryBuilder('u');
    }

    public function filtrado($username = '', $estado = 2)
    {
    	$dql = "u";
    	$username = '%'. $username . '%';
    	$qb = $this->createQueryBuilder($dql);
    	$where = 'u.username LIKE :nombre';
    	if ($estado < 2) {
    		$where = $where . ' AND u.enabled = :estado';
    		$qb->setParameter('estado', $estado);
    	}
    	$qb
    	->where($where)  
    	->setParameter('nombre', $username);
    	return $qb;
    }
}