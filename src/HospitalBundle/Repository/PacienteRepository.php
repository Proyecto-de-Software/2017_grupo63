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

    private function datosCurva($paciente, $lapso, $colum)
    {
        $em = $this->getEntityManager();
        $dql = "SELECT DATE_FORMAT(h.fecha, '%Y,%m,%d') as fecha, h.$colum, h.id as his FROM HospitalBundle:Paciente p JOIN p.historias as h WHERE  h.paciente = :pac AND h.fecha < :lapso";
        $args = array('pac' => $paciente, 'lapso' => $lapso);
        $query = $em->createQuery($dql);
        $query->setParameters($args);
        //var_dump($query);
        $datos = $query->getArrayResult();
        $datosJS = array();
        foreach ($datos as $dato) {
            $arreglo = array();
            //$fechaJS = $this->sacarUnMes($dato['fecha']);
            $arreglo[] = $dato["fecha"];
            $arreglo[] = (float)$dato["$colum"];
            $datosJS[] = $arreglo;
        }
        return $datosJS;
    }

    public function curvaPeso($pacienteID)
    {
        $paciente = $this->find($pacienteID);
        $datos['name']  = $paciente->getNombre() . " " . $paciente->getApellido();
        $datos['data'] = $this->datosCurvaPeso($pacienteID, $paciente->getNacimiento());
        if ($paciente->getGenero() == 'MASCULINO') {
            $datosMedia = array(3.3, 3.5, 3.8, 4.1, 4.4, 4.7, 4.9, 5.2, 5.4, 5.6, 5.8, 6, 6.2, 6.4);
            $datosMin =   array(2.5, 2.6, 2.8, 3.1, 3.4, 3.6, 3.8, 4.1, 4.3, 4.4, 4.6, 4.8, 4.9, 5.1);
            $datosMax =   array(4.6, 4.8, 5.1, 5.5, 5.9, 6.3, 6.6, 6.9, 7.2, 7.4, 7.7, 7.9, 8.1, 8.3);
        } else {
            $datosMedia = array(3.2, 3.3, 3.6, 3.8, 4.1, 4.3, 4.6, 4.8, 5  , 5.2, 5.4, 5.5, 5.7, 5.8);
            $datosMin =   array(2.4, 2.5, 2.7, 2.9, 3.1, 3.3, 3.5, 3.7, 3.8, 4.0, 4.1, 4.3, 4.4, 4.5);
            $datosMax =   array(4.2, 4.4, 4.7, 5  , 5.4, 5.7, 6.0, 6.2, 6.5, 6.7, 6.9, 7.1, 7.3, 7.5);
        }
        
        $curvas = $this->datosCurvaTrece($paciente->getNacimiento(), $datosMin, $datosMedia, $datosMax); 
        $curvas[] = $datos;
        return $curvas;
    }

    private function datosCurvaPeso($paciente, $nacimiento)
    {
        $nac = explode("-", $nacimiento->format('Y-m-d'));
        $d = mktime(0, 0, 0, $nac[1]  , $nac[2] + 91, $nac[0]);
        //var_dump($d);
        $treceSemanas =  date("Y-m-d", $d);
        $data = $this->datosCurva($paciente, $treceSemanas, "peso"); 
        return $data;
    }

    private function datosCurvaTrece($nacimiento, $datosMin, $datosMedia, $datosMax)
    {
        $media = array('name' => 'media', 'color' => 'green');
        $max = array('name' => 'maximo', 'color' => 'red');
        $min = array('name' => 'minimo', 'color' => 'red');
        $todos = array();
         
        $nac = explode("-", $nacimiento->format('Y-m-d'));
        $fecha = date("Y-m-d", mktime(0, 0, 0, $nac[1]  , $nac[0], $nac[2]));
        $semana = 7;
        for ($i=0; $i < 14; $i++) { 
            $arregloMed = array();
            $arregloMed[] = $this->sacarUnMes($fecha);
            $arregloMed[] = $datosMedia[$i];
            $arregloMax = array();
            $arregloMax[] = $this->sacarUnMes($fecha);
            $arregloMax[] = $datosMax[$i];
            $arregloMin = array();
            $arregloMin[] = $this->sacarUnMes($fecha);
            $arregloMin[] = $datosMin[$i];
            $fecha = date("Y-m-d", mktime(0, 0, 0, $nac[1]  , $nac[0] + $semana , $nac[2]));
            $min["data"][] = $arregloMin;
            $media["data"][] = $arregloMed;
            $max["data"][] = $arregloMax;
            $semana += 7;
        }
        $todos[] = $min;
        $todos[] = $media;
        $todos[] = $max;
        return $todos;
    }

    private function sacarUnMes($fecha)
    {   
        $fechaJS = explode("-", $fecha);
        //$fechaJS[1] = (int) $fechaJS[1] - 1;
        return implode(",", $fechaJS);
    }
}